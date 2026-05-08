<?php

require_once __DIR__ . '/../config/database.php';

class ShippingModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('shipping');
    }

    public function create($orderId, $fees = 0, $courier = '') {
        $tracking = 'NBT' . strtoupper(bin2hex(random_bytes(6)));
        $label = 'LBL-' . $orderId . '-' . date('Ymd');
        $estimated = date('Y-m-d', strtotime('+5 days'));

        $stmt = $this->db->prepare("INSERT INTO shipping (order_id, tracking_no, shipping_label, courier_name, estimated_delivery, shipping_fees) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$orderId, $tracking, $label, $courier, $estimated, $fees]);
        return $this->db->lastInsertId();
    }

    public function findByOrder($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM shipping WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function updateStatus($shippingId, $status) {
        $stmt = $this->db->prepare("UPDATE shipping SET shipping_status = ? WHERE shipping_id = ?");
        return $stmt->execute([$status, $shippingId]);
    }

    public function track($trackingNo) {
        $stmt = $this->db->prepare("SELECT * FROM shipping WHERE tracking_no = ?");
        $stmt->execute([$trackingNo]);
        return $stmt->fetch();
    }
}
