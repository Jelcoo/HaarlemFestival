<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeGenerator
{
    public static function generateQRCode($string)
    {
        $qrcode = new QrCode($string);
        $writer = new PngWriter();
        $result = $writer->write($qrcode);

        $dataUri = $result->getDataUri();

        return $dataUri;
    }
}
