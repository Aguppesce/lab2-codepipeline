<?php
// Leer variables de entorno
$host = $_ENV['DATABASE_HOST'] ?? getenv('DATABASE_HOST') ?: 'localhost';
$dbname = $_ENV['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?: 'app_db';
$user = $_ENV['DATABASE_USER'] ?? getenv('DATABASE_USER') ?: 'root';
$password = $_ENV['DATABASE_PASSWORD'] ?? getenv('DATABASE_PASSWORD') ?: 'password';

// Debug (TEMPORAL - eliminar después)
error_log("DB Connection attempt: host=$host, dbname=$dbname, user=$user, pass_length=" . strlen($password));

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    error_log("DB Connection successful");
    return $pdo;
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    error_log("Connection details: host=$host, dbname=$dbname, user=$user");
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}