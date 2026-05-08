<?php

require_once __DIR__ . '/../config/database.php';

class AnalyticsModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('analytics');
    }

    public function logCarbonSaving($userId, $itemId, $co2, $water, $type) {
        $stmt = $this->db->prepare("INSERT INTO carbon_savings (user_id, item_id, co2_saved, water_saved, transaction_type) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $itemId, $co2, $water, $type]);
    }

    public function getTotalSavings() {
        return $this->db->query("SELECT COALESCE(SUM(co2_saved),0) as total_co2, COALESCE(SUM(water_saved),0) as total_water FROM carbon_savings")->fetch();
    }

    public function getUserSavings($userId) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(co2_saved),0) as total_co2, COALESCE(SUM(water_saved),0) as total_water FROM carbon_savings WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function calculateCarbonSaving($materialType, $weight) {
        $scores = [
            'cotton' => 10, 'polyester' => 15, 'denim' => 20,
            'wool' => 12, 'silk' => 8, 'linen' => 6, 'leather' => 25
        ];
        $score = $scores[strtolower($materialType)] ?? 10;
        $co2 = $weight * $score;
        $water = $weight * $score * 100;
        return ['co2_saved' => round($co2, 2), 'water_saved' => round($water, 2)];
    }

    public function createReport($staffId, $type, $content) {
        $stmt = $this->db->prepare("INSERT INTO analytics_reports (generated_by_id, report_type, content) VALUES (?, ?, ?)");
        $stmt->execute([$staffId, $type, $content]);
        return $this->db->lastInsertId();
    }

    public function getReports($type = null) {
        if ($type) {
            $stmt = $this->db->prepare("SELECT * FROM analytics_reports WHERE report_type = ? ORDER BY created_at DESC");
            $stmt->execute([$type]);
            return $stmt->fetchAll();
        }
        return $this->db->query("SELECT * FROM analytics_reports ORDER BY created_at DESC")->fetchAll();
    }
}
