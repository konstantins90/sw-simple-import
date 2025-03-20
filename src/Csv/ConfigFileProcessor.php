<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Csv\PropertyCollector;
use App\Csv\ImageDownloader;
use App\Utils\PriceCalculator;
use Propel\Files;

class ConfigFileProcessor extends FileProcessorDefault implements FileProcessorInterface
{
    protected array $logs = [];
    protected array $errors = [];
    protected array $records = [];
    protected Files $configFile;
    protected array $propertiesFields = [];
    protected array $currentRecord = [];

    public function __construct(
        protected ShopwareApiClient $shopwareClient,
        protected PropertyCollector $propertyCollector,
        protected PriceCalculator $priceCalculator,
        protected ImageDownloader $imageDownloader
    ){
        
    }

    public function setRecords(array $records): void
    {
        foreach($records as $record) {
            $item = $this->parseData($record);
            if ($item['name'] == '' || $item['ean'] == '') {
                continue;
            }
            $this->records[] = $item;
        }
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    public function getPropertiesArray(): array
    {
        foreach($this->getRecords() as $productData) {
            $this->propertyCollector->collectProperties($productData);
        }
        $propertiesArray = $this->propertyCollector->getPropertiesArray();
        return $propertiesArray;
    }

    public function downloadImages(): void
    {
        foreach($this->records as &$productData) {
            $productData['media'] = $this->imageDownloader->downloadImage($productData);
        }
    }

    public function mapProductProperties(): void
    {
        $mappedProperties = [];
        $startTime = microtime(true);
        echo "Generiere Produkt Properties";

        $this->downloadImages();
        $propertiesArray = $this->getPropertiesArray();

        $endTime = microtime(true);
        $executionTime = $startTime - $endTime;
        echo ": {$executionTime} s\n";

        foreach($this->records as &$productData)
        {
            $productData['propertiesMapped'] = [];
            foreach ($productData['properties'] as $propertyKey => $propertyValue) {
                // Если свойство присутствует в propertiesArray
                if (isset($propertiesArray[$propertyKey])) {
                    // Ищем соответствующее имя в массиве options и получаем его ID
                    foreach ($propertiesArray[$propertyKey]['options'] as $option) {
                        if ($option['name'] === $propertyValue) {
                            // Добавляем ID свойства в массив mappedProperties
                            $productData['propertiesMapped'][] = [
                                'name' => $propertyValue,
                                'id' => $option['id']
                            ];
                            break;
                        }
                    }
                }
            }

            $productData['properties'] = $productData['propertiesMapped'];
            unset($productData['propertiesMapped']);
        }
    }

    public function import(): void
    {
        $count = 0;
        $this->shopwareClient->getManufacturerList(true);
        foreach($this->getRecords() as $productData)
        {
            $this->shopwareClient->checkTimeout();
            $this->importProduct($productData);
            $count++;
        }
    }

    public function importProduct(array $productData): bool
    {
        $productNumber = $productData['productNumber'];
        
        // Überprüfen, ob das Produkt bereits existiert
        $productId = $this->shopwareClient->productExists($productNumber);
        $this->logs[$productNumber] = [];

        if (isset($productData['manufacturer'])) {
            $manufacturer = $productData['manufacturer'];
            unset($productData['manufacturer']);

            $manufacturerId = $this->shopwareClient->findManufacturer($manufacturer);

            if ($manufacturerId === false) {
                $manufacturerId = $this->shopwareClient->createManufacturer($manufacturer);
            }

            if ($manufacturerId !== false) {
                $productData['manufacturerId'] = $manufacturerId;
            }
        }


        if ($productId) {
            $this->logs[$productNumber][] = 'Produkt existiert, aktualisiere es';
            // Produkt existiert, aktualisiere es
            unset($productData['visibilities']);
            // d($productData);
            $this->shopwareClient->updateProduct($productId, $productData);
        } else {
            // Produkt existiert nicht, importiere es
            $this->logs[$productNumber][] = 'Produkt existiert nicht, importiere es';
            $productId = $this->shopwareClient->importProduct($productData);
        }

        if (!$productId) {
            d("Produkt nicht gefunden: $productId");
            return false;
        }

        // d($productData);

        // Wenn Medien vorhanden sind, lade sie hoch und füge sie dem Produkt hinzu
        if ($this->shopwareClient->productHasMedia($productId) == false) {
            $this->logs[$productNumber][] = 'Neues Media wird erstellt';

            $mediaName = isset($productData['ean']) ? $productData['ean'] : $productData['productNumber'];
            // d($mediaName);
            $mediaId = $this->shopwareClient->findMediaByName($mediaName);

            if (empty($mediaId) && isset($productData['media'])) {
                $mediaId = $this->shopwareClient->createMedia($productData['media']);

                if (!empty($mediaId)) {
                    $this->logs[$productNumber][] = 'Media wird hochgeladen';
                    $uploadMediaResult = $this->shopwareClient->uploadMedia($mediaId, $productData['media']);

                    if (!$uploadMediaResult) {
                        return false;
                    }
                }
            }

            if (empty($mediaId)) {
                return false;
            }

            $this->logs[$productNumber][] = 'Media wird zu product gesetzt';
            $productMediaId = $this->shopwareClient->addMediaToProduct($productId, $mediaId);
            
            if ($productMediaId) {
                $this->logs[$productNumber][] = 'Cover wird gesetzt';
                $this->shopwareClient->productSetCover($productId, $productMediaId);
            }
        }

        return true;
    }

    protected function parseData(array $record): array
    {
        $this->currentRecord = $record;
        $this->priceCalculator->setMargin((float) $this->getPriceMarge());
        $this->priceCalculator->setExchangeRate((float) $this->getExchangeRate());
        $netPrice = $this->priceCalculator->convertRubToEurWithMargin((float) $this->getProperty('price'));
        $grossPrice = $this->priceCalculator->convertNetToGross($netPrice, 0.07);

        $productData = [
            'name' => $this->getProperty('name'),
            'description' => $this->getProperty('description'),
            'productNumber' => $this->getProperty('productNumber'),
            'ean' => $this->getProperty('ean'),
            'price' => [
                [
                    'currencyId' => self::CURRENCY_EUR,
                    'net' => $netPrice,
                    'gross' => $grossPrice,
                    'linked' => false
                ]
            ],
            'active' => ($this->getConfigFile()->getImportType() === 'disable' ? false : true),
            'stock' => (int) $this->getProperty('stock'),
            'taxId' => $this->getProperty('taxId'),
            'manufacturer' => $this->getProperty('manufacturer'),
            'weight' => floatval(str_replace(",", ".", $this->getProperty('weight'))),
            'media' => $this->getProperty('media'),
            'visibilities' => [
                [
                    'salesChannelId' => $this->getProperty('salesChannelId'),
                    'visibility' => (int) $this->getProperty('visibility')
                ]
            ],
            'properties' => $this->getPropertiesData(),
            'customFields' => [
                'custom_preorder_status' => $this->getCustomField('preorder') == '1' ? true : false,
                'custom_preorder_deadline' => $this->getCustomField('preorder_deadline'),
                'custom_preorder_delivery_date' => $this->getCustomField('preorder_delivery'),
                'custom_preorder_order_status' => $this->getCustomField('preorder_state'),
                // 'custom_preorder_publisher' => $this->getCustomField('status'),
            ]
        ];

        $this->currentRecord = [];

        return $productData;
    }

    public function showLog(): void
    {
        d($this->logs);
    }

    public function setConfigFile(Files $configFile): void
    {
        $this->configFile = $configFile;
    }

    public function getConfigFile()
    {
        return $this->configFile;
    }

    public function getPriceMarge()
    {
        return $this->getConfigFile()->getMarge() ?: $this->getConfigFile()->getConfig()?->getMarge();
    }

    public function getExchangeRate()
    {
        return $this->getConfigFile()->getExchangeRate() ?: 1;
    }

    public function getMapping(): array
    {
        $jsonString = $this->getConfigFile()->getConfig()->getMapping();
        $cleanedJsonString = str_replace(['\r\n', '\r', '\n'], "", $jsonString);
        return json_decode($cleanedJsonString, true);
    }

    public function getPropertiesFields(): array
    {
        if (!count($this->propertiesFields)) {
            $mapping = $this->getMapping();
            $this->propertiesFields = array_diff_key($mapping, array_flip(self::getDefaultFields()));
        }

        return $this->propertiesFields;
    }

    public function getProperty(string $name): string
    {
        $result = '';
        $fields = $this->getMapping();
        if (!isset($fields[$name])) {
            return $result;
        }

        $field = $fields[$name];

        if ($field['type'] === 'csv') {
            if (isset($this->currentRecord[$field['csv']])) {
                $result = $this->currentRecord[$field['csv']];
            }
        } else {
            $result = $field['default'] ?: '';
        }

        if ($name === 'productNumber' && $prefix = $this->getConfigFile()->getPrefix()) {
            $result = (string) $prefix . '-' . $result;
        }

        return $result;
    }

    protected function getCustomField(string $name): string
    {
        $result = '';
        $enabled = $this->getConfigFile()->getPreorder();

        if ((string) $enabled !== '1') {
            return $result;
        }

        switch ($name) {
            case 'preorder':
                $result = $enabled;
                break;
            case 'preorder_deadline':
                $result = $this->getConfigFile()->getPreorderDeadline() ? $this->getConfigFile()->getPreorderDeadline()->format('Y-m-d\\TH:i') : '';
                break;
            case 'preorder_delivery':
                $result = $this->getConfigFile()->getPreorderDelivery() ? $this->getConfigFile()->getPreorderDelivery()->format('Y-m-d\\TH:i') : '';
                break;
            case 'preorder_state':
                $result = $this->getConfigFile()->getPreorderState() ?: '';
                break;
        }

        return $result;
    }

    protected function getPropertiesData():array
    {
        $data = [];
        $fields = $this->getPropertiesFields()['properties'];

        foreach ($fields as $key => $field) {
            if ($field['type'] != 'csv') {
                $data[$key] = $field['default'];
            } else {
                if (isset($this->currentRecord[$field['csv']])) {
                    $data[$key] = $this->currentRecord[$field['csv']];
                }
            }
        }

        return $data;
    }
}
