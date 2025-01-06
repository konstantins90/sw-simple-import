<?php

require __DIR__ . '/../vendor/autoload.php'; // Lade den Autoloader

use Dotenv\Dotenv;

// Lade die .env Datei
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

return [
    'shopware_api_url' => $_ENV['SHOPWARE_API_URL'],
    'client_id' => $_ENV['SHOPWARE_CLIENT_ID'],
    'client_secret' => $_ENV['SHOPWARE_CLIENT_SECRET'],
];
