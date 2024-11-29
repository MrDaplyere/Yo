<?php
interface ReportStrategy {
    public function generateReport(array $data): string;
}
?>
