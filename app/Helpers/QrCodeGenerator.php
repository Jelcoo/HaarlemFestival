<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;

class QrCodeGenerator
{
    private const START_STRING = 'QWFST';
    private const ENCRYPTION_KEY = 'SecretString';

    public static function generateQRCode($id, $event)
    {
        $encryptedData = self::createQrString($id, $event);
        $qrcode = new QrCode($encryptedData);
        $writer = new PngWriter();
        $result = $writer->write($qrcode);

        return $result->getDataUri();
    }

    public static function createQrString($id, $event)
    {
        $combinedData = "{$id}.{$event}";
        $encryptedData = self::encrypt($combinedData);
        return self::START_STRING . $encryptedData;
    }

    public static function readQrString($qrString): array
    {
        if (strpos($qrString, self::START_STRING) !== 0) {
            throw new Exception("Invalid QR code format.");
        }

        $encryptedData = substr($qrString, strlen(self::START_STRING));
        $decryptedData = self::decrypt($encryptedData);

        if ($decryptedData === false) {
            throw new Exception("Decryption failed.");
        }

        [$id, $event] = explode('.', $decryptedData); 

        return [
            'id' => (int)$id,
            'event' => $event
        ];
    }

    private static function encrypt($data)
    {
        $encrypted = openssl_encrypt($data, 'AES-128-ECB', self::ENCRYPTION_KEY);    
        return base64_encode($encrypted);
    }

    private static function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data), 'AES-128-ECB', self::ENCRYPTION_KEY);
    }
}
