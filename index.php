<?php
require __DIR__ . '/vendor/autoload.php';

use Propel\Runtime\Propel;

Propel::init(__DIR__ . '/generated-conf/config.php');

use App\Controller\UploadController;
use App\Csv\FileProcessorDefault;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use Propel\FilesQuery;
use Propel\ConfigQuery;
use Propel\Config;
use Propel\ImportHistory;
use Propel\ImportHistoryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Map\ImportHistoryTableMap;

define('ROOT_PATH', __DIR__);

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);
error_reporting(E_ALL);
ini_set('display_errors', '1');
$router = new \AltoRouter();

$dbConnection = [
    'status' => false,
    'message' => null,
];

try {
    $connection = Propel::getServiceContainer()->getConnection();
    $result = $connection->query('SELECT * FROM config');
    $dbConnection['status'] = true;
    $dbConnection['message'] = 'Соединение c базой данных успешно.';
} catch (\Exception $e) {
    $dbConnection['status'] = false;
    $dbConnection['message'] = "Ошибка соединения c базой данных: " . $e->getMessage();
}

$lockFileStatus = false;
$lockFile = __DIR__ . '/import.lock';
if (file_exists($lockFile)) {
    $lockFileStatus = true;
}

$router->map('GET', '/', function () use ($twig, $dbConnection, $lockFileStatus) {
    echo $twig->render('index.html.twig', [
        'title' => 'Админ Панель',
        'dbConnection' => $dbConnection,
        'lockFileStatus' => $lockFileStatus,
    ]);
});

$router->map('GET', '/files', function () use ($twig) {
    $files = FilesQuery::create()
        ->orderByCreatedAt(Criteria::DESC)
        // ->limit(5)
        ->find();

    $fileData = [];
    foreach ($files as $file) {
        $fileData[] = [
            'id' => $file->getId(),
            'name' => $file->getFilename(),
            'path' => $file->getPath(),
            'config_id' => $file->getConfigId(),
            'config_name' => $file->getConfig()->getName(),
            'status' => $file->getStatus(),
            'created_at' => $file->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    // JSON-Antwort zurückgeben
    header('Content-Type: application/json');
    echo json_encode($fileData);
});

$router->map('DELETE', '/files/delete/[i:id]', function ($id) {
    $file = FilesQuery::create()->findPk($id);

    if ($file) {
        $filePath = __DIR__ . '/csv/' . $file->getPath(); // Datei-Pfad basierend auf der `path`-Spalte

        // Datei vom Dateisystem löschen
        if (file_exists($filePath)) {
            unlink($filePath); // Datei löschen
        }

        // Datenbankeintrag löschen
        $file->delete();

        echo json_encode(['status' => 'success', 'message' => 'Datei erfolgreich gelöscht.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datei nicht gefunden.']);
    }

    // Zurück zur Dateiübersicht
    header('Location: /files');
    exit;
});

$router->map('GET', '/files/[i:id]', function ($id) {
    $file = FilesQuery::create()->findPk($id);

    if ($file) {
        $filePath = __DIR__ . '/csv/' . $file->getPath(); // Datei-Pfad basierend auf der `path`-Spalte

        if (file_exists($filePath)) {
            $csvContent = file_get_contents($filePath);

            // Send the CSV content back as JSON
            echo json_encode([
                'status' => 'success',
                'fileContent' => $csvContent
            ]);
        } else {
            echo json_encode(['status' => 'errorR' . $filePath, 'message' => 'Datei nicht gefunden.']);
        };
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datei nicht gefunden.']);
    }
    exit;
});

$router->map('GET', '/download/[i:id]', function ($id) {
    $file = FilesQuery::create()->findPk($id);

    if ($file) {
        $filePath = __DIR__ . '/csv/' . $file->getPath(); // Datei-Pfad basierend auf der `path`-Spalte

        if (file_exists($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));

            readfile($filePath);
        } else {
            echo json_encode(['status' => 'errorR' . $filePath, 'message' => 'Datei nicht gefunden.']);
        };
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datei nicht gefunden.']);
    }
    exit;
});

$router->map('GET', '/files/edit/[i:id]', function ($id) use ($twig, $dbConnection) {
    $file = FilesQuery::create()->findPk($id);

    if (!$file) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "File not found";
        exit;
    }

    $criteria = new Criteria();
    $criteria->addDescendingOrderByColumn(ImportHistoryTableMap::COL_ID);
    
    $imports = $file->getImportHistories($criteria);
    
    echo $twig->render('file_edit.html.twig', [
        'file' => $file,
        'title' => 'Edit Datei',
        'dbConnection' => $dbConnection,
        'imports' => $imports
    ]);
});

$router->map('GET', '/log/[i:id]', function ($id) use ($twig, $dbConnection) {
    $import = ImportHistoryQuery::create()->findPk($id);

    if (!$import) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "File not found";
        exit;
    }

    $logFile = ROOT_PATH . "/logs/" . $import->getLogFile();
    $logData = null;

    if (!file_exists($logFile)) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "File not found";
        exit;
    }

    $logData = file_get_contents($logFile);
    $logData = htmlspecialchars($logData);
    
    echo $twig->render('log.html.twig', [
        'import' => $import,
        'title' => 'Log Datei',
        'dbConnection' => $dbConnection,
        'logData' => $logData
    ]);
});

$router->map('GET', '/files/edit/[i:id]', function ($id) use ($twig) {
    $file = FilesQuery::create()->findPk($id);

    if (!$file) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "File not found";
        exit;
    }
    
    echo $twig->render('file_edit.html.twig', [
        'file' => $file,
        'title' => 'Edit Datei'
    ]);
});

$router->map('POST', '/files/edit/[i:id]', function ($id){
    $file = FilesQuery::create()->findPk($id);

    if (!$file) {
        throw new Exception("Запись с ID $id не найдена!");
    }

    if (isset($_FILES['file']) && $_POST['config']) {
        $controller = new UploadController();
        $controller->replaceFile($file);
    }

    if (isset($_POST['marge'])) {
        $file->setMarge($_POST['marge']);
    }
    if (isset($_POST['prefix'])) {
        $file->setPrefix($_POST['prefix']);
    }
    if (isset($_POST['config'])) {
        $file->setConfigId($_POST['config']);
    }
    if (isset($_POST['status'])) {
        $file->setStatus($_POST['status']);
    }
    if (isset($_POST['import_type'])) {
        $file->setImportType($_POST['import_type']);
    }
    if (isset($_POST['exchange_rate'])) {
        $file->setExchangeRate($_POST['exchange_rate']);
    }
    if (isset($_POST['preorder-status'])) {
        $file->setPreorder($_POST['preorder-status']);
    }
    if (isset($_POST['preorder-deadline'])) {
        $file->setPreorderDeadline($_POST['preorder-deadline']);
    }
    if (isset($_POST['preorder-delivery'])) {
        $file->setPreorderDelivery($_POST['preorder-delivery']);
    }
    if (isset($_POST['preorder-state'])) {
        $file->setPreorderState($_POST['preorder-state']);
    }

    $file->save();

    header("Location: /files/edit/$id");
    exit;
});

$router->map('GET', '/config-files', function () use ($twig) {
    echo $twig->render('config.html.twig', [
        'title' => 'Конфиг Панель'
    ]);
});

$router->map('GET', '/config-files/all', function () use ($twig) {
    $files = ConfigQuery::create()
    ->orderByCreatedAt(Criteria::DESC)
    ->find();

    $fileData = [];
    foreach ($files as $file) {
        $fileData[] = [
            'id' => $file->getId(),
            'name' => $file->getName(),
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($fileData);
});

$router->map('GET', '/config-files/edit/[i:id]', function ($id) use ($twig) {
    $configFile = ConfigQuery::create()->findPk($id);

    if (!$configFile) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "Config not found";
        exit;
    }
    //$fields = FileProcessorDefault::getDefaultFields();

    // $combinedFields = [];
    // foreach ($fields as $field) {
    //     $combinedFields[$field] = $savedMapping[$field] ?? ['default' => '', 'csv' => ''];
    // }

    $savedMapping = json_decode($configFile->getMapping(), true) ?? [];
    // print_r($savedMapping);
    echo $twig->render('config_edit.html.twig', [
        'configFile' => $configFile,
        'title' => 'Edit Konfiguration',
        'fields' => json_encode($savedMapping, JSON_UNESCAPED_UNICODE)
    ]);
});

$router->map('POST', '/config-files/edit/[i:id]', function ($id) {
    $configFile = ConfigQuery::create()->findPk($id);

    if (!$configFile) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "Config not found";
        exit;
    }

    $name = $_POST['name'] ?? null;
    $marge = $_POST['marge'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $fields = $_POST['fields'] ?? [];

    if ($name) {
        $mandatoryFields = FileProcessorDefault::getDefaultFields();

        $mapping = [];
        $properties = [];

        foreach ($mandatoryFields as $mandatoryField) {
            $fieldValue = null;
            foreach ($fields as $field) {
                if ($field['name'] === $mandatoryField) {
                    $fieldValue = [
                        'type' => $field['type'] ?? '',
                        'csv' => $field['type'] === 'csv' ? $field['csvField'] : null,
                        'default' => $field['type'] === 'default' ? $field['default'] : null,
                    ];
                    break;
                }
            }

            $mapping[$mandatoryField] = $fieldValue ?? [
                'type' => 'default',
                'csv' => null,
                'default' => null
            ];
        }

        foreach ($fields as $field) {
            if (!in_array($field['name'], $mandatoryFields)) {
                $properties[$field['name']] = [
                    'type' => $field['type'] ?? '',
                    'csv' => $field['type'] === 'csv' ? $field['csvField'] : null,
                    'default' => $field['type'] === 'default' ? $field['default'] : null,
                ];
            }
        }

        $mapping['properties'] = $properties;

        try {
            $configFile->setName($name);
            $configFile->setMarge($marge);
            $configFile->setPrefix($prefix);
            $configFile->setMapping(json_encode($mapping, JSON_UNESCAPED_UNICODE));
            $configFile->setCreatedAt(new \DateTime());
            $configFile->setUpdatedAt(new \DateTime());
            $configFile->save();

            $_SESSION['message'] = 'Configuration created successfully!';
            header('Location: /config-files');
            exit;
        } catch (Exception $e) {
            // Handle save errors
            $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /config-files/create');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Name is required.';
        header('Location: /config-files/create');
        exit;
    }
});

$router->map('DELETE', '/config-files/delete/[i:id]', function ($id) {
    $config = ConfigQuery::create()->findPk($id);

    if ($config) {
        $config->delete();

        echo json_encode(['status' => 'success', 'message' => 'Konfig erfolgreich gelöscht.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Konfig nicht gefunden.']);
    }

    // Zurück zur Dateiübersicht
    header('Location: /config-files');
    exit;
});

$router->map('GET', '/config-files/create', function () use ($twig) {
    $filePath = $_GET['filePath'] ?? null;
    $fileId = $_GET['fileId'] ?? null;

    echo $twig->render('config_create.html.twig', [
        'title' => 'Neue Konfiguration erstellen',
        'filePath' => $filePath,
        'fileId' => $fileId,
    ]);
});

$router->map('PUT', '/files/update-config/[i:id]', function ($id) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['config_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'config_id fehlt']);
        http_response_code(400);
        exit;
    }

    $configId = $input['config_id'];

    $file = FilesQuery::create()->findPk($id);
    if ($file) {
        // Update the config ID
        $file->setConfigId($configId);
        $file->save();

        echo json_encode(['status' => 'success', 'message' => 'Konfiguration erfolgreich aktualisiert.']);
        http_response_code(200);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datei nicht gefunden.']);
        http_response_code(404);
    }

    exit;
});

$router->map('PUT', '/files/update-status/[i:id]', function ($id) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['status'])) {
        echo json_encode(['status' => 'error', 'message' => 'Status fehlt']);
        http_response_code(400);
        exit;
    }

    $newStatus = $input['status'];

    $file = FilesQuery::create()->findPk($id);
    if ($file) {
        $file->setStatus($newStatus);
        $file->save();

        echo json_encode(['status' => 'success', 'message' => 'Status erfolgreich aktualisiert.', 'newStatus' => $newStatus]);
        http_response_code(200);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datei nicht gefunden.']);
        http_response_code(404);
    }

    exit;
});

$router->map('POST', '/config-files/create', function () {
    $name = $_POST['name'] ?? null;
    $marge = $_POST['marge'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $fields = $_POST['fields'] ?? [];
    $fileId = $_POST['fileId'] ?? [];
    $csvHeaders = json_decode($_POST['csv_headers'] ?? '[]', true);

    if ($name) {
        $mandatoryFields = FileProcessorDefault::getDefaultFields();

        $mapping = [];
        $properties = [];

        foreach ($mandatoryFields as $mandatoryField) {
            $fieldValue = null;
            foreach ($fields as $field) {
                if ($field['name'] === $mandatoryField) {
                    $fieldValue = [
                        'type' => $field['type'] ?? '',
                        'csv' => $field['type'] === 'csv' ? $field['csvField'] : null,
                        'default' => $field['type'] === 'default' ? $field['default'] : null,
                    ];
                    break;
                }
            }

            $mapping[$mandatoryField] = $fieldValue ?? [
                'type' => 'default',
                'csv' => null,
                'default' => null
            ];
        }

        foreach ($fields as $field) {
            if (!in_array($field['name'], $mandatoryFields)) {
                $properties[$field['name']] = [
                    'type' => $field['type'] ?? '',
                    'csv' => $field['type'] === 'csv' ? $field['csvField'] : null,
                    'default' => $field['type'] === 'default' ? $field['default'] : null,
                ];
            }
        }

        $mapping['properties'] = $properties;

        try {
            $configFile = new Config();
            $configFile->setName($name);
            $configFile->setMarge($marge);
            $configFile->setPrefix($prefix);
            $configFile->setMapping(json_encode($mapping, JSON_UNESCAPED_UNICODE));
            $configFile->setCsvHeaders(json_encode($csvHeaders, JSON_UNESCAPED_UNICODE));
            $configFile->setCreatedAt(new \DateTime());
            $configFile->setUpdatedAt(new \DateTime());
            $configFile->save();

            if ($fileId) {
                $file = FilesQuery::create()->findPk($fileId);
                if ($file) {
                    $file->setConfigId($configFile->getId());
                    $file->save();
                    $_SESSION['message'] = 'Configuration created successfully!';
                    header('Location: /');
                    exit;
                } else {
                    $_SESSION['error'] = 'No such file in Database!';
                    header('Location: /');
                    exit;
                }
            } else {
                $_SESSION['message'] = 'Configuration created successfully!';
                header('Location: /config-files');
                exit;
            }
        } catch (Exception $e) {
            // Handle save errors
            $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /config-files/create');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Name is required.';
        header('Location: /config-files/create');
        exit;
    }
});

$router->map('GET', '/config-files/fields', function () {
    // Felder aus der Klasse abrufen
    $fields = FileProcessorDefault::getDefaultFields();
    array_push($fields, ...FileProcessorDefault::getDefaultPropertyFields());

    // JSON-Antwort zurückgeben
    header('Content-Type: application/json');
    echo json_encode($fields);
});

$router->map('GET', '/config-files/fields/[i:id]', function ($id) {
    // Config abrufen
    $configFile = ConfigQuery::create()->findPk($id);

    $jsonString = $configFile->getMapping();
    $cleanedJsonString = str_replace(['\r\n', '\r', '\n'], "", $jsonString);
    $fields = json_decode($cleanedJsonString, true) ?? [];

    // JSON-Antwort zurückgeben
    header('Content-Type: application/json');
    echo json_encode($fields);
});

$router->map('GET', '/config-files/csv-headers/[i:id]', function ($id) {
    // Config abrufen
    $configFile = ConfigQuery::create()->findPk($id);
    $jsonString = $configFile->getCsvHeaders();
    $cleanedJsonString = str_replace(['\r\n', '\r', '\n'], "", $jsonString);
    $csvHeaders = json_decode($cleanedJsonString, true) ?? [];

    // JSON-Antwort zurückgeben
    header('Content-Type: application/json');
    echo json_encode($csvHeaders);
});

$router->map('POST', '/upload', function () {
    $controller = new UploadController();
    $result = $controller->handleUpload();

    header('Content-Type: application/json');
    echo json_encode($result);
});

$router->map('GET', '/rm-lock-file', function () {
    $lockFile = __DIR__ . '/import.lock';

    if (file_exists($lockFile)) {
        unlink($lockFile);
    }

    // Zurück zur Dateiübersicht
    header('Location: /');
    exit;
});


$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // Если маршрут не найден, вернуть ошибку 404
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "404 - Page not found";
}
