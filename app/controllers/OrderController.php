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
        $itemId = $_POST['item_id'] ?? 0;
        $offer = $_POST['offer_price'] ?? 0;
        $paymentMethod = $_POST['payment_method'] ?? 'card';

        $itemModel = new ItemModel();
        $item = $itemModel->findById($itemId);
        if (!$item || $item['item_status'] !== 'available') { header('Location: /marketplace'); exit; }

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

        $analytics = new AnalyticsModel();
        if ($item['material_type'] && $item['item_weight']) {
            $saving = $analytics->calculateCarbonSaving($item['material_type'], $item['item_weight']);
            $analytics->logCarbonSaving(Session::userId(), $itemId, $saving['co2_saved'], $saving['water_saved'], 'purchase');
        }

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
}
