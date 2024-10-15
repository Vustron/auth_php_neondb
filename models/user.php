<?php

class User
{
    private $pdo;
    private $pepper = 'a_random_secret_pepper_value';
    private $isLocal;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        session_start();
        $this->isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
    }

    private function generateUniqueID($length = 21)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function register($firstName, $lastName, $email, $password)
    {
        $response = array();

        // Generate a unique ID
        $userId = $this->generateUniqueID();

        // Ensure the ID is unique
        while ($this->isIDExists($userId)) {
            $userId = $this->generateUniqueID();
        }

        // Pepper the password by hashing it with HMAC
        $pepperedPassword = hash_hmac("sha256", $password, $this->pepper);

        // Hash the password using bcrypt (which adds its own salt)
        $hashedPassword = password_hash($pepperedPassword, PASSWORD_BCRYPT);

        // Check if the email already exists in the database
        $checkEmail = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($checkEmail);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $response['error'] = true;
            $response['message'] = "Email Address Already Exists!";
            return $response;
        }

        // Insert the user data with the hashed password and unique ID
        $insertUserQuery = "INSERT INTO users (id, first_name, last_name, email, password) 
                            VALUES (:id, :first_name, :last_name, :email, :password)";
        $stmt = $this->pdo->prepare($insertUserQuery);
        if ($stmt->execute(['id' => $userId, 'first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'password' => $hashedPassword])) {
            // Insert the hash into the user_hashes table
            $insertHashQuery = "INSERT INTO user_hashes (user_id, hash) VALUES (:user_id, :hash)";
            $stmt = $this->pdo->prepare($insertHashQuery);
            $stmt->execute(['user_id' => $userId, 'hash' => $hashedPassword]);

            $response['error'] = false;
            $response['message'] = "User registered successfully.";
        } else {
            $response['error'] = true;
            $response['message'] = "Error: " . $stmt->errorInfo()[2];
        }

        return $response;
    }

    private function isIDExists($id)
    {
        $checkId = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($checkId);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function login($email, $password)
    {
        $response = array();

        // Fetch the user record from the database
        $checkEmail = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($checkEmail);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            $userId = $user['id'];

            // Fetch the hash from the user_hashes table
            $checkHash = "SELECT hash FROM user_hashes WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($checkHash);
            $stmt->execute(['user_id' => $userId]);
            $hashRecord = $stmt->fetch();
            $storedHashedPassword = $hashRecord['hash'];

            // Pepper the input password
            $pepperedPassword = hash_hmac("sha256", $password, $this->pepper);

            // Verify the password
            if (password_verify($pepperedPassword, $storedHashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $user['email'];
                $response['error'] = false;
                $response['message'] = "Login successful.";
            } else {
                $response['error'] = true;
                $response['message'] = "Invalid email or password.";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Invalid email or password.";
        }

        return $response;
    }

    public function getUser()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        $query = "SELECT first_name, last_name, email, created_at FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $userId]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }

        return null;
    }

    public function logout()
    {
        // Clear all session variables
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Redirect to login page
        $loginUrl = $this->isLocal ? '/LogInSystem2/index.php' : '/index.php';
        header("Location: $loginUrl");
        exit();
    }

     public function getSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $currentUrl = $_SERVER['REQUEST_URI'];
        
        $homeUrl = $this->isLocal ? '/LogInSystem2/views/home.php' : '/views/home.php';
        $loginUrl = $this->isLocal ? '/LogInSystem2/index.php' : '/index.php';

        if (isset($_SESSION['user_id']) && $currentUrl !== $homeUrl) {
            header("Location: $homeUrl");
            exit();
        } elseif (!isset($_SESSION['user_id']) && $currentUrl !== $loginUrl) {
            header("Location: $loginUrl");
            exit();
        }
    }
}
