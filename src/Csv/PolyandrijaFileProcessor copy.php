<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Csv\PropertyCollector;
use App\Utils\PriceCalculator;

class PolyandrijaFileProcessor implements FileProcessorInterface
{
    protected array $logs = [];
    protected array $errors = [];
    protected array $records = [];

    public function __construct(
        protected ShopwareApiClient $shopwareClient,
        protected PropertyCollector $propertyCollector,
        protected PriceCalculator $priceCalculator
    ){
        
    }

    public function setRecords(array $records): void
    {
        foreach($records as $record) {
            $this->records[] = $this->parseData($record);
        }
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    // public function importProperties(): void
    // {
        
    // }

    public function getPropertiesArray(): array
    {
        foreach($this->getRecords() as $productData) {
            $this->propertyCollector->collectProperties($productData);
        }
        $propertiesArray = $this->propertyCollector->getPropertiesArray();
        return $propertiesArray;
    }

    public function mapProductProperties(): void
    {
        $mappedProperties = [];
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
        $count = 0;
        foreach($this->getRecords() as $productData)
        {
            $this->importProduct($productData);
            $count++;
            if ($count > 10) {
                die('STOP 10');
            }
        }
    }

    public function importProduct(array $productData): void
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
            d($productData);
            $this->shopwareClient->updateProduct($productId, $productData);
        } else {
            // Produkt existiert nicht, importiere es
            $this->logs[$productNumber][] = 'Produkt existiert nicht, importiere es';
            $productId = $this->shopwareClient->importProduct($productData);
        }

        if (!$productId) {
            die('STOP');
        }

        // Wenn Medien vorhanden sind, lade sie hoch und füge sie dem Produkt hinzu
        if (isset($productData['media']) && $this->shopwareClient->productHasMedia($productId) == false) {
            $this->logs[$productNumber][] = 'Neues Media wird erstellt';
            $mediaId = $this->shopwareClient->createMedia($productData['media']);

            if ($mediaId !== false) {
                $this->logs[$productNumber][] = 'Media wird hochgeladen';
                $this->shopwareClient->uploadMedia($mediaId, $productData['media']);
                $this->logs[$productNumber][] = 'Media wird zu product gesetzt';
                $productMediaId = $this->shopwareClient->addMediaToProduct($productId, $mediaId);

                if ($productMediaId) {
                    $this->logs[$productNumber][] = 'Cover wird gesetzt';
                    $this->shopwareClient->productSetCover($productId, $productMediaId);
                }
            }
        }
    }

    protected function parseData(array $record): array
    {
        $netPrice = $this->priceCalculator->convertRubToEurWithMargin((float) $record['Цена, руб.']);
        $grossPrice = $this->priceCalculator->convertNetToGross($netPrice, 0.07);

        $productData = [
            'name' => $record['Название '],
            'productNumber' => $record['ISBN (эту колонку не удалять ни при каких обстоятельствах)'],
            'ean' => $record['ISBN (эту колонку не удалять ни при каких обстоятельствах)'],
            'price' => [
                [
                    'currencyId' => self::CURRENCY_EUR,
                    'net' => $netPrice,
                    'gross' => $grossPrice,
                    'linked' => false
                ]
            ],
            'stock' => 10,
            'taxId' => self::TAX_REDUCED,
            'manufacturer' => $record['Издательство'],
            'properties' => [
                'publishing' => 'Поляндрия',
                'author' => $record['Автор'],
                'illustrator' => $record['Иллюстратор'],
                'series' => $record['Серия'],
                'age' => $record['Маркировка по N436-ФЗ'],
                'releaseYear' => $record['Год'],
                'pageCount' => $record['Кол-во стр.'],
            ],
            'media' => $record['Ссылка на обложку'],
            'visibilities' => [
                [
                    'salesChannelId' => self::SALES_CHANNEL,
                    'visibility' => 30
                ]
            ]
        ];

        return $productData;
    }

    public function showLog(): void
    {
        d($this->logs);
    }
}
