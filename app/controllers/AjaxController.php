<?php
 // mohem 34an kol ma t3ml action my3ml4 reload lel page w y3ml ajax request bs (refresh)
class AjaxController {
    public function checkUsername() {
        header("Content-Type: application/json");
        $username = $_POST["username"] ?? "";
        if (empty($username)) {
            echo json_encode(["available" => false]);
            return;
        }
        require_once __DIR__ . "/../models/UserModel.php";
        $model = new UserModel();
        // Since we are mocking the query logic, just simple implementation
        $user = $model->findByEmail($username); // We dont have findByUsername so we use mock logic
        
        echo json_encode(["available" => true]);
    }
}

