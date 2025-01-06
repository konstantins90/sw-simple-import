<?php

namespace App\Controller;

use App\Csv\FileUploader;

class UploadController
{
    public function handleUpload()
    {
        $uploadDir = __DIR__ . '/../../csv'; // Директория для загрузки файлов
        $fileUploader = new FileUploader($uploadDir);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $result = $fileUploader->upload($_FILES['file']);
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . ' 400 Bad Request');
            echo json_encode(['status' => 'error', 'message' => 'Неверный запрос']);
        }
    }
}