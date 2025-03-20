<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Config\ConfigLoader;
use App\Csv\PropertyCollector;
use App\Utils\PriceCalculator;
use GuzzleHttp\Client;
use App\Csv\ImageDownloader;
use Propel\Files;

class FileProcessorFactory
{
    public static function createProcessor(string $filename): FileProcessorInterface
    {
        $config = ConfigLoader::load();
        $shopwareClient = new ShopwareApiClient(
            $config['shopware_api_url'],
            $config['client_id'],
            $config['client_secret']
        );

        $httpClient = new Client();

        $propertyCollector = new PropertyCollector($shopwareClient);
        $priceCalculator = new PriceCalculator($httpClient);
        $imageDownloader = new ImageDownloader();

        return new ConfigFileProcessor($shopwareClient, $propertyCollector, $priceCalculator, $imageDownloader);
    }
}
