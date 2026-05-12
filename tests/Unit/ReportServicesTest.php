<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/ReportServices.php';

class ReportServicesTest extends TestCase {

    private $service;

    protected function setUp(): void {
        $this->service = new ReportServices();
    }

    public function testSubmitReport() {
        $result = $this->service->submitReport('item');
        $this->assertTrue($result);
    }

    public function testGetReportType() {
        $result = $this->service->getReportType('spam');
        $this->assertEquals("Type: spam", $result);
    }

    public function testGetReportDetails() {
        $result = $this->service->getReportDetails('user');
        $this->assertEquals("Report details fetched.", $result);
    }

    public function testGetAnalyticalReports() {
        $result = $this->service->getAnalyticalReports('monthly');
        $this->assertEquals("Analytical data returned.", $result);
    }
}
?>