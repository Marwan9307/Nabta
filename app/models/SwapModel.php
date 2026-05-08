<?php

require_once __DIR__ . '/../config/database.php';

class SwapModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('swaps');
    }

    public function create($initiatorId, $receiverId, $offeredItemId, $requestedItemId, $balanceAmount = 0, $settlementType = 'none') {
        $expires = date('Y-m-d H:i:s', strtotime('+72 hours'));
        $stmt = $this->db->prepare("INSERT INTO swaps (initiator_id, receiver_id, offered_item_id, requested_item_id, balance_amount, settlement_type, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$initiatorId, $receiverId, $offeredItemId, $requestedItemId, $balanceAmount, $settlementType, $expires]);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM swaps WHERE swap_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE swaps SET swap_status = ? WHERE swap_id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM swaps WHERE initiator_id = ? OR receiver_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }

    public function getPending($userId) {
        $stmt = $this->db->prepare("SELECT * FROM swaps WHERE receiver_id = ? AND swap_status = 'pending' ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function cancelExpired() {
        $stmt = $this->db->prepare("UPDATE swaps SET swap_status = 'cancelled' WHERE swap_status = 'pending' AND expires_at < datetime('now')");
        return $stmt->execute();
    }

    public function calculateValueDifference($offeredPrice, $requestedPrice) {
        $diff = abs($offeredPrice - $requestedPrice);
        $avg = ($offeredPrice + $requestedPrice) / 2;
        $percent = ($avg > 0) ? ($diff / $avg) * 100 : 0;
        return ['difference' => $diff, 'percent' => $percent, 'needs_balance' => $percent > 20];
    }
}
