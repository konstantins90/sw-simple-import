<?php

namespace App\Utils;

class Logger
{
    public static function log(string $message): void
    {
        // Einfache Logik zum Protokollieren von Nachrichten
        file_put_contents('log.txt', $message . PHP_EOL, FILE_APPEND);
    }
}
