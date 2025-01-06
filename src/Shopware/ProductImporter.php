<?php

namespace App\Shopware;

class ProductImporter
{
    private ShopwareApiClient $client;

    public function __construct(ShopwareApiClient $client)
    {
        $this->client = $client;
    }

    public function import(array $productData): void
    {
        $this->client->importProduct($productData);
    }
}
