<?php
include_once 'ReportStrategy.php';

class HTMLReportStrategy implements ReportStrategy {
    public function generateReport(array $data): string {
        $html = "<table border='1'><tr>";
        foreach (array_keys($data[0]) as $header) {
            $html .= "<th>{$header}</th>";
        }
        $html .= "</tr>";
        foreach ($data as $row) {
            $html .= "<tr>";
            foreach ($row as $cell) {
                $html .= "<td>{$cell}</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }
}
?>
