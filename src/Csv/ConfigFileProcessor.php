<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Csv\PropertyCollector;
use App\Csv\ImageDownloader;
use App\Utils\PriceCalculator;
use Propel\Files;
use Propel\ImportHistory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ConfigFileProcessor extends FileProcessorDefault implements FileProcessorInterface
{
    protected array $logs = [];
    protected array $errors = [];
    protected array $records = [];
    protected Files $configFile;
    protected array $propertiesFields = [];
    protected array $currentRecord = [];

    protected $logger = null;
    protected $importHistory = null;

    public function __construct(
        protected ShopwareApiClient $shopwareClient,
        protected PropertyCollector $propertyCollector,
        protected PriceCalculator $priceCalculator,
        protected ImageDownloader $imageDownloader
    ){
        $this->importHistory = new ImportHistory();
    }

    public function setLogger()
    {
        $filePath = $this->getConfigFile()->getPath();
        $fileInfo = pathinfo($filePath);
        $filename = $fileInfo['filename'];
        $filename .= "-" . microtime(true) . ".log";
        $this->logger = new Logger('mein_logger');
        $this->logger->pushHandler(new StreamHandler(ROOT_PATH . "/logs/" . $filename, Logger::DEBUG));

        $this->importHistory->setLogFile($filename);
        $this->importHistory->save();
    }

    public function addLogMessage(string $message): void
    {
        $this->logger->info($message);
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
        $productCount = count($this->records);
        $message = "Download Bilder ($productCount)";
        echo $message;
        $this->logger->info($message);
        $count = 1;
        foreach($this->records as &$productData) {
            $productNumber = $productData['productNumber'];
            $message = "Suche das Bild für $productNumber ($count / $productCount)";
            echo $message;
            $this->logger->info($message);
            $media = $this->imageDownloader->downloadImage($productData);

            $productData['media'] = $media;

            $message = "Das Bild: $media";
            echo $message;
            $this->logger->info($message);

            $count++;
        }
    }

    public function mapProductProperties(): void
    {
        $message = "Generiere Produkt Properties";
        echo $message;
        $this->logger->info($message);

        $mappedProperties = [];

        $this->downloadImages();
        $propertiesArray = $this->getPropertiesArray();

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
        if ($this->getConfigFile()->getImportType() === 'disable') {
            $message = "Produkte werden deaktiviert";
            echo $message;
            $this->logger->info($message);
            foreach($this->getRecords() as $productData)
            {
                $this->shopwareClient->checkTimeout();
                $this->disableProduct($productData);
            }

            return;
        }

        if ($this->getConfigFile()->getImportType() === 'remove') {
            $message = "Produkte werden gelöscht";
            echo $message;
            $this->logger->info($message);
            $toRemove = [];
            foreach($this->getRecords() as $productData)
            {
                $this->shopwareClient->checkTimeout();

                $productNumber = $productData['productNumber'];
                $productId = $this->shopwareClient->productExists($productNumber);
                if ($productId) {
                    $toRemove[] = [
                        'id' => $productId
                    ];
                }

                // $this->deleteProduct($productData);
            }
            $message = "Anzahl: " . count($toRemove);
            echo $message;
            $this->logger->info($message);
            if (count($toRemove) > 0) {
                $this->shopwareClient->deleteProductArray($toRemove);
            }
            return;
        }

        $message = "Produkte werden importiert / aktualisiert";
        echo $message;
        $this->logger->info($message);

        $count = 1;
        $this->shopwareClient->getManufacturerList(true);

        $productCount = count($this->getRecords());

        $message = "Produkte Anzahl: $productCount";
        echo $message;
        $this->logger->info($message);

        foreach($this->getRecords() as $productData)
        {
            $this->shopwareClient->checkTimeout();

            $productNumber = $productData['productNumber'];
            $message = "Produkt ID: $productNumber ($count / $productCount) wird importiert";
            echo $message;
            $this->logger->info($message);

            $this->importProduct($productData);
            $count++;
        }
    }

    public function deleteProduct(array $productData): bool
    {
        $productNumber = $productData['productNumber'];
        $productId = $this->shopwareClient->productExists($productNumber);

        if ($productId) {
            $this->logs[$productNumber][] = 'Produkt existiert, es wird gelöscht';
            // Produkt existiert, aktualisiere es
            $this->shopwareClient->deleteProduct($productId);
        } else {
            $this->logs[$productNumber][] = 'Produkt existiert nicht, ignoriert';
        }

        if (!$productId) {
            d("Produkt nicht gefunden: $productId");
            return false;
        }

        return true;
    }

    public function disableProduct(array $productData): bool
    {
        $productNumber = $productData['productNumber'];
        $productId = $this->shopwareClient->productExists($productNumber);

        if ($productId) {
            $this->logs[$productNumber][] = 'Produkt existiert, deaktiviere es';
            // Produkt existiert, aktualisiere es
            $this->shopwareClient->updateProduct($productId, ['active' => false]);
        } else {
            $this->logs[$productNumber][] = 'Produkt existiert nicht, ignoriert';
        }

        if (!$productId) {
            d("Produkt nicht gefunden: $productId");
            return false;
        }

        return true;
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
        $netPrice = $this->priceCalculator->convertRubToEurWithMargin($this->getProperty('price'));
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
        $this->importHistory->setFileId($this->configFile->getId());
        $this->importHistory->save();
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
