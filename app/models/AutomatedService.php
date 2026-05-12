<?php


interface Observer {
    public function receiveNotification($notification);
}

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notifyObservers($eventData);
}

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

class Notification {
    private $message;
    private $type; 
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
