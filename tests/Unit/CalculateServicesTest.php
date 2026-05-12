<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/models/CalculateServices.php';

class CalculateServicesTest extends TestCase {
    private CalculateServices $service;

    protected function setUp(): void {
        $this->service = new CalculateServices();
    }

    public function testCalculateTrustScoreRange() {
        $score = $this->service->calculateTrustScore(1);
        $this->assertIsFloat($score);
        $this->assertGreaterThanOrEqual(1.0, $score);
        $this->assertLessThanOrEqual(5.0, $score);
    }

    public function testCalculateEcoPointsRange() {
        $points = $this->service->calculateEcoPoints(42);
        $this->assertGreaterThanOrEqual(100, $points);
        $this->assertLessThanOrEqual(1000, $points);
    }

    public function testCalculateCO2EquationValue() {
        $this->assertSame(12.5, $this->service->calculateCO2Equation(123));
    }

    public function testCalculateBundleDiscountValue() {
        $this->assertSame(10, $this->service->calculateBundleDiscount(123));
    }

    public function testCalculateTotalReturnsInt() {
        $this->assertSame(150, $this->service->calculateTotal(123));
    }

    public function testCalculateTotalPriceReturnsInt() {
        $this->assertSame(150, $this->service->calculateTotalPrice(123));
    }
}
