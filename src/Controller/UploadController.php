<?php

namespace App\Controller;

use App\Csv\FileUploader;
use App\Utils\Logger;
use Kint;

class UploadController
{
    public function handleUpload($configId = null): array
    {
        try {
            $uploadDir = __DIR__ . '/../../csv';
            $logger = new Logger();
            $fileUploader = new FileUploader(uploadDir: $uploadDir, logger: $logger);

            if (!$configId && isset($_POST['config_id'])) {
                $configId = $_POST['config_id'];
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $configId) {
                return $fileUploader->upload($_FILES['file'], $configId);
            } else {
                return ['status' => 'error', 'error' => 'ERROR', 'message' => 'Неверный запрос'];
            }
        } catch (\Throwable $th) {#
            return ['status' => 'error', 'error' => $th->getMessage(), 'message' => 'Неверный запрос'];
        }
    }

    public function replaceFile($fileRecord)
    {
        try {
            $uploadDir = __DIR__ . '/../../csv';
            $logger = new Logger();
            $fileUploader = new FileUploader(uploadDir: $uploadDir, logger: $logger);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
                return $fileUploader->replaceFile($fileRecord, $_FILES['file']);
            } else {
                return ['status' => 'error', 'error' => 'ERROR', 'message' => 'Неверный запрос'];
            }
        } catch (\Throwable $th) {#
            return ['status' => 'error', 'error' => $th->getMessage(), 'message' => 'Неверный запрос'];
        }
    }
}