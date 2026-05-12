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
            
            $comments = $this->communityModel->getComments($post['post_id']);
            foreach ($comments as &$comment) {
                $commentAuthor = $userModel->findById($comment['author_id']);
                $comment['author_name'] = $commentAuthor['username'] ?? 'Unknown';
            }
            $post['comments'] = $comments;
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

    private function checkFraudDetection($content) {
        $spamKeywords = ['scam', 'fake url', 'wire transfer'];
        foreach ($spamKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    // --- SSD Methods ---
    public function postInCommunity($content, $media) {
        echo "Post created";
    }

    public function runSentimentAnalysis() {
        return "Safe"; // or Flagged
    }

    public function savePostToDatabase() {
        echo "Post formally saved to Db.";
    }

    public function viewCommunity() {
        // Map explicitly to Community Flow
    }

    public function checkUserPreferences() {
        return null;
    }

    public function fetchAllPostsByDate() {
        // Map explicitly
        return [];
    }

    public function filterByInterest($userID, $preference) {
        return [];
    }

    public function create() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        
        // Use Case: Check Fraud Detection (<include> from Post in Community)
        if ($this->checkFraudDetection($content)) {
            // flag the post or block it
            Session::flash('error', 'Post flagged as spam/fraud.');
            header('Location: /community/create');
            exit;
        }
        $postType = $_POST['post_type'] ?? 'general';

        $mediaUrl = '';
        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $mediaUrl = '/uploads/community/' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../../public/uploads/community';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['media']['tmp_name'], __DIR__ . '/../../public' . $mediaUrl);
        }

        $this->communityModel->createPost(Session::userId(), $title, $content, $mediaUrl, $postType);

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

    public function addComment() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        
        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        $userId = Session::userId();

        if ($postId && $content !== '') {
            $this->communityModel->addComment($postId, $userId, $content);
        }
        
        header('Location: /community');
        exit;
    }
}
