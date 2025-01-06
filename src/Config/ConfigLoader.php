<?php

namespace App\Config;

use Dotenv\Dotenv;

class ConfigLoader
{
    public static function load(): array
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        return [
            'shopware_api_url' => $_ENV['SHOPWARE_API_URL'],
            'client_id' => $_ENV['SHOPWARE_CLIENT_ID'],
            'client_secret' => $_ENV['SHOPWARE_CLIENT_SECRET'],
        ];
    }
}