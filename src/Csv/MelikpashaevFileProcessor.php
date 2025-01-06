<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;

class MelikpashaevFileProcessor implements FileProcessorInterface
{
    protected ShopwareApiClient $shopwareClient;

    public function __construct(ShopwareApiClient $shopwareClient)
    {
        $this->shopwareClient = $shopwareClient;
    }

    public function process(array $record): void
    {
        $productData = $this->parseData($record);

        // d($productData);
        
        // if ($this->shopwareClient->productExists($record['isbn'])) {
        //     $this->shopwareClient->updateProduct($record['isbn'], $productData);
        // } else {
        //     $this->shopwareClient->importProduct($productData);
        // }
    }

    protected function parseData(array $record): array
    {
        $productData = [
            'name' => $record['Название издания'],
            'productNumber' => $record['ISBN'],
            'isbn' => $record['ISBN'],
            'price' => [
                [
                    'currencyId' => self::CURRENCY_EUR,
                    'gross' => 19.99,
                    'net' => 16.80
                ]
            ],
            'stock' => 10,
            'taxId' => self::TAX_REDUCED,
            'manufacturer' => 'Мелик-Пашаев',
            'author' => $record['АВТОР'],
            'series' => $record['СЕРИЯ'],
            'age' => $record['В+'],
            'releaseYear' => $record['Год'],
            'pageCount' => $record['СТР.'],
        ];

        return $productData;
    }
}
