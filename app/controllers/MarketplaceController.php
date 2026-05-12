<?php

require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class MarketplaceController {
    private $itemModel;

    public function __construct() {
        $this->itemModel = new ItemModel();
    }

    public function index() {
        $filters = [
            'category' => $_GET['category'] ?? '',
            'material' => $_GET['material'] ?? '', 
            'condition' => $_GET['condition'] ?? '', 
            'gender' => $_GET['gender'] ?? '',
            'listing_type' => $_GET['listing_type'] ?? '',
            'is_upcycled' => $_GET['upcycled'] ?? '',
            'swap_available' => $_GET['swap_available'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'search' => $_GET['q'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
        ];

        $items = $this->itemModel->getMarketplaceItems($filters);

        $data = [
            'page_title' => 'Marketplace',
            'is_logged_in' => Session::isLoggedIn(),
            'items' => $items,
            'notifications' => [],
            'chat_users' => [],
        ];

        if (Session::isLoggedIn()) {
            $userModel = new UserModel();
            $user = $userModel->findById(Session::userId());
            $data['avatar'] = $user['profile_picture'] ?: 'https://placehold.co/40x40';
        }

        require_once __DIR__ . '/../views/marketplace/index.php';
    }

    // --- SSD Methods: Browse Marketplace ---
    public function browseMarketplace() {
        echo "Marketplace opened.";
    }

    public function searchItem($itemCriteria) {
        $this->queryDatabase();
    }

    private function queryDatabase() {}

    public function filterItems($gender, $material, $category) {
        $this->applyFilters();
    }

    private function applyFilters() {}

    public function selectItem($itemID) {
        // Maps to getItemInfo and fetching details
    }

    public function assessItem($Item) {
        // Assess item logic mapped to MarketPlace controller
    }

    public function browseMarketPage() {
        echo "Browse market page.";
    }

    public function getTrustScore() {
        // Fetch Seller Reliability Data
    }

    public function show($id) {
        $item = $this->itemModel->findById($id);
        if (!$item) { 
            Session::flash('error', 'Item not found.');
            header('Location: /marketplace'); 
            exit; 
        }

        // Check if item is still available
        if (strtolower(trim($item['item_status'])) !== 'available') {
            Session::flash('error', 'This item is no longer available for purchase.');
            header('Location: /marketplace'); 
            exit; 
        }

        $userModel = new UserModel();
        $seller = $userModel->findById($item['owner_id']);
        $condition = $this->itemModel->getCondition($id);

        $negotiationPercent = $item['negotiation_percent'] ?? 0;
        $minPrice = $item['item_price'] * (1 - ($negotiationPercent / 100));

        $data = [
            'page_title' => $item['title'],
            'is_logged_in' => Session::isLoggedIn(),
            'item_id' => $item['item_id'],
            'item_image' => $item['item_photo'] ?: 'https://placehold.co/700x900',
            'item_title' => $item['title'],
            'seller_id' => $item['owner_id'],
            'seller_name' => $seller['username'] ?? 'Unknown',
            'trust_score' => ($seller['trust_score'] ?? 0) . '/5',
            'description' => $item['item_description'],
            'price' => $item['item_price'],
            'negotiation' => $negotiationPercent . '%',
            'negotiation_percent' => $negotiationPercent,
            'min_price' => $minPrice,
            'material_type' => $item['material_type'] ?? '',
            'size' => $item['size'] ?? '',
            'is_upcycled' => $item['is_upcycled'] ?? 0,
            'notifications' => [],
            'chat_users' => [],
        ];

        if (Session::isLoggedIn()) {
            $user = $userModel->findById(Session::userId());
            $data['avatar'] = $user['profile_picture'] ?: 'https://placehold.co/40x40';
            $closetItems = $this->itemModel->getClosetItems(Session::userId());
            $data['closet_item'] = !empty($closetItems) ? $closetItems[0]['title'] : 'No items';
        }

        require_once __DIR__ . '/../views/marketplace/show.php';
    }
}
