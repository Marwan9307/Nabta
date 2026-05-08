<?php

require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/CommunityModel.php';
require_once __DIR__ . '/../config/session.php';

class UpcycleController {
    public function index() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data = [
            'page_title' => 'Upcycle Hub',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/upcycle/index.php';
    }

    public function track() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data = [
            'page_title' => 'Track Upcycle',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'progress' => 55,
            'phase' => 'Pattern redesign',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/upcycle/track.php';
    }

    public function mentor() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data = [
            'page_title' => 'Mentor',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/upcycle/mentor.php';
    }

    public function logTransformation() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $itemId = $_POST['item_id'] ?? 0;
        $itemModel = new ItemModel();
        $item = $itemModel->findById($itemId);
        if (!$item) { header('Location: /upcycle'); exit; }

        $beforePhoto = '';
        $afterPhoto = '';
        $dir = __DIR__ . '/../../public/uploads/upcycle';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        if (isset($_FILES['before_photo']) && $_FILES['before_photo']['error'] === 0) {
            $ext = pathinfo($_FILES['before_photo']['name'], PATHINFO_EXTENSION);
            $beforePhoto = '/uploads/upcycle/' . uniqid('b_') . '.' . $ext;
            move_uploaded_file($_FILES['before_photo']['tmp_name'], __DIR__ . '/../../public' . $beforePhoto);
        }
        if (isset($_FILES['after_photo']) && $_FILES['after_photo']['error'] === 0) {
            $ext = pathinfo($_FILES['after_photo']['name'], PATHINFO_EXTENSION);
            $afterPhoto = '/uploads/upcycle/' . uniqid('a_') . '.' . $ext;
            move_uploaded_file($_FILES['after_photo']['tmp_name'], __DIR__ . '/../../public' . $afterPhoto);
        }

        $analytics = new AnalyticsModel();
        $saving = $analytics->calculateCarbonSaving($item['material_type'] ?: 'cotton', $item['item_weight'] ?: 0.5);

        $itemModel->saveUpcyclingLog([
            'item_id' => $itemId,
            'artisan_id' => Session::userId(),
            'mentor_id' => $_POST['mentor_id'] ?? null,
            'before_photo' => $beforePhoto,
            'after_photo' => $afterPhoto,
            'upcycling_story' => $_POST['story'] ?? '',
            'added_materials' => $_POST['added_materials'] ?? '',
            'water_saved' => $saving['water_saved'],
            'co2_saved' => $saving['co2_saved'],
        ]);

        $itemModel->update($itemId, ['is_upcycled' => 1]);
        $analytics->logCarbonSaving(Session::userId(), $itemId, $saving['co2_saved'], $saving['water_saved'], 'upcycle');

        $userModel = new UserModel();
        $userModel->updateEcoPoints(Session::userId(), 20);

        header('Location: /upcycle');
        exit;
    }
}
