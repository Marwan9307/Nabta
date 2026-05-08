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
            'listing_type' => $_GET['listing_type'] ?? '',
            'is_upcycled' => $_GET['upcycled'] ?? '',
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

    public function show($id) {
        $item = $this->itemModel->findById($id);
        if (!$item) { header('Location: /marketplace'); exit; }

        $userModel = new UserModel();
        $seller = $userModel->findById($item['owner_id']);
        $condition = $this->itemModel->getCondition($id);

        $data = [
            'page_title' => $item['title'],
            'is_logged_in' => Session::isLoggedIn(),
            'item_image' => $item['item_photo'] ?: 'https://placehold.co/700x900',
            'item_title' => $item['title'],
            'seller_id' => $item['owner_id'],
            'seller_name' => $seller['username'] ?? 'Unknown',
            'trust_score' => ($seller['trust_score'] ?? 0) . '/5',
            'description' => $item['item_description'],
            'price' => $item['item_price'],
            'negotiation' => $item['negotiation_percent'] . '%',
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
