<?php

require_once 'ItemTemplate.php';

// Occurrence Class
class ItemOccurrence {
    private $itemID;
    private $price;
    private $status; // LOCKED / UNLOCKED
    private $condition;
    private $owner_id;
    
    // Reference to the Abstraction (ItemTemplate)
    private $template;

    public function __construct($itemID, $price, $status, $condition, ItemTemplate $template) {
        $this->itemID = $itemID;
        $this->price = $price;
        $this->status = $status;
        $this->condition = $condition;
        $this->template = $template; // 1-to-many association
    }

    public function getItemInfo() {
        // Combines occurrence data with shared abstraction data
        $sharedData = $this->template->getSharedInfo();
        return array_merge([
            'itemID' => $this->itemID,
            'price' => $this->price,
            'status' => $this->status,
            'condition' => $this->condition
        ], $sharedData);
    }
}
