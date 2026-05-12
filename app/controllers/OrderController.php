<?php

require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/ShippingModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function negotiatePrice($itemId, $proposedPrice) {
        // Use Case: Negotiate Price (<extend> Do an Operation)
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "Negotiation started for item {$itemId} with price {$proposedPrice}";
    }

    public function offerMultiItem() {
        // Use Case: Offer Multi-Item (<extend> Buy Item / Sell Item)
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "Multi-item offer submitted.";
    }

    public function calculateCO2AndWater() {
        // Use Case: Calculate CO2 and Water Equation (<include> Do an Operation)
        echo "CO2 and Water savings calculated.";
    }

    public function calculateEcoCredits() {
        // Use Case: Calculate Eco-credits (<include> Do an Operation)
        echo "Eco-credits calculated and awarded.";
    }

    // --- SSD Methods: Buy Item & Sell Item ---
    public function selectItemToBuy($itemID) {
        // Maps to SSD: Initiation of Purchase
        echo "Item selected for purchase.";
    }

    public function submitPurchaseRequest($offeredPrice) {
        // Maps to SSD: submitPurchaseRequest
        echo "Purchase request submitted.";
    }

    public function autoCancel() {
        // Maps to SSD: No Response from Seller > 48h
        echo "Order auto-cancelled.";
    }

    public function viewPendingOffers($itemID) {
        // Maps to SSD: viewPendingOffers
        return [];
    }

    public function acceptOffer($requestID) {
        // Maps to SSD: acceptOffer
        $this->createOrder();
        $this->lockItem();
    }

    public function createOrder() {
        echo "Order Created.";
    }

    public function lockItem() {
        // System automatically rejects other pending bids
        echo "Item locked and other bids rejected.";
    }

    public function rejectOffer($requestID) {
        // Maps to SSD: rejectOffer
        echo "Offer rejected.";
    }

    public function index() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $orders = $this->orderModel->getByBuyer(Session::userId());
        $sold = $this->orderModel->getBySeller(Session::userId());
        $all = array_merge($orders, $sold);
        usort($all, function($a, $b) { return strtotime($b['created_at']) - strtotime($a['created_at']); });

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Orders',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'orders' => $all,
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/order/index.php';
    }

    public function show($id) {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $order = $this->orderModel->findById($id);
        if (!$order) { header('Location: /order'); exit; }

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Order Details',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'order_id' => $order['order_id'],
            'status' => $order['order_status'],
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/order/show.php';
    }

    public function buy() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::flash('error', 'Invalid request method.');
            header('Location: /marketplace');
            exit;
        }

        $itemId = (int)($_POST['item_id'] ?? 0);
        $offer = !empty($_POST['offer_price']) ? (float)$_POST['offer_price'] : 0;
        $paymentMethod = $_POST['payment_method'] ?? 'card';

        $itemModel = new ItemModel();
        $item = $itemModel->findById($itemId);
        
        if (!$item) {
            Session::flash('error', 'Item not found.');
            header('Location: /marketplace');
            exit;
        }

        if (strtolower(trim($item['item_status'])) !== 'available') {
            Session::flash('error', 'This item is no longer available.');
            header('Location: /marketplace');
            exit;
        }

        if ($item['owner_id'] == Session::userId()) {
            Session::flash('error', 'You cannot buy your own item.');
            header('Location: /marketplace/show/' . $itemId);
            exit;
        }

        $offer = min($offer, $item['item_price']);

        if ($offer > 0 && $offer < $item['item_price'] && $item['negotiation_percent'] == 0) {
            Session::flash('error', 'Offer price cannot be lower than the asking price.');
            header('Location: /marketplace/show/' . $itemId);
            exit;
        }

        if ($offer > 0) {
            $minPrice = $item['item_price'] * (1 - ($item['negotiation_percent'] / 100));
            if ($offer < $minPrice) {
                Session::flash('error', 'The seller rejected your offer. It is lower than the accepted negotiation limit.');
                header('Location: /marketplace/show/' . $itemId);
                exit;
            }
        }

        $price = $offer > 0 ? $offer : $item['item_price'];
        $fee = round($price * 0.05, 2);

        $orderId = $this->orderModel->create(
            Session::userId(), $item['owner_id'],
            [['item_id' => $itemId, 'price' => $price]], $fee
        );

        $txId = $this->orderModel->createTransaction($orderId, $paymentMethod);
        $this->orderModel->createEscrow($txId, $price + $fee);
        $this->orderModel->updateTransactionStatus($txId, 'completed');

        $itemModel->lockItem($itemId);

        $shippingModel = new ShippingModel();
        $shippingModel->create($orderId, 50);

        // Eco points are awarded based on items exchanging hands
        $userModel = new UserModel();
        $userModel->updateEcoPoints(Session::userId(), 10);
        $userModel->updateEcoPoints($item['owner_id'], 15);

        $chatModel = new ChatModel();
        $chatModel->createNotification($item['owner_id'], 'Your item "' . $item['title'] . '" has been purchased!', 'order');
        $chatModel->createNotification(Session::userId(), 'Order #' . $orderId . ' confirmed.', 'order');

        header('Location: /order/show/' . $orderId);
        exit;
    }

    public function confirmDelivery() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $orderId = $_POST['order_id'] ?? 0;
        $order = $this->orderModel->findById($orderId);
        if (!$order || $order['buyer_id'] != Session::userId()) { header('Location: /order'); exit; }

        $this->orderModel->updateStatus($orderId, 'delivered');
        $tx = $this->orderModel->getTransaction($orderId);
        if ($tx) {
            $escrow = $this->orderModel->getEscrow($tx['transaction_id']);
            if ($escrow) $this->orderModel->releaseEscrow($escrow['escrow_id']);
        }

        $shippingModel = new ShippingModel();
        $ship = $shippingModel->findByOrder($orderId);
        if ($ship) $shippingModel->updateStatus($ship['shipping_id'], 'delivered');

        header('Location: /order/show/' . $orderId);
        exit;
    }

    // --- SSD Additional Methods: Buy Process ---
    public function buyItem($item) {
        // SSD: buy item
        echo "Purchasing sequence init.";
    }

    public function createTransaction($initiatorID, $receiverID) {
        // SSD: Transaction explicitly created
        echo "Transaction generated.";
    }

    public function requestPrice($price) {
        // SSD: Transaction to Seller
        echo "Price requested from seller.";
    }

    public function destroyTransaction() {
        // SSD: Transaction canceled
        echo "Transaction destroyed.";
    }

    public function choosePaymentMethod($method) {
        echo "Payment method chosen: " . $method;
    }

    public function setHandoverMode($mode) {
        echo "Handover mode set to: " . $mode;
    }
}
