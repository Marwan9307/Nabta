<?php

require_once __DIR__ . '/../config/database.php';

class ReportModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('reports');
    }

    public function create($reporterId, $targetId, $type, $reason) {
        $stmt = $this->db->prepare("INSERT INTO reports (reporter_id, target_id, report_type, reason) VALUES (?, ?, ?, ?)");
        $stmt->execute([$reporterId, $targetId, $type, $reason]);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE report_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function resolve($reportId, $solverId, $status = 'resolved') {
        $stmt = $this->db->prepare("UPDATE reports SET report_status = ?, solver_id = ? WHERE report_id = ?");
        return $stmt->execute([$status, $solverId, $reportId]);
    }

    public function getPending() {
        return $this->db->query("SELECT * FROM reports WHERE report_status = 'pending' ORDER BY created_at DESC")->fetchAll();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM reports ORDER BY created_at DESC")->fetchAll();
    }

    public function getByType($type) {
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE report_type = ? ORDER BY created_at DESC");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }
}
