<?php
include_once 'ReportStrategy.php';

class JSONReportStrategy implements ReportStrategy {
    public function generateReport(array $data): string {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>
