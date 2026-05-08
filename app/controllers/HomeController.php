<?php

require_once __DIR__ . '/../models/ItemModel.php';
require_once __DIR__ . '/../models/AnalyticsModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ChatModel.php';
require_once __DIR__ . '/../config/session.php';

class HomeController {
    public function index() {
        $analytics = new AnalyticsModel();
        $savings = $analytics->getTotalSavings();

        $data = [
            'page_title' => 'NABTA | Home',
            'is_logged_in' => Session::isLoggedIn(),
            'water_base' => max(1200, round($savings['total_water'])),
            'co2_base' => max(560, round($savings['total_co2'])),
            'notifications' => [],
            'chat_users' => [],
        ];

        if (Session::isLoggedIn()) {
            $userModel = new UserModel();
            $user = $userModel->findById(Session::userId());
            $data['avatar'] = $user['profile_picture'] ?: 'https://placehold.co/40x40';

            $chatModel = new ChatModel();
            $data['notifications'] = $chatModel->getNotifications(Session::userId());
        }

        require_once __DIR__ . '/../views/home/index.php';
    }
}
