<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ReportModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class AdminController {
    public function index() {
        if (!Session::isLoggedIn() || !in_array(Session::userRole(), ['admin', 'moderator'])) {
            header('Location: /auth/login');
            exit;
        }

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $pending = $userModel->getPendingUpcyclers();

        $data = [
            'page_title' => 'Admin',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'pending_upcyclers' => $pending,
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function approveUpcycler() {
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $userId = $_POST['user_id'] ?? 0;
        $userModel = new UserModel();
        $userModel->approveUpcycler($userId);

        $chatModel = new ChatModel();
        $chatModel->createNotification($userId, 'Your upcycler application has been approved!', 'community');

        header('Location: /admin');
        exit;
    }

    public function rejectUpcycler() {
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $userId = $_POST['user_id'] ?? 0;
        $reason = $_POST['reason'] ?? '';
        $userModel = new UserModel();
        $userModel->rejectUpcycler($userId, $reason);

        $chatModel = new ChatModel();
        $chatModel->createNotification($userId, 'Your upcycler application was rejected. Reason: ' . $reason, 'community');

        header('Location: /admin');
        exit;
    }

    public function reports() {
        if (!Session::isLoggedIn() || !in_array(Session::userRole(), ['admin', 'moderator'])) {
            header('Location: /auth/login');
            exit;
        }

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Admin Reports',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'report_summary' => 'Behavioral and sustainability reports overview.',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/admin/reports.php';
    }
}
