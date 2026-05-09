<?php

class ReportServices {
    private $reportsList = [];

    public function getReportDetails($report_Type) {
        return "Report details fetched.";
    }

    public function submitReport($report_Type) {
        $this->reportsList[] = "New report submitted.";
        return true;
    }

    public function getAnalyticalReports($report_Type) {
        return "Analytical data returned.";
    }

    public function getReportType($report_Type_Enum) {
        return "Type: " . $report_Type_Enum;
    }
}
