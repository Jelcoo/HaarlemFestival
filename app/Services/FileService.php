<?php

namespace App\Services;

use App\Config\Config;

class FileService
{
    public function saveFile($inputFile, $outputFile)
    {
        return move_uploaded_file($inputFile['tmp_name'], $outputFile);
    }

    public function deleteFile($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public static function getFilePath(string $filename)
    {
        return rtrim(Config::getKey('STORAGE_PATH'), '/') . '/' . $filename;
    }

    public static function getExtension(string $mimeType)
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
        ];

        return $extensions[$mimeType] ?? 'bin';
    }
}
