<?php

require_once __DIR__ . '/../config/database.php';

class ItemModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('items');
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO items (owner_id, title, item_description, material_type, item_photo, item_weight, is_upcycled, item_price, negotiation_percent, listing_type, category, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['owner_id'], $data['title'], $data['description'] ?? '',
            $data['material_type'] ?? '', $data['item_photo'] ?? '',
            $data['item_weight'] ?? 0, $data['is_upcycled'] ?? 0,
            $data['item_price'] ?? 0, $data['negotiation_percent'] ?? 0,
            $data['listing_type'] ?? 'available', $data['category'] ?? '',
            $data['size'] ?? ''
        ]);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE item_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach (['title', 'item_description', 'material_type', 'item_photo', 'item_weight', 'is_upcycled', 'item_price', 'negotiation_percent', 'listing_type', 'item_status', 'category', 'size'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = ?";
                $values[] = $data[$f];
            }
        }
        if (empty($fields)) return false;
        $values[] = $id;
        $stmt = $this->db->prepare("UPDATE items SET " . implode(', ', $fields) . " WHERE item_id = ?");
        return $stmt->execute($values);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM items WHERE item_id = ?");
        return $stmt->execute([$id]);
    }

    public function getByOwner($ownerId) {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE owner_id = ? ORDER BY created_at DESC");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll();
    }

    public function getMarketplaceItems($filters = []) {
        $sql = "SELECT * FROM items WHERE item_status = 'available'";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['listing_type'])) {
            $sql .= " AND listing_type = ?";
            $params[] = $filters['listing_type'];
        }
        if (!empty($filters['is_upcycled'])) {
            $sql .= " AND is_upcycled = 1";
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND item_price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND item_price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE ? OR item_description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc': $sql .= " ORDER BY item_price ASC"; break;
            case 'price_desc': $sql .= " ORDER BY item_price DESC"; break;
            default: $sql .= " ORDER BY created_at DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function lockItem($id) {
        return $this->update($id, ['item_status' => 'locked']);
    }

    public function unlockItem($id) {
        return $this->update($id, ['item_status' => 'available']);
    }

    public function saveCondition($itemId, $data) {
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO condition_assessment (item_id, stain_check, tear_check, fading_check, missing_button, usage_frequency, final_grade) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $itemId, $data['stain'] ?? 0, $data['tear'] ?? 0,
            $data['fading'] ?? 0, $data['missing_button'] ?? 0,
            $data['usage_frequency'] ?? '', $data['final_grade'] ?? 'like_new'
        ]);
    }

    public function getCondition($itemId) {
        $stmt = $this->db->prepare("SELECT * FROM condition_assessment WHERE item_id = ?");
        $stmt->execute([$itemId]);
        return $stmt->fetch();
    }

    public function addToCloset($ownerId, $itemId) {
        $stmt = $this->db->prepare("INSERT INTO virtual_closet (owner_id, item_id) VALUES (?, ?)");
        return $stmt->execute([$ownerId, $itemId]);
    }

    public function removeFromCloset($ownerId, $itemId) {
        $stmt = $this->db->prepare("DELETE FROM virtual_closet WHERE owner_id = ? AND item_id = ?");
        return $stmt->execute([$ownerId, $itemId]);
    }

    public function getClosetItems($ownerId) {
        $stmt = $this->db->prepare("SELECT i.* FROM virtual_closet vc JOIN items i ON vc.item_id = i.item_id WHERE vc.owner_id = ? ORDER BY vc.added_at DESC");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll();
    }

    public function saveUpcyclingLog($data) {
        $stmt = $this->db->prepare("INSERT INTO upcycling_log (item_id, artisan_id, mentor_id, before_photo, after_photo, upcycling_story, added_materials, water_saved, co2_saved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['item_id'], $data['artisan_id'], $data['mentor_id'] ?? null,
            $data['before_photo'] ?? '', $data['after_photo'] ?? '',
            $data['upcycling_story'] ?? '', $data['added_materials'] ?? '',
            $data['water_saved'] ?? 0, $data['co2_saved'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    public function getUpcyclingLog($itemId) {
        $stmt = $this->db->prepare("SELECT * FROM upcycling_log WHERE item_id = ?");
        $stmt->execute([$itemId]);
        return $stmt->fetch();
    }
}
