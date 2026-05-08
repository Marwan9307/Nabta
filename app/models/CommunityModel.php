<?php

require_once __DIR__ . '/../config/database.php';

class CommunityModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('community');
    }

    public function createPost($authorId, $title, $content, $mediaUrl = '', $postType = 'general') {
        $stmt = $this->db->prepare("INSERT INTO community_posts (author_id, title, content, media_url, post_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$authorId, $title, $content, $mediaUrl, $postType]);
        return $this->db->lastInsertId();
    }

    public function getPost($id) {
        $stmt = $this->db->prepare("SELECT * FROM community_posts WHERE post_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAllPosts($type = null) {
        if ($type) {
            $stmt = $this->db->prepare("SELECT * FROM community_posts WHERE post_type = ? ORDER BY created_at DESC");
            $stmt->execute([$type]);
        } else {
            $stmt = $this->db->query("SELECT * FROM community_posts ORDER BY created_at DESC");
        }
        return $stmt->fetchAll();
    }

    public function addComment($postId, $authorId, $content) {
        $stmt = $this->db->prepare("INSERT INTO community_comments (post_id, author_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$postId, $authorId, $content]);
        return $this->db->lastInsertId();
    }

    public function getComments($postId) {
        $stmt = $this->db->prepare("SELECT * FROM community_comments WHERE post_id = ? ORDER BY created_at ASC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    public function createMentorship($mentorId, $menteeId) {
        $stmt = $this->db->prepare("INSERT OR IGNORE INTO mentorships (mentor_id, mentee_id) VALUES (?, ?)");
        return $stmt->execute([$mentorId, $menteeId]);
    }

    public function getMentorships($userId) {
        $stmt = $this->db->prepare("SELECT * FROM mentorships WHERE mentor_id = ? OR mentee_id = ?");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll();
    }

    public function getEligibleMentors() {
        $userDb = Database::get('users');
        return $userDb->query("SELECT u.user_id, u.username, r.eco_points FROM users u JOIN registered r ON u.user_id = r.user_id JOIN upcyclers up ON u.user_id = up.user_id WHERE up.status = 'approved' AND r.eco_points >= 500 ORDER BY r.eco_points DESC")->fetchAll();
    }

    public function deletePost($postId) {
        $this->db->prepare("DELETE FROM community_comments WHERE post_id = ?")->execute([$postId]);
        return $this->db->prepare("DELETE FROM community_posts WHERE post_id = ?")->execute([$postId]);
    }
}
