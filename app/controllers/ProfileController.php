<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class ProfileController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index($id = null) {
        if (!$id && !Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userId = $id ?: Session::userId();
        $user = $this->userModel->findById($userId);
        if (!$user) { header('Location: /home'); exit; }

        $analytics = new AnalyticsModel();
        $savings = $analytics->getUserSavings($userId);

        $stars = '';
        $score = round($user['trust_score'] ?? 0);
        for ($i = 0; $i < 5; $i++) $stars .= $i < $score ? '★' : '☆';

        $data = [
            'page_title' => 'Profile',
            'is_logged_in' => Session::isLoggedIn(),
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/100x100',
            'name' => $user['username'],
            'email' => $user['email'] ?? 'Not set',
            'bio' => $user['bio'] ?? 'No bio provided.',
            'gender' => ucfirst($user['gender'] ?? 'Not specified'),
            'city' => $user['city'] ?? 'Unknown location',
            'mobile_no' => $user['mobile_no'] ?? 'Not provided',
            'style_preference' => $user['style_preference'] ?? 'Not set',
            'color_palette' => $user['color_palette'] ?? 'Not set',
            'shoe_size' => $user['shoe_size'] ?? 'Not set',
            'bottom_size' => $user['bottom_size'] ?? 'Not set',
            'top_size' => $user['top_size'] ?? 'Not set',
            'fabric_sensitivity' => $user['fabric_sensitivity'] ?? 'None',
            'building_no' => $user['building_no'] ?? '',
            'street_name' => $user['street_name'] ?? '',
            'trust_score' => $stars,
            'eco_points' => $user['eco_points'] ?? 0,
            'notifications' => [],
            'chat_users' => [],
        ];

        require_once __DIR__ . '/../views/profile/index.php';
    }

    // --- SSD Methods: View Trust Score ---
    public function viewProfile() {
        echo "Profile View requested";
    }

    public function getTrustScore() {
        echo "Trust score accessed";
    }

    public function getRank() {
        echo "Rank & Badge determined";
    }

    public function applyRoleForm() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data = [
            'page_title' => 'Apply Role',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/profile/apply_role.php';
    }

    public function applyRole() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        $portfolio = '';
        if (isset($_FILES['portfolio']) && $_FILES['portfolio']['error'] === 0) {
            $ext = pathinfo($_FILES['portfolio']['name'], PATHINFO_EXTENSION);
            $portfolio = '/uploads/portfolios/' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../../public/uploads/portfolios';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['portfolio']['tmp_name'], __DIR__ . '/../../public' . $portfolio);
        }

        $this->userModel->applyUpcyclerRole(Session::userId(), $portfolio);

        $chatModel = new ChatModel();
        $chatModel->createNotification(Session::userId(), 'Your upcycler role application is under review.', 'community');

        header('Location: /profile');
        exit;
    }

    public function update() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $this->userModel->updateProfile(Session::userId(), $_POST);
        header('Location: /profile');
        exit;
    }

    public function updatePhoto() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $profile_picture = '/uploads/avatars/' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../../public/uploads/avatars';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . '/../../public' . $profile_picture);
            
            $data = ['profile_picture' => $profile_picture];
            $this->userModel->updateProfile(Session::userId(), $data);
        }

        header('Location: /profile');
        exit;
    }
}
