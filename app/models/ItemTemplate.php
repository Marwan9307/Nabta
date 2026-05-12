<?php


class ItemTemplate {
    private $templateID;
    private $name;
    private $description;
    private $itemWeight;
    private $upcycled;
    private $category;

    public function __construct($name, $description, $weight, $upcycled, $category) {
        $this->name = $name;
        $this->description = $description;
        $this->itemWeight = $weight;
        $this->upcycled = $upcycled;
        $this->category = $category;
    }

    public function getSharedInfo() {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'weight' => $this->itemWeight,
            'upcycled' => $this->upcycled,
            'category' => $this->category
        ];
    }
}
