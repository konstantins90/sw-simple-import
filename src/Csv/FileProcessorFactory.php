<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;
use App\Config\ConfigLoader;
use App\Csv\PropertyCollector;
use App\Utils\PriceCalculator;
use GuzzleHttp\Client;

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

        if (str_contains($filename, 'melikpashaev')) {
            return new MelikpashaevFileProcessor($shopwareClient);
        } elseif (str_contains($filename, 'polyandrija')) {
            return new PolyandrijaFileProcessor($shopwareClient, $propertyCollector, $priceCalculator);
        } elseif (str_contains($filename, 'nigma')) {
            return new NigmaFileProcessor($shopwareClient, $propertyCollector, $priceCalculator);
        } else {
            throw new \Exception("Kein passender Prozessor für die Datei $filename gefunden.");
        }
    }
}
