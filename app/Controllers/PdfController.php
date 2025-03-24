<?php

namespace App\Controllers;

use App\Helpers\PdfHelper;

class PdfController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generateExample(): void
    {
        $data = [
            'title' => 'Sample PDF Document',
            'content' => 'This PDF was generated using dompdf with your existing controller structure.'
        ];

        PdfHelper::generatePdfFromView('pdf', $data, 'example.pdf');
    }
}
