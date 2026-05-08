<?php

require_once __DIR__ . '/../models/ReportModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/session.php';

class ModeratorController {
    public function index() {
        if (!Session::isLoggedIn() || !in_array(Session::userRole(), ['admin', 'moderator'])) {
            header('Location: /auth/login');
            exit;
        }

        $reportModel = new ReportModel();
        $reports = $reportModel->getByType('communication');

        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());

        $data = [
            'page_title' => 'Moderator',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'reports' => $reports,
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/moderator/index.php';
    }

    public function resolveReport() {
        if (!Session::isLoggedIn() || !in_array(Session::userRole(), ['admin', 'moderator'])) {
            header('Location: /auth/login');
            exit;
        }
        $reportId = $_POST['report_id'] ?? 0;
        $status = $_POST['status'] ?? 'resolved';
        $reportModel = new ReportModel();
        $reportModel->resolve($reportId, Session::userId(), $status);
        header('Location: /moderator');
        exit;
    }

    public function createReport() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $targetId = $_POST['target_id'] ?? 0;
        $type = $_POST['report_type'] ?? 'communication';
        $reason = $_POST['reason'] ?? '';
        $reportModel = new ReportModel();
        $reportModel->create(Session::userId(), $targetId, $type, $reason);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/home'));
        exit;
    }
}
