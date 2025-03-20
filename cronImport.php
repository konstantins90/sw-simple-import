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
const STATUS_COMPLETED = 'imported';
const STATUS_RUN = 'run';

$lockFile = __DIR__ . '/import.lock';

if (file_exists($lockFile)) {
    echo "Ein Import läuft bereits. Beende Skript.\n";
    exit;
}
file_put_contents($lockFile, getmypid());
chmod($lockFile, 0777);

$files = FilesQuery::create()
    ->orderByUpdatedAt(Criteria::ASC)
    ->filterByStatus(['eq' => STATUS_PENDING])
    ->limit(1)
    ->find();

$csvDir = __DIR__ . '/csv';

foreach ($files as $file) {
    $csvFilePath = $csvDir . '/' . $file->getPath();
    echo "Bearbeite Datei: $csvFilePath\n";
    $file->setStatus(STATUS_RUN);
    $file->save();

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
    $fileProcessor->mapProductProperties();
    $fileProcessor->import();
    $fileProcessor->showLog();

    $file->setStatus(STATUS_COMPLETED);
    $file->save();
    
    echo "Datei $csvFilePath erfolgreich verarbeitet.\n";
}

unlink($lockFile);