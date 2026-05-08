<?php

require_once __DIR__ . '/../config/database.php';

class ChatModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('chat');
    }

    public function sendMessage($senderId, $receiverId, $text) {
        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, msg_text) VALUES (?, ?, ?)");
        $stmt->execute([$senderId, $receiverId, $text]);
        return $this->db->lastInsertId();
    }

    public function getConversation($userId1, $userId2) {
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
        $stmt->execute([$userId1, $userId2, $userId2, $userId1]);
        return $stmt->fetchAll();
    }

    public function getChatUsers($userId) {
        $stmt = $this->db->prepare("SELECT DISTINCT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as other_id FROM messages WHERE sender_id = ? OR receiver_id = ?");
        $stmt->execute([$userId, $userId, $userId]);
        return $stmt->fetchAll();
    }

    public function createNotification($userId, $text, $type = 'general') {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, msg_text, notification_type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $text, $type]);
        return $this->db->lastInsertId();
    }

    public function getNotifications($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function markRead($notificationId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
        return $stmt->execute([$notificationId]);
    }

    public function markAllRead($userId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$userId]);
        return $stmt->fetch()['cnt'];
    }
}
