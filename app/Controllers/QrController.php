<?php

namespace App\Controllers;

use App\Helpers\Qrcodegenerator;

class QrController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        $datauri = Qrcodegenerator::generateQRCode();
        return $this->pageLoader->setPage('qrcode')->render(['dataUri' => $datauri]);
    }
}
