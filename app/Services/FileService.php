<?php

namespace App\Services;

use App\Config\Config;

class FileService
{
    public function saveBase64File($base64String, $outputFile)
    {
        $base64String = preg_replace('/^data:(.*?);base64,/', '', $base64String);

        $decodedData = base64_decode($base64String);
        if ($decodedData == false) {
            return false;
        }

        if (file_put_contents($outputFile, $decodedData) !== false) {
            return $outputFile;
        }

        return false;
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
