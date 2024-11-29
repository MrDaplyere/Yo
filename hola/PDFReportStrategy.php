<?php
include_once 'ReportStrategy.php';
require_once 'fpdf/fpdf.php';

class PdfReportStrategy implements ReportStrategy {
    public function generateReport(array $data): string {
        ob_clean();
        flush();

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
        
        return $pdf->Output('S'); 
    }
}
?>
