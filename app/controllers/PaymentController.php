<?php

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/PaymentGateway.php';

class PaymentController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function secureEscrow($transactionId) {
        $paymentGateway = PaymentGateway::getInstance();
        $paymentGateway->executeCharge(200); // Example logic
        echo "Funds secured in escrow for transaction " . htmlspecialchars($transactionId);
    }

    public function manageRollback($transactionId) {
        $paymentGateway = PaymentGateway::getInstance();
        $paymentGateway->executeRefund($transactionId);
        echo "Transaction " . htmlspecialchars($transactionId) . " rolled back successfully.";
    }
}
