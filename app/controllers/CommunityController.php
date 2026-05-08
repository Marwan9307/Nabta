<?php

require_once __DIR__ . '/../models/CommunityModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/session.php';

class CommunityController {
    private $communityModel;

    public function __construct() {
        $this->communityModel = new CommunityModel();
    }

    public function index() {
        $posts = $this->communityModel->getAllPosts();
        $userModel = new UserModel();

        foreach ($posts as &$post) {
            $author = $userModel->findById($post['author_id']);
            $post['author_name'] = $author['username'] ?? 'Unknown';
        }

        $mentors = [];
        try { $mentors = $this->communityModel->getEligibleMentors(); } catch (Exception $e) {}

        $data = [
            'page_title' => 'Community',
            'is_logged_in' => Session::isLoggedIn(),
            'posts' => $posts,
            'eligible_mentors' => $mentors,
            'notifications' => [],
            'chat_users' => [],
        ];

        if (Session::isLoggedIn()) {
            $user = $userModel->findById(Session::userId());
            $data['avatar'] = $user['profile_picture'] ?: 'https://placehold.co/40x40';
        }

        require_once __DIR__ . '/../views/community/index.php';
    }

    public function createForm() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $userModel = new UserModel();
        $user = $userModel->findById(Session::userId());
        $data = [
            'page_title' => 'Create Community Post',
            'is_logged_in' => true,
            'avatar' => $user['profile_picture'] ?: 'https://placehold.co/40x40',
            'notifications' => [],
            'chat_users' => [],
        ];
        require_once __DIR__ . '/../views/community/create.php';
    }

    public function create() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $postType = $_POST['post_type'] ?? 'general';

        $this->communityModel->createPost(Session::userId(), $title, $content, '', $postType);

        $userModel = new UserModel();
        $userModel->updateEcoPoints(Session::userId(), 5);

        header('Location: /community');
        exit;
    }

    public function requestMentor() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $mentorId = $_POST['mentor_id'] ?? 0;
        if ($mentorId) {
            $this->communityModel->createMentorship($mentorId, Session::userId());
        }
        header('Location: /community');
        exit;
    }
}
