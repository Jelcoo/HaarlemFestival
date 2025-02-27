<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
class Qrcodegenerator {
    public static function generateQRCode() {
        $qrcode = new QrCode('Testing');
        $writer = new PngWriter();
        $result = $writer->write($qrcode);
        $dataUri = $result->getDataUri();

        return $dataUri;
    }
}

