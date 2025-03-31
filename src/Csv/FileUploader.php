<?php

namespace App\Csv;

use Propel\Files;
use App\Utils\Logger;

class FileUploader
{
    public function __construct(
        private $uploadDir,
        private Logger $logger,
        private $maxFileSize = 25 * 1024 * 1024,
        private $allowedTypes = ['application/vnd.ms-excel', 'text/csv', 'text/plain']
    ) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        // Создание директории, если она не существует
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function replaceFile($fileRecord, $file)
    {
        if (!$this->isValidFile($file)) {
            return false;
        }

        $originalName = basename($file['name']);
        $uniqueName = $this->generateUniqueName($originalName);
        $subDir = $this->generateSubDir($uniqueName);

        $targetDir = $this->uploadDir . $subDir;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . '/' . $uniqueName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $fileRecord->setFilename($originalName);
            $fileRecord->setPath($subDir . '/' . $uniqueName);
            $fileRecord->setStatus('idle');
            $fileRecord->setUpdatedAt(new \DateTime());
            $fileRecord->save();

            return true;
        } else {
            return false;
        }
    }

    public function upload($file, $configId)
    {
        if (!$this->isValidFile($file)) {
            return ['status' => 'error', 'message' => 'Неверный файл 2'];
        }

        $originalName = basename($file['name']);
        $uniqueName = $this->generateUniqueName($originalName);
        $subDir = $this->generateSubDir($uniqueName);

        $targetDir = $this->uploadDir . $subDir;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . '/' . $uniqueName;

        $this->logger->info("Dieses ist eine Info-Nachricht.");
        $this->logger->info($originalName);
        $this->logger->info($subDir . '/' . $uniqueName);
        $this->logger->info('idle');
        $this->logger->info('1');

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $fileRecord = new Files();
            $fileRecord->setFilename($originalName);
            $fileRecord->setPath($subDir . '/' . $uniqueName);
            $fileRecord->setStatus('idle');
            $fileRecord->setConfigId($configId);
            $fileRecord->setCreatedAt(new \DateTime());
            $fileRecord->setUpdatedAt(new \DateTime());
            $this->logger->info($fileRecord->__toString());
            $fileRecord->save();

            return [
                'success' => true,
                'status' => 'success',
                'file' => $originalName,
                'path' => $fileRecord->getPath(),
                'file_id' => $fileRecord->getId()
            ];
        } else {
            return ['status' => 'error', 'message' => 'Ошибка загрузки файла'];
        }
    }

    private function isValidFile($file)
    {
        // Проверка на наличие ошибок
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Проверка размера файла
        if ($file['size'] > $this->maxFileSize) {
            return false;
        }

        // Проверка типа файла
        if (!in_array(mime_content_type($file['tmp_name']), $this->allowedTypes)) {
            return false;
        }

        return true;
    }

    private function generateUniqueName($originalName)
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        // Eindeutigen Namen generieren
        return uniqid($baseName . '_') . '.' . $extension;
    }

    private function generateSubDir($uniqueName)
    {
        // Verzeichnisstruktur aus den ersten zwei Buchstaben des Dateinamens erstellen
        return strtolower(substr($uniqueName, 0, 1)) . '/' . strtolower(substr($uniqueName, 1, 1));
    }
}
