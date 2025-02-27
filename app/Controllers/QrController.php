<?php

namespace App\Controllers;

use App\Helpers\QrCodeGenerator;

class QrController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        $datauri = QrCodeGenerator::generateQRCode('test');
        return $this->pageLoader->setPage('qrcode')->render(['dataUri' => $datauri]);
    }
}
