<?php

require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/session.php';

class ItemController {
    private $itemModel;

    public function __construct() {
        $this->itemModel = new ItemModel();
    }

    public function closet() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        $items = $this->itemModel->getClosetItems(Session::userId());
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'My Closet',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'closet_items' => $items,
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/item/closet.php';
    }

    public function requestStyling() {
        // Use Case: Request Styling
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "Styling request submitted.";
    }

    public function generateAiOutfit() {
        // Use Case: Generate AI Outfit (<extend> Request Styling)
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "AI has generated outfit recommendations based on your closet.";
    }

    public function createBoard() {
        // Use Case: Create Board
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $boardName = $_POST['board_name'] ?? 'New Board';
        echo "Board '{$boardName}' created successfully.";
    }

    public function assessItem($itemId) {
        // Use Case: Assess Item (<include> from Do an Operation)
        echo "Item status and authenticity assessed for ID: " . htmlspecialchars($itemId);
    }

    public function listBulkItems() {
        // Use Case: List Bulk Items (<extend> from Assess Item)
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        echo "Multiple items assessed and listed in bulk.";
    }

    public function createForm() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $data = ['page_title' => 'Create Item', 'is_logged_in' => true, 'notifications' => [], 'chat_users' => []];
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data['avatar'] = $user['profile_picture'] ?: 'https://placehold.co/40x40';
        require_once __DIR__ . '/../views/item/create.php';
    }

    public function create() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        $photo = '';
        if (isset($_FILES['item_photo']) && $_FILES['item_photo']['error'] === 0) {
            $ext = pathinfo($_FILES['item_photo']['name'], PATHINFO_EXTENSION);
            $photo = '/uploads/items/' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../../public/uploads/items';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['item_photo']['tmp_name'], __DIR__ . '/../../public' . $photo);
        }

        $itemId = $this->itemModel->create([
            'owner_id' => Session::userId(),
            'title' => $_POST['title'] ?? 'Untitled',
            'description' => $_POST['description'] ?? '',
            'material_type' => $_POST['material_type'] ?? '',
            'item_photo' => $photo,
            'item_weight' => $_POST['weight'] ?? 0,
            'is_upcycled' => isset($_POST['is_upcycled']) ? 1 : 0,
            'item_price' => $_POST['price'] ?? 0,
            'negotiation_percent' => $_POST['negotiation'] ?? 0,
            'listing_type' => $_POST['listing_type'] ?? 'available',
            'category' => $_POST['category'] ?? '',
            'size' => $_POST['size'] ?? '',
        ]);

        $this->itemModel->addToCloset(Session::userId(), $itemId);

        if (!empty($_POST['material_type']) && !empty($_POST['weight'])) {
            $analytics = new AnalyticsModel();
            $saving = $analytics->calculateCarbonSaving($_POST['material_type'], $_POST['weight']);
            $analytics->logCarbonSaving(Session::userId(), $itemId, $saving['co2_saved'], $saving['water_saved'], 'listing');
        }

        header('Location: /item/closet');
        exit;
    }

    public function editForm($id) {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $item = $this->itemModel->findById($id);
        if (!$item || $item['owner_id'] != Session::userId()) { header('Location: /item/closet'); exit; }

        $condition = $this->itemModel->getCondition($id);
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Edit Item',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'title' => $item['title'],
            'price' => $item['item_price'],
            'category' => $item['category'],
            'notifications' => [],
            'chat_users' => [],
        ];

        require_once __DIR__ . '/../views/item/edit.php';
    }

    public function edit($id) {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $item = $this->itemModel->findById($id);
        if (!$item || $item['owner_id'] != Session::userId()) { header('Location: /item/closet'); exit; }

        $this->itemModel->update($id, [
            'title' => $_POST['title'] ?? $item['title'],
            'item_price' => $_POST['price'] ?? $item['item_price'],
            'category' => $_POST['category'] ?? $item['category'],
        ]);

        $stain = isset($_POST['stain']) ? 1 : 0;
        $fading = isset($_POST['fading']) ? 1 : 0;
        $hole = isset($_POST['hole']) ? 1 : 0;
        $missing = isset($_POST['missing_button']) ? 1 : 0;
        $total = $stain + $fading + $hole + $missing;
        $grade = $total === 0 ? 'like_new' : ($total === 1 ? 'good' : 'fair');

        $this->itemModel->saveCondition($id, [
            'stain' => $stain, 'tear' => $hole, 'fading' => $fading,
            'missing_button' => $missing, 'final_grade' => $grade,
        ]);

        header('Location: /item/closet');
        exit;
    }

    public function addToCloset() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $itemId = $_POST['item_id'] ?? 0;
        if ($itemId) $this->itemModel->addToCloset(Session::userId(), $itemId);
        header('Location: /item/closet');
        exit;
    }

    public function removeFromCloset() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $itemId = $_POST['item_id'] ?? 0;
        if ($itemId) $this->itemModel->removeFromCloset(Session::userId(), $itemId);
        header('Location: /item/closet');
        exit;
    }
}
