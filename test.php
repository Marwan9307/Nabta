<?php
require 'app/config/database.php';
$db = Database::get('orders');
print_r($db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_ASSOC));
