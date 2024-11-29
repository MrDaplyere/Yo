<?php
include_once 'ReportStrategy.php';
require_once 'fpdf/fpdf.php';

class PdfReportStrategy implements ReportStrategy {
    public function generateReport(array $data): string {
        // Elimina el uso de ob_clean y flush si no estás usando buffering de salida
        // No es necesario limpiar el buffer si no estás iniciando explícitamente uno

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        
        $pdf->Cell(40, 10, 'Cliente', 1);
        $pdf->Cell(40, 10, 'Producto', 1);
        $pdf->Cell(40, 10, 'Fecha', 1);
        $pdf->Ln();
        
        /* -------------TABLA-------------- */
        if (!empty($data)) {
            foreach ($data as $row) {
                $pdf->Cell(40, 10, htmlspecialchars($row['cliente']), 1);
                $pdf->Cell(40, 10, htmlspecialchars($row['producto']), 1);
                $pdf->Cell(40, 10, htmlspecialchars($row['fecha']), 1);
                $pdf->Ln();
            }
        } else {
            $pdf->Cell(120, 10, ' ', 1, 1, 'C');
        }
        
        // Si necesitas que el PDF se descargue directamente, usa 'I' o 'D'
        return $pdf->Output('S'); // Devuelve el PDF como una cadena
        // Si prefieres que el PDF se muestre en el navegador, usa 'I'
        // return $pdf->Output('I'); // Mostrar PDF directamente
        // Si prefieres que el PDF se descargue, usa 'D'
        // return $pdf->Output('D'); // Descargar PDF
    }
}
?>
