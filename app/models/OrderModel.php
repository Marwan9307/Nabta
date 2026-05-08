<?php

require_once __DIR__ . '/../config/database.php';

class OrderModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('orders');
    }

    public function create($buyerId, $sellerId, $items, $platformFee = 0, $bundleDiscount = 0) {
        $subtotal = array_sum(array_column($items, 'price'));
        $total = $subtotal + $platformFee - $bundleDiscount;

        $this->db->beginTransaction();

        $stmt = $this->db->prepare("INSERT INTO orders (buyer_id, seller_id, items_subtotal, platform_fee, bundle_discount, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$buyerId, $sellerId, $subtotal, $platformFee, $bundleDiscount, $total]);
        $orderId = $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, item_id, price) VALUES (?, ?, ?)");
        foreach ($items as $item) {
            $stmt->execute([$orderId, $item['item_id'], $item['price']]);
        }

        $this->db->commit();
        return $orderId;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function updateStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
        return $stmt->execute([$status, $orderId]);
    }

    public function getByBuyer($buyerId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$buyerId]);
        return $stmt->fetchAll();
    }

    public function getBySeller($sellerId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE seller_id = ? ORDER BY created_at DESC");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll();
    }

    public function createTransaction($orderId, $paymentMethod) {
        $stmt = $this->db->prepare("INSERT INTO transactions (order_id, payment_method) VALUES (?, ?)");
        $stmt->execute([$orderId, $paymentMethod]);
        return $this->db->lastInsertId();
    }

    public function updateTransactionStatus($transactionId, $status) {
        $stmt = $this->db->prepare("UPDATE transactions SET transaction_status = ? WHERE transaction_id = ?");
        return $stmt->execute([$status, $transactionId]);
    }

    public function getTransaction($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch();
    }

    public function createEscrow($transactionId, $amount) {
        $stmt = $this->db->prepare("INSERT INTO escrow (transaction_id, amount) VALUES (?, ?)");
        $stmt->execute([$transactionId, $amount]);
        return $this->db->lastInsertId();
    }

    public function releaseEscrow($escrowId) {
        $stmt = $this->db->prepare("UPDATE escrow SET escrow_status = 'released' WHERE escrow_id = ?");
        return $stmt->execute([$escrowId]);
    }

    public function refundEscrow($escrowId) {
        $stmt = $this->db->prepare("UPDATE escrow SET escrow_status = 'refunded' WHERE escrow_id = ?");
        return $stmt->execute([$escrowId]);
    }

    public function getEscrow($transactionId) {
        $stmt = $this->db->prepare("SELECT * FROM escrow WHERE transaction_id = ?");
        $stmt->execute([$transactionId]);
        return $stmt->fetch();
    }

    public function getAllOrders() {
        return $this->db->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
    }
}
