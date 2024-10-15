<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Database
{
    private $pdo;

    public function __construct()
    {
        $this->connect();
        $this->initializeTables();
    }

    private function connect()
    {
        $isLocal = $this->isLocalEnvironment();

        if ($isLocal) {
            $this->connectLocal();
        } else {
            $this->connectNeonDB();
        }
    }

    private function isLocalEnvironment()
    {
        return in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
    }
    
    private function connectLocal()
    {
        $host = 'localhost';
        $db = 'login';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Error connecting to local database: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    private function connectNeonDB()
    {
   
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

   
        $endpointId = explode('.', $host)[0];

        $dsn = "pgsql:host=$host;dbname=$db;sslmode=require;options=endpoint=$endpointId";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException("Error connecting to NeonDB: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    
    public function getPdo()
    {
        return $this->pdo;
    }

    private function initializeTables()
    {
        $createUsersTable = "
            CREATE TABLE IF NOT EXISTS users (
                id VARCHAR(21) PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $createHashesTable = "
            CREATE TABLE IF NOT EXISTS user_hashes (
                user_id VARCHAR(21) PRIMARY KEY,
                hash VARCHAR(255) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ";

        try {
            $this->pdo->exec($createUsersTable);
            $this->pdo->exec($createHashesTable);
        } catch (\PDOException $e) {
            throw new \PDOException("Error creating tables: " . $e->getMessage(), (int)$e->getCode());
        }
    }
}
