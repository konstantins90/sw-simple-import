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
use Propel\Runtime\ActiveQuery\Criteria;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);
error_reporting(0);
$router = new \AltoRouter();

// try {
//     $connection = Propel::getServiceContainer()->getConnection();
//     $result = $connection->query('SELECT * FROM config');

//     var_dump($result->fetchAll());
//     echo "Соединение c базой данных успешно.";
// } catch (\Exception $e) {
//     echo "Ошибка соединения c базой данных: " . $e->getMessage();
// }

$router->map('GET', '/', function () use ($twig) {
    echo $twig->render('index.html.twig', [
        'title' => 'Админ Панель',
    ]);
});

$router->map('GET', '/files', function () use ($twig) {
    $files = FilesQuery::create()
        ->orderByCreatedAt(Criteria::DESC)
        ->limit(5)
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
        $filePath = __DIR__ . '/../../uploads/' . $file->getPath(); // Datei-Pfad basierend auf der `path`-Spalte

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

$router->map('GET', '/config-files', function () use ($twig) {
    echo $twig->render('config.html.twig', [
        'title' => 'Конфиг Панель'
    ]);
});

$router->map('GET', '/config-files/all', function () use ($twig) {
    $files = ConfigQuery::create()->find();

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
    //print_r($savedMapping);
    echo $twig->render('config_edit.html.twig', [
        'configFile' => $configFile,
        'title' => 'Edit Konfiguration',
        'fields' => json_encode($savedMapping, JSON_UNESCAPED_UNICODE)
    ]);
});

$router->map('POST', '/config-files/edit/[i:id]', function ($id) {
    // Konfiguration abrufen
    $configFile = ConfigQuery::create()->findPk($id);

    if (!$configFile) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "Config not found";
        exit;
    }

    // Daten aus dem POST-Request
    $name = $_POST['name'] ?? null;
    $marge = $_POST['marge'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $fields = $_POST['fields'] ?? [];

    if ($name) {
        //print_r($name);
        //print_r($fields);
        $mapping = [];
        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? '';
            $fieldCsv = $field['csvField'] ?? '';
            $fieldDefault = $field['default'] ?? '';

            if (!empty($fieldName)) {
                $mapping[$fieldName] = [
                    'type' => $fieldType,
                    'csv' => $fieldType === 'csv' ? $fieldCsv : null,
                    'default' => $fieldType === 'default' ? $fieldDefault : null,
                ];
            }
        }

        try {
            $configFile->setName($name);
            $configFile->setMarge($marge);
            $configFile->setPrefix($prefix);
            $configFile->setMapping(json_encode($mapping, JSON_UNESCAPED_UNICODE));
            $configFile->setCreatedAt(new \DateTime());
            $configFile->setUpdatedAt(new \DateTime());
            $configFile->save();

            $_SESSION['message'] = 'Configuration updated successfully!';
            header('Location: /config-files');
            exit;
        } catch (Exception $e) {
            // Handle save errors
            $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
            header('Location: /config-files/edit/{$id}');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Please provide valid data.';
        header("Location: /config-files/edit/{$id}");
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
    // Formular anzeigen
    echo $twig->render('config_create.html.twig', [
        'title' => 'Neue Konfiguration erstellen'
    ]);
});

$router->map('POST', '/config-files/create', function () {
    $name = $_POST['name'] ?? null;
    $marge = $_POST['marge'] ?? null;
    $prefix = $_POST['prefix'] ?? null;
    $fields = $_POST['fields'] ?? [];


    if ($name) {
        $mapping = [];
        foreach ($fields as $field) {
            $fieldName = $field['name'] ?? '';
            $fieldType = $field['type'] ?? '';
            $fieldCsv = $field['csvField'] ?? '';
            $fieldDefault = $field['default'] ?? '';

            if (!empty($fieldName)) {
                $mapping[$fieldName] = [
                    'type' => $fieldType,
                    'csv' => $fieldType === 'csv' ? $fieldCsv : null,
                    'default' => $fieldType === 'default' ? $fieldDefault : null,
                ];
            }
        }

        try {
            $configFile = new Config();
            $configFile->setName($name);
            if ($marge) {
                $configFile->setmarge($marge);
            }
            if ($prefix) {
                $configFile->setPrefix($prefix);
            }
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

$router->map('GET', '/config-files/fields/[i:id]', function ($id) {
    // Felder aus der Klasse abrufen
    $configFile = ConfigQuery::create()->findPk($id);

    $fields = json_decode($configFile->getMapping(), true) ?? [];

    // JSON-Antwort zurückgeben
    header('Content-Type: application/json');
    echo json_encode($fields);
});

$router->map('POST', '/upload', function () {
    $controller = new UploadController();
    $controller->handleUpload();
});


$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // Если маршрут не найден, вернуть ошибку 404
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "404 - Page not found";
}
