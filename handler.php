<?php

require __DIR__ . '/vendor/autoload.php';

use App\Csv\CsvParser;
use App\Csv\FileProcessorFactory;

$csvDir = __DIR__ . '/csv';
$csvFiles = array_filter(scandir($csvDir), function ($file) use ($csvDir) {
    // Überprüfen, ob es sich um eine CSV-Datei handelt und der Dateiname nicht mit "ignore_" beginnt
    return pathinfo($file, PATHINFO_EXTENSION) === 'csv' &&
           !is_dir($csvDir . '/' . $file) &&
           strpos($file, 'ignore_') !== 0;
});

foreach ($csvFiles as $csvFile) {
    $csvFilePath = $csvDir . '/' . $csvFile;
    echo "Bearbeite Datei: $csvFilePath\n";

    // Initialisiere den Parser und Prozessor für jede Datei
    $csvParser = new CsvParser($csvFilePath);
    $fileProcessor = FileProcessorFactory::createProcessor($csvFilePath);

    $productArray = [];
    foreach($csvParser->getRecords() as $record) {
        $productArray[] = $record;
    }

    $fileProcessor->setRecords($productArray);
    $fileProcessor->mapProductProperties();
    $fileProcessor->import();

    $fileProcessor->showLog();
    
    echo "Datei $csvFilePath erfolgreich verarbeitet.\n";
}