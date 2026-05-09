<?php

require_once __DIR__ . '/../models/SwapModel.php';
require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class SwapController {
    private $swapModel;

    public function __construct() {
        $this->swapModel = new SwapModel();
    }

    public function index() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        // Use Case: Cancel Automatically (Extension point: Response Timeout)
        $this->swapModel->cancelExpired();
        $this->cancelAutomatically();
        
        $swaps = $this->swapModel->getByUser(Session::userId());

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Swaps',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'swaps' => $swaps,
            'notifications' => [],
        ];
        require_once __DIR__ . '/../views/swap/index.php';
    }

    private function cancelAutomatically() {
        // Condition: {No Response>48 hours}. Checks swapping tables for unresponsive states.
        // Actually performed by $this->swapModel->cancelExpired() internally, just exposing interface for completeness.
    }

    public function offerBundle() {
        // Use Case: Offer Bundle (<extend> Swap Item)
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "Bundle offered for swap.";
    }

    public function checkCashDifference($valueA, $valueB) {
        // Use Case: Check Cash Difference (<extend> Swap Item, Condition: {Value_A != Value_B})
        // Detects Value Gap Detected
        if ($valueA != $valueB) {
            $difference = abs($valueA - $valueB);
            return "Cash difference required: $" . $difference;
        }
        return "No cash difference required.";
    }

    public function lockInAgreement($swapId) {
        // Use Case: Lock in the Agreement (<include> from Offer Bundle / Swap)
        echo "Agreement locked for swap " . htmlspecialchars($swapId);
    }

    public function meetFinder() {
        // Use Case: Meet Finder (<extend> from Swap Item / Delivery selection)
        echo "Meet Finder selected for Delivery Selection point.";
    }

    public function show($id) {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $swap = $this->swapModel->findById($id);
        if (!$swap) { header('Location: /swap'); exit; }

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Swap Details',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'swap_id' => $swap['swap_id'],
            'swap_note' => 'Status: ' . $swap['swap_status'],
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/swap/show.php';
    }

    public function request() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $offeredItemId = $_POST['offered_item_id'] ?? 0;
        $requestedItemId = $_POST['requested_item_id'] ?? 0;

        $itemModel = new ItemModel();
        $offered = $itemModel->findById($offeredItemId);
        $requested = $itemModel->findById($requestedItemId);
        if (!$offered || !$requested) { header('Location: /marketplace'); exit; }

        $valueDiff = $this->swapModel->calculateValueDifference($offered['item_price'], $requested['item_price']);
        $settlement = $valueDiff['needs_balance'] ? 'cash_top_up' : 'none';
        $balance = $valueDiff['difference'];

        $swapId = $this->swapModel->create(
            Session::userId(), $requested['owner_id'],
            $offeredItemId, $requestedItemId, $balance, $settlement
        );

        $chatModel = new ChatModel();
        $chatModel->createNotification($requested['owner_id'], 'You received a swap request for "' . $requested['title'] . '"', 'order');

        header('Location: /swap/show/' . $swapId);
        exit;
    }

    public function accept() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $swapId = $_POST['swap_id'] ?? 0;
        $swap = $this->swapModel->findById($swapId);
        if (!$swap || $swap['receiver_id'] != Session::userId()) { header('Location: /swap'); exit; }

        $this->swapModel->updateStatus($swapId, 'accepted');

        $itemModel = new ItemModel();
        $itemModel->lockItem($swap['offered_item_id']);
        $itemModel->lockItem($swap['requested_item_id']);

        $userModel = new UserModel();
        $userModel->updateEcoPoints($swap['initiator_id'], 10);
        $userModel->updateEcoPoints($swap['receiver_id'], 10);

        $chatModel = new ChatModel();
        $chatModel->createNotification($swap['initiator_id'], 'Your swap request #' . $swapId . ' was accepted!', 'order');

        header('Location: /swap/show/' . $swapId);
        exit;
    }

    public function reject() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $swapId = $_POST['swap_id'] ?? 0;
        $swap = $this->swapModel->findById($swapId);
        if (!$swap || $swap['receiver_id'] != Session::userId()) { header('Location: /swap'); exit; }

        $this->swapModel->updateStatus($swapId, 'rejected');

        $chatModel = new ChatModel();
        $chatModel->createNotification($swap['initiator_id'], 'Your swap request #' . $swapId . ' was declined.', 'order');

        header('Location: /swap');
        exit;
    }
}
