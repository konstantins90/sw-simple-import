<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Csv\PropertyCollector;
use App\Utils\PriceCalculator;
use Propel\Files;

class FileProcessorDefault implements FileProcessorInterface
{
    protected array $logs = [];
    protected array $errors = [];
    protected array $records = [];

    public function __construct(
        protected ShopwareApiClient $shopwareClient,
        protected PropertyCollector $propertyCollector,
        protected PriceCalculator $priceCalculator
    ) {}

    static public function getDefaultFields(): array
    {
        return [
            'name',
            'description',
            'productNumber',
            'ean',
            'stock',
            'taxId',
            'price',
            'manufacturer',
            'weight',
            'media',
            'salesChannelId',
            'visibility'
        ];
    }

    public function setRecords(array $records): void
    {
        $this->records = $records;
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    public function getPropertiesArray(): array
    {
        foreach ($this->getRecords() as $productData) {
            $this->propertyCollector->collectProperties($productData);
        }
        $propertiesArray = $this->propertyCollector->getPropertiesArray();
        return $propertiesArray;
    }

    public function mapProductProperties(): void {}

    public function import(): void
    {
        $count = 0;
        foreach ($this->getRecords() as $productData) {
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
            d($productData);
            $this->shopwareClient->updateProduct($productId, $productData);
        } else {
            // Produkt existiert nicht, importiere es
            $this->logs[$productNumber][] = 'Produkt existiert nicht, importiere es';
            $productId = $this->shopwareClient->importProduct($productData);
        }

        if (!$productId) {
            return false;
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

        return true;
    }

    public function showLog(): void
    {
        d($this->logs);
    }

    public function setConfigFile(Files $configFile): void
    {
        $this->configFile = $configFile;
    }
}
