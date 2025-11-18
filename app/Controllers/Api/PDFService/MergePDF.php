<?php

namespace App\Controllers\Api\PDFService;

use CodeIgniter\RESTful\ResourceController;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class MergePDF extends ResourceController
{
    protected $format = 'json';

    public function __construct()
    {
        require_once APPPATH . 'Libraries/FPDF186/fpdf.php';
        require_once APPPATH . 'Libraries/FPDI/src/autoload.php';
    }

    public function mergepdf()
    {
        $logSteps = [];
        $skippedFiles = [];

        try {
            // Validar API Key desde .env
            $apiKeyHeader = $this->request->getHeaderLine('x-api-key');
            $expectedKey = getenv('API_KEY');

            if (!$apiKeyHeader || $apiKeyHeader !== $expectedKey) {
                return $this->respond([
                    'status' => 'ERROR',
                    'message' => 'API Key inválida o no enviada'
                ], 401);
            }

            // Recibir datos JSON con PDFs en base64
            $data = $this->request->getJSON(true);
            if (!isset($data['files']) || !is_array($data['files']) || count($data['files']) === 0) {
                return $this->fail([
                    'status'  => 'ERROR',
                    'message' => 'Se requiere un array "files" con PDFs en base64'
                ]);
            }

            // Restricción: máximo 40 archivos
            if (count($data['files']) > 210) {
                return $this->fail([
                    'status'  => 'ERROR',
                    'message' => 'Máximo 200 archivos permitidos por lote'
                ]);
            }

            $logSteps[] = "Se recibieron " . count($data['files']) . " archivos PDF.";

            $pdf = new Fpdi();
            // $pdf->SetCompression(true); 
            $tempFiles = [];

            // Crear carpeta temporal si no existe
            $tempDir = WRITEPATH . 'uploads/pdftemp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Procesar PDFs, omitiendo los mayores a 2 MB
            foreach ($data['files'] as $index => $fileBase64) {
                $pdfContent = base64_decode($fileBase64);
                $fileSize = strlen($pdfContent);

                if ($fileSize > 2 * 1024 * 1024) {
                    $skippedFiles[] = $index;
                    continue;
                }

                $tempFile = $tempDir . 'apipdf_' . uniqid() . '.pdf';
                file_put_contents($tempFile, $pdfContent);
                $tempFiles[] = $tempFile;

                $pageCount = $pdf->setSourceFile($tempFile);
                $logSteps[] = "Archivo {$index} tiene {$pageCount} páginas.";

                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $tplIdx = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($tplIdx);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tplIdx);
                }
            }

            if (empty($tempFiles)) {
                return $this->fail([
                    'status'  => 'ERROR',
                    'message' => 'No hay archivos PDF válidos menores a 2 MB para procesar.'
                ]);
            }

            // Generar PDF final en disco
            $mergedFile = $tempDir . 'apimerged_' . uniqid() . '.pdf';
            $pdf->Output($mergedFile, 'F');

            // Leer y codificar en base64
            $mergedPdfContent = file_get_contents($mergedFile);
            $mergedBase64 = base64_encode($mergedPdfContent);
            $logSteps[] = "PDF combinado codificado en Base64.";

            // Borrar archivos temporales
            foreach ($tempFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            if (file_exists($mergedFile)) {
                unlink($mergedFile);
            }

            return $this->respond([
                'status'      => 'OK',
                'message'     => 'PDFs unidos correctamente',
                'file_base64' => $mergedBase64,
                'skipped'     => $skippedFiles,
                'logs'        => $logSteps
            ]);

        } catch (\Exception $e) {
            return $this->fail([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'logs'    => $logSteps
            ]);
        }
    }
}
