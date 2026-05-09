<?php

// The Observer interface (Registered Users)
interface Observer {
    public function receiveNotification($notification);
}

// The Subject interface (Transaction, Order, Community)
interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notifyObservers($eventData);
}

// Observable Coordinator
class AutomatedService implements Subject {
    private $observers = [];

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    // Pushing the notification to all subscribers
    public function notifyObservers($notification) {
        foreach ($this->observers as $observer) {
            $observer->receiveNotification($notification);
        }
    }

    public function notifyFollowers($eventData) {
        $notification = new Notification($eventData['message'], $eventData['type']);
        $this->notifyObservers($notification);
    }
}

// Concrete Notification Object
class Notification {
    private $message;
    private $type; // ORDER, PAYMENT, COMMUNITY
    private $isRead;
    private $createdAt;

    public function __construct($message, $type) {
        $this->message = $message;
        $this->type = $type;
        $this->isRead = false;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function isRead() {
        return $this->isRead;
    }

    public function getDetails() {
        return "[$this->type] {$this->message}";
    }
}
