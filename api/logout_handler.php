<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../models/database.php';
require_once '../models/user.php';

$db = new Database();
$pdo = $db->getPdo();

$userModel = new User($pdo);


$userModel->logout();
