<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
    public static function generatePdfFromView(string $view, array $data = [], string $filename = 'file.pdf'): void
    {
        extract($data);

        ob_start();
        include __DIR__ . "/../../resources/views/{$view}.php"; // ✅ Correct path
        $html = ob_get_clean();

        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream($filename);
    }
}
