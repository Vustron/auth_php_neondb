<?php

require_once '../models/database.php';
require_once '../models/user.php';

$db = new Database();
$pdo = $db->getPdo();

$userModel = new User($pdo);
$userModel->getSession();

$user = $userModel->getUser();
?>

<!DOCTYPE html>
<html lang="en">
<?php require '../views/header.php'; ?>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-lg shadow-lg text-center max-w-md w-full">
        <h1 class="text-4xl font-bold mb-4">Hello 
            <?php 
            if ($user) {
                echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']);
            }
            ?> :)
        </h1>
        <div class="text-left mb-4">
            <p class="text-lg font-semibold text-gray-700">
                Email: <span class="text-gray-900"><?php echo htmlspecialchars($user['email']); ?></span>
            </p>
            <p class="text-lg font-semibold text-gray-700">
                Account Created: <span class="text-gray-900"><?php echo date('F j, Y, g:i a', strtotime($user['created_at'])); ?></span>
            </p>
        </div>
        <a href="../api/logout_handler.php" class="inline-block px-6 py-2 text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition duration-150">Logout</a>
    </div>
</body>
</html>
