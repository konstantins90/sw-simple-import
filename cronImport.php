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
const STATUS_ERROR = 'error';

define('ROOT_PATH', __DIR__);

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

    try {
        $file->setStatus(STATUS_RUN);
        $file->save();

        // Initialisiere den Parser und Prozessor für jede Datei
        $fileProcessor = FileProcessorFactory::createProcessor($csvFilePath);
        $fileProcessor->setConfigFile($file);
        $fileProcessor->setLogger();
        
        $csvParser = new CsvParser($csvFilePath);

        $headers = $csvParser->getHeaders();
        $duplicates = array_filter(array_count_values($headers), fn($count) => $count > 1);

        if (!empty($duplicates)) {
            $message = "Doppelte Spaltennamen gefunden: " . implode(', ', array_keys($duplicates));
            $fileProcessor->addLogMessage($message);
            throw new RuntimeException($message);
        }

        $productArray = [];
        foreach($csvParser->getRecords() as $record) {
            $productArray[] = $record;
        }

        $fileProcessor->setRecords($productArray);
        $fields = $fileProcessor->getRecords();
        if ($fileProcessor->getConfigFile()->getImportType() == 'created_updated') {
            $fileProcessor->mapProductProperties();
        }
        $fileProcessor->import();
        $fileProcessor->showLog();

        $file->setStatus(STATUS_COMPLETED);
        $file->save();
        
        echo "Datei $csvFilePath erfolgreich verarbeitet.\n";
    } catch (RuntimeException $e) {
        $message = "Fehler bei Datei $csvFilePath: " . $e->getMessage() . "\n";
        echo $message;
        $file->setStatus(STATUS_ERROR);
        $fileProcessor->addLogMessage($message);
    } catch (Exception $e) {
        $message = "Unerwarteter Fehler bei Datei $csvFilePath: " . $e->getMessage() . "\n";
        echo $message;
        $file->setStatus(STATUS_ERROR);
        $fileProcessor->addLogMessage($message);
    } finally {
        $file->save();
    }
}

unlink($lockFile);