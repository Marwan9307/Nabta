<?php

class PaymentGateway {
    // 1. Private static instance
    private static $theGateway = null;

    // 2. Private constructor prevents instantiation from other classes
    private function __construct() {
        // One-time initialization: loading payment processor credentials
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {}

    // 3. Public static method to get the instance
    public static function getInstance() {
        if (self::$theGateway === null) {
            self::$theGateway = new self();
        }
        return self::$theGateway;
    }

    // Pattern Methods
    public function executeCharge($amount) {
        return "Charge executed for amount: $amount";
    }

    public function executeTransfer($recipient, $amount) {
        return "Transfer executed to $recipient for $amount";
    }

    public function executeRefund($transactionId) {
        return "Refund executed for transaction: $transactionId";
    }

    public function getPaymentStatus() {
        return "PENDING"; // SUCCESS | FAILED
    }

    public function notifyCustomer() {
        return "Customer notified of payment status.";
    }
}
