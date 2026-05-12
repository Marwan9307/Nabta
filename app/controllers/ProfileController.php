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

        if ($userId === Session::userId() && Session::userRole() !== $user['role']) {
            Session::set('user_role', $user['role']);
        }

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
            'trust_score' => $stars,
            'eco_points' => $user['eco_points'] ?? 0,
            'upcycler_status' => $this->userModel->getUpcyclerStatus($userId),
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

        $motivation = $_POST['motivation'] ?? '';

        $this->userModel->applyUpcyclerRole(Session::userId(), $portfolio, $motivation);

        $chatModel = new ChatModel();
        $chatModel->createNotification(Session::userId(), 'Your upcycler role application is under review.', 'community');
        
        $admins = $this->userModel->getAdmins();
        foreach ($admins as $admin) {
            $chatModel->createNotification($admin['user_id'], 'New upcycler application from user #' . Session::userId(), 'system');
        }
		
		Session::flash('success', 'Your upcycler role application has been sent for review!');

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
