<?php

require __DIR__ . '/vendor/autoload.php';

use Propel\Runtime\Propel;

Propel::init(__DIR__ . '/generated-conf/config-cli.php');

use Propel\FilesQuery;
use Propel\ConfigQuery;
use Propel\Config;
use Propel\Runtime\ActiveQuery\Criteria;

use App\Csv\CsvParser;
use App\Csv\FileProcessorFactory;

const STATUS_PENDING = 'pending';

$files = FilesQuery::create()
    ->orderByUpdatedAt(Criteria::ASC)
    ->filterByStatus(['eq' => STATUS_PENDING])
    ->limit(5)
    ->find();

$csvDir = __DIR__ . '/csv';

foreach ($files as $file) {
    $csvFilePath = $csvDir . '/' . $file->getPath();
    echo "Bearbeite Datei: $csvFilePath\n";

    // Initialisiere den Parser und Prozessor für jede Datei
    $csvParser = new CsvParser($csvFilePath);
    $fileProcessor = FileProcessorFactory::createProcessor($csvFilePath);

    $productArray = [];
    foreach($csvParser->getRecords() as $record) {
        $productArray[] = $record;
    }

    $fileProcessor->setConfigFile($file);
    $fileProcessor->setRecords($productArray);
    $fields = $fileProcessor->getRecords();
    d($fields);
    die('STOP');
    $fileProcessor->mapProductProperties();
    $fileProcessor->import();

    $fileProcessor->showLog();
    
    echo "Datei $csvFilePath erfolgreich verarbeitet.\n";
}