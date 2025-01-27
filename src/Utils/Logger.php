<?php

namespace App\Utils;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    private $logger;

    public function __construct($logFile = 'app.log')
    {
        // Erstelle ein Logger-Objekt
        $this->logger = new MonologLogger('app_logger');
        
        // StreamHandler: Log-Datei, Fehlerlevel
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/' . $logFile, MonologLogger::DEBUG));
    }

    // Logge eine Nachricht
    public function log($level, $message)
    {
        $this->logger->log($level, $message);
    }

    // Zusätzliche Methoden für bestimmte Log-Level
    public function info($message)
    {
        $this->logger->info($message);
    }

    public function warning($message)
    {
        $this->logger->warning($message);
    }

    public function error($message)
    {
        $this->logger->error($message);
    }
}
