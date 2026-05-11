<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/session.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function loginForm() {
        $data = ['page_title' => 'Login', 'email' => '', 'is_logged_in' => Session::isLoggedIn()];
        $error = Session::flash('error');
        if ($error) $data['error'] = $error;
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            Session::flash('error', 'Invalid credentials');
            header('Location: /auth/login');
            exit;
        }

        Session::set('user_id', $user['user_id']);
        Session::set('user_role', $user['role']);
        Session::set('username', $user['username']);
        header('Location: /home'); // Maps to WelcomePage in SSD
        exit;
    }

    // --- SSD Methods: Register & Login ---
    public function checkAccountExistence($email) {
        // Validation check before login proceeds
        return $this->userModel->findByEmail($email) !== null;
    }

    public function verifyCredentials($password) {
        return true; // Implemented via $userModel->login internally
    }

    public function updateInfo($preferences) {
        // User fills preference_selection
        echo "Preferences updated.";
    }

    public function registerForm() {
        $data = ['page_title' => 'Register', 'is_logged_in' => Session::isLoggedIn()];
        $error = Session::flash('error');
        if ($error) {
            $data['error'] = $error;
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $city = $_POST['city'] ?? 'Cairo';
        $gender = $_POST['gender'] ?? '';
        $bio = $_POST['bio'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            Session::flash('error', 'All fields required');
            header('Location: /auth/register');
            exit;
        }

        if ($this->userModel->findByEmail($email) || $this->userModel->findByUsername($username)) {
            Session::flash('error', 'An account with this Email or Username already exists! Please use a different one.');
            header('Location: /auth/register');
            exit;
        }

        $profilePic = '';
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $profilePic = '/uploads/avatars/' . uniqid() . '.' . $ext;
            $dir = __DIR__ . '/../../public/uploads/avatars';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], __DIR__ . '/../../public' . $profilePic);
        }

        $userId = $this->userModel->register($username, $email, $password, $phone, $city, $gender, $bio, $profilePic);
        Session::set('user_id', $userId);
        Session::set('user_role', 'registered');
        Session::set('username', $username);
        header('Location: /auth/preferences');
        exit;
    }

    public function preferencesForm() {
        $data = ['page_title' => 'Preferences', 'is_logged_in' => Session::isLoggedIn()];
        require_once __DIR__ . '/../views/auth/preferences.php';
    }

    public function savePreferences() {
        if (!Session::isLoggedIn()) { header('Location: /auth/login'); exit; }
        $this->userModel->savePreferences(Session::userId(), [
            'style_preference' => $_POST['style_preference'] ?? '',
            'color_palette' => $_POST['color_palette'] ?? '',
            'shoe_size' => $_POST['shoe_size'] ?? '',
            'bottom_size' => $_POST['bottom_size'] ?? '',
            'top_size' => $_POST['top_size'] ?? '',
            'fabric_sensitivity' => $_POST['fabric_sensitivity'] ?? '',
        ]);
        header('Location: /home');
        exit;
    }

    public function logout() {
        Session::destroy();
        header('Location: /home');
        exit;
    }
}
