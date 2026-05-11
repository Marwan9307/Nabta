<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/encryption.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('users');
    }

    public function register($username, $email, $password, $phone, $city, $gender, $bio = '', $profilePic = '') {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $encPhone = Encryption::encrypt($phone);
        $encCity = Encryption::encrypt($city);

        $this->db->beginTransaction();

        $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'registered')");
        $stmt->execute([$username, $email, $hash]);
        $userId = $this->db->lastInsertId();

        $stmt = $this->db->prepare("INSERT INTO registered (user_id, gender, bio, profile_picture, city) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $gender, $bio, $profilePic, $encCity]);

        $stmtMobile = $this->db->prepare("INSERT INTO registered_mobile (user_id, mobile_no) VALUES (?, ?)");
        $stmtMobile->execute([$userId, $encPhone]);

        $this->db->commit();
        return $userId;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }
        return $user;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT u.*, r.*, rm.mobile_no, rs.fabric_sensitivity FROM users u LEFT JOIN registered r ON u.user_id = r.user_id LEFT JOIN registered_mobile rm ON u.user_id = rm.user_id LEFT JOIN registered_sensitivity rs ON u.user_id = rs.user_id WHERE u.user_id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if ($user && isset($user['mobile_no'])) {
            $user['mobile_no'] = Encryption::decrypt($user['mobile_no']);
        }
        if ($user && $user['city']) {
            $user['city'] = Encryption::decrypt($user['city']);
        }
        return $user;
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function updateProfile($userId, $data) {
        $fields = [];
        $values = [];
        foreach (['bio', 'profile_picture', 'badge', 'gender', 'style_preference', 'color_palette', 'shoe_size', 'bottom_size', 'top_size'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = ?";
                $values[] = $data[$f];
            }
        }
        
        // Handle standalone tables for mapping
        if (isset($data['fabric_sensitivity'])) {
            $stmtSens = $this->db->prepare("INSERT INTO registered_sensitivity (user_id, fabric_sensitivity) VALUES (?, ?) ON CONFLICT(user_id) DO UPDATE SET fabric_sensitivity=excluded.fabric_sensitivity");
            $stmtSens->execute([$userId, $data['fabric_sensitivity']]);
        }
        if (isset($data['mobile_no'])) {
            $stmtMob = $this->db->prepare("INSERT INTO registered_mobile (user_id, mobile_no) VALUES (?, ?) ON CONFLICT(user_id) DO UPDATE SET mobile_no=excluded.mobile_no");
            $stmtMob->execute([$userId, Encryption::encrypt($data['mobile_no'])]);
        }

        if (isset($data['city'])) {
            $fields[] = "city = ?";
            $values[] = Encryption::encrypt($data['city']);
        }
        if (isset($data['building_no'])) {
            $fields[] = "building_no = ?";
            $values[] = Encryption::encrypt($data['building_no']);
        }
        if (isset($data['street_name'])) {
            $fields[] = "street_name = ?";
            $values[] = Encryption::encrypt($data['street_name']);
        }
        if (empty($fields)) return false;
        $values[] = $userId;
        $sql = "UPDATE registered SET " . implode(', ', $fields) . " WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function updateEcoPoints($userId, $points) {
        $stmt = $this->db->prepare("UPDATE registered SET eco_points = eco_points + ? WHERE user_id = ?");
        return $stmt->execute([$points, $userId]);
    }

    public function updateTrustScore($userId, $score) {
        $stmt = $this->db->prepare("UPDATE registered SET trust_score = ? WHERE user_id = ?");
        return $stmt->execute([$score, $userId]);
    }

    public function applyUpcyclerRole($userId, $portfolio) {
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO upcycler (user_id, portfolio, status) VALUES (?, ?, 'pending')");
        return $stmt->execute([$userId, $portfolio]);
    }

    public function approveUpcycler($userId) {
        $stmt = $this->db->prepare("UPDATE upcycler SET status = 'approved' WHERE user_id = ?");
        $stmt->execute([$userId]);
        $stmt = $this->db->prepare("UPDATE users SET role = 'upcycler' WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function rejectUpcycler($userId, $reason = '') {
        $stmt = $this->db->prepare("UPDATE upcycler SET status = 'rejected' WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function getPendingUpcyclers() {
        $stmt = $this->db->query("SELECT u.user_id, u.username, up.portfolio, up.status FROM users u JOIN upcycler up ON u.user_id = up.user_id WHERE up.status = 'pending'");
        return $stmt->fetchAll();
    }

    public function follow($followerId, $followedId) {
        $stmt = $this->db->prepare("INSERT OR IGNORE INTO follows (follower_id, followed_id) VALUES (?, ?)");
        return $stmt->execute([$followerId, $followedId]);
    }

    public function unfollow($followerId, $followedId) {
        $stmt = $this->db->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
        return $stmt->execute([$followerId, $followedId]);
    }

    public function getFollowers($userId) {
        $stmt = $this->db->prepare("SELECT u.user_id, u.username FROM follows f JOIN users u ON f.follower_id = u.user_id WHERE f.followed_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAllUsers() {
        return $this->db->query("SELECT u.user_id, u.username, u.email, u.role, r.eco_points, r.trust_score FROM users u LEFT JOIN registered r ON u.user_id = r.user_id")->fetchAll();
    }

    public function savePreferences($userId, $data) {
        $fields = [];
        $values = [];
        foreach (['style_preference', 'color_palette', 'shoe_size', 'bottom_size', 'top_size', 'fabric_sensitivity'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = ?";
                $values[] = $data[$f];
            }
        }
        if (empty($fields)) return false;
        $values[] = $userId;
        $stmt = $this->db->prepare("UPDATE registered SET " . implode(', ', $fields) . " WHERE user_id = ?");
        return $stmt->execute($values);
    }

    public function getStaff() {
        return $this->db->query("SELECT u.user_id, u.username, u.role, s.salary, s.specialization FROM users u JOIN staff s ON u.user_id = s.user_id")->fetchAll();
    }
}
