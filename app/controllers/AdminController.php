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
        $all_users = $userModel->getAllUsers();

        $data = [
            'page_title' => 'Admin',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'pending_upcyclers' => $pending,
            'all_users' => $all_users,
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

    public function acceptMember() {
        // Use Case: Accept Member
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $userId = $_POST['user_id'] ?? 0;
        $userModel = new UserModel();
        // custom model logic: $userModel->updateStatus($userId, 'active');
        header('Location: /admin');
        exit;
    }

    public function makeAdmin() {
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $userId = $_POST['user_id'] ?? 0;
        $userModel = new UserModel();
        
        $db = Database::get('users');
        $stmt = $db->prepare("UPDATE users SET role = 'admin' WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $chatModel = new ChatModel();
        $chatModel->createNotification($userId, 'You have been promoted to Admin!', 'system');

        header('Location: /admin');
        exit;
    }

    public function revokeAdmin() {
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $userId = $_POST['user_id'] ?? 0;
        
        // Prevent revoking own admin access
        if ($userId == Session::userId()) {
            Session::flash('error', 'You cannot revoke your own admin rights.');
            header('Location: /admin');
            exit;
        }

        $db = Database::get('users');
        $stmt = $db->prepare("UPDATE users SET role = 'registered' WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $chatModel = new ChatModel();
        $chatModel->createNotification($userId, 'Your Admin privileges have been revoked.', 'system');

        header('Location: /admin');
        exit;
    }

    public function modifyFees() {
        // Use Case: Modify Fees for Specific Categories
        if (!Session::isLoggedIn() || Session::userRole() !== 'admin') { header('Location: /home'); exit; }
        $category = $_POST['category'] ?? '';
        $fee_percentage = $_POST['fee_percentage'] ?? 0;
        // custom logic to update fees settings
        echo "Fees updated for category: " . htmlspecialchars($category);
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

    // --- SSD Methods: Resolve Conflict (Transaction) ---
    public function resolveTransactionConflicts($reportID) {
        $status = $this->getReportDetails($reportID);
        // resolve logic
        $this->creatNotification(Session::userId());
        $this->fillData([]);
        $this->notifyuser();
    }

    private function getReportDetails($reportID) {
        return "Report Details";
    }

    private function creatNotification($user) {}
    private function fillData($data) {}
    private function notifyuser() {}
}
