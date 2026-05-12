<?php

require_once __DIR__ . '/../config/database.php';

class ItemModel {
    private $db;

    public function __construct() {
        $this->db = Database::get('item');
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO item (owner_id, title, item_description, material_type, item_photo, item_weight, is_upcycled, item_price, negotiation_percent, listing_type, category, size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
        $stmt = $this->db->prepare("SELECT * FROM item WHERE item_id = ?");
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
        $stmt = $this->db->prepare("UPDATE item SET " . implode(', ', $fields) . " WHERE item_id = ?");
        return $stmt->execute($values);
    }

    public function delete($id) {
        $this->db->prepare("DELETE FROM condition_assessment WHERE item_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM style_board_items WHERE item_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM upcycling_log WHERE item_id = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM virtual_closet WHERE item_id = ?")->execute([$id]);
        
        $stmt = $this->db->prepare("DELETE FROM item WHERE item_id = ?");
        return $stmt->execute([$id]);
    }

    public function getByOwner($ownerId) {
        $stmt = $this->db->prepare("SELECT * FROM item WHERE owner_id = ? ORDER BY created_at DESC");
        $stmt->execute([$ownerId]);
        return $stmt->fetchAll();
    }

public function getMarketplaceItems($filters = []) {
    // عملنا JOIN هنا عشان نجيب الـ final_grade من جدول التقييم
    $sql = "SELECT i.*, ca.final_grade 
            FROM item i 
            LEFT JOIN condition_assessment ca ON i.item_id = ca.item_id 
            WHERE i.item_status = 'available'";
    
    $params = [];

    // فلتر النوع (Category) - زودنا Shoes و Jackets
    if (!empty($filters['category'])) {
        $sql .= " AND i.category = ?";
        $params[] = $filters['category'];
    }

    // فلتر الخامة (Material) - جديد
    if (!empty($filters['material'])) {
        $sql .= " AND i.material_type = ?";
        $params[] = $filters['material'];
    }

    // فلتر الحالة (Condition) - بيقرأ من الجدول المربوط
    if (!empty($filters['condition'])) {
        $sql .= " AND ca.final_grade = ?";
        $params[] = $filters['condition'];
    }

    // داخل دالة getMarketplaceItems
    if (!empty($filters['gender'])) {
        $sql .= " AND i.gender = ?"; // أو اسم العمود عندك في الداتابيز
        $params[] = $filters['gender'];
   }

    // بقية الفلاتر (Upcycled / Price / Search)
    if (!empty($filters['is_upcycled'])) {
        $sql .= " AND i.is_upcycled = 1";
    }
    if (!empty($filters['search'])) {
        $sql .= " AND (i.title LIKE ? OR i.item_description LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }

    // الترتيب
    $sort = $filters['sort'] ?? 'newest';
    switch ($sort) {
        case 'price_asc': $sql .= " ORDER BY i.item_price ASC"; break;
        case 'price_desc': $sql .= " ORDER BY i.item_price DESC"; break;
        default: $sql .= " ORDER BY i.item_id DESC";
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

    public function assessItem($itemId, $tearCheck, $cleanlinessCheck, $usageFrequency) {
        $score = 0;
        
        if (strtolower($tearCheck) === 'yes') {
            $score += 33.33;
        }
        
        if (strtolower($cleanlinessCheck) === 'yes') {
            $score += 33.33;
        }
        
        $usage = strtolower($usageFrequency);
        if ($usage === 'little usage') {
            $score += 33.33;
        } elseif ($usage === 'medium usage') {
            $score += 22.22;
        } elseif ($usage === 'too much usage') {
            $score += 11.11;
        }
        
        $finalGrade = '';
        if ($score >= 99) {
            $finalGrade = 'Like New';
        } elseif ($score >= 66) {
            $finalGrade = 'Good';
        } else {
            $finalGrade = 'Fair';
        }
        
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO condition_assessment (item_id, stain_check, tear_check, fading_check, missing_button, usage_frequency, final_grade) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $itemId, 
            strtolower($cleanlinessCheck) === 'no' ? 1 : 0, // Assuming cleanliness maps to stain/fading vaguely
            strtolower($tearCheck) === 'yes' ? 0 : 1, // Assuming "Yes" meant passing the check (not torn)
            0, 
            0, 
            $usageFrequency, 
            $finalGrade
        ]);
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
        $stmt_check = $this->db->prepare("SELECT owner_id FROM item WHERE item_id = ?");
        $stmt_check->execute([$itemId]);
        $row = $stmt_check->fetch();
        
        if ($row && $row['owner_id'] == $ownerId) {
            return $this->delete($itemId);
        }
        return false;
    }

    public function getClosetItems($ownerId) {
        $stmt = $this->db->prepare("SELECT i.* FROM virtual_closet vc JOIN item i ON vc.item_id = i.item_id WHERE vc.owner_id = ? ORDER BY vc.added_at DESC");
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
