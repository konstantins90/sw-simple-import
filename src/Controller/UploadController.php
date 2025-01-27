<?php

namespace App\Controller;

use App\Csv\FileUploader;
use App\Utils\Logger;
use Kint;

class UploadController
{
    public function handleUpload(): array
    {
        try {
            $uploadDir = __DIR__ . '/../../csv';
            $logger = new Logger();
            $fileUploader = new FileUploader(uploadDir: $uploadDir, logger: $logger);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['config_id'])) {
                return $fileUploader->upload($_FILES['file'], $_POST['config_id']);
            } else {
                return ['status' => 'error', 'error' => 'ERROR', 'message' => 'Неверный запрос'];
            }
        } catch (\Throwable $th) {
            return ['status' => 'error', 'error' => $th->getMessage(), 'message' => 'Неверный запрос'];
        }

        
    }
}