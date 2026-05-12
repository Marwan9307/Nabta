<?php

class PaymentGateway {
    private static $theGateway = null;


    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance() {
        if (self::$theGateway === null) {
            self::$theGateway = new self();
        }
        return self::$theGateway;
    }

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
        return "PENDING"; 
    }

    public function notifyCustomer() {
        return "Customer notified of payment status.";
    }
}
