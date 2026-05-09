<?php

require_once __DIR__ . '/../config/session.php';

class MultiRoleController {
    // Current active role based on context (buyer, seller, swapper, upcycler)
    private $activeRole;
    private $allowedRoles = ['shopper', 'merchant', 'swapper'];

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->activeRole = 'shopper'; // Default context
    }

    public function assignRole($registeredUser, $roleEnum) {
        // Logic to assign a specific role to a registered user
        if (in_array($roleEnum, $this->allowedRoles)) {
            // update user role logic
        }
    }

    public function getActiveRoles() {
        return Session::userRole(); // Or logic mapping to current allowed roles
    }

    public function selectActiveRole($roleString) {
        if (in_array($roleString, $this->allowedRoles)) {
            $this->activeRole = $roleString;
        }
    }

    public function verifyRole() {
        return "Current context verified as: " . $this->activeRole;
    }
}
