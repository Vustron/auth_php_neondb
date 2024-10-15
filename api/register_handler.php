<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../models/database.php';
require_once '../models/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $database = new Database();
    $pdo = $database->getPdo();
    $user = new User($pdo);

    $response = $user->register($firstName, $lastName, $email, $password);

    if ($response['error']) {
        $errorMessage = urlencode($response['message']);
        header("Location: ../views/register_form.php?error=$errorMessage");
        exit();
    } else {
        header('Location: ../views/login_form.php');
        exit();
    }
}

