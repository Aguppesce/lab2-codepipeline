<?php
// Leer variables de entorno
$host = $_ENV['DATABASE_HOST'] ?? getenv('DATABASE_HOST') ?: 'FALLBACK_LOCALHOST';
$dbname = $_ENV['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?: 'FALLBACK_DB';
$user = $_ENV['DATABASE_USER'] ?? getenv('DATABASE_USER') ?: 'FALLBACK_USER';
$password = $_ENV['DATABASE_PASSWORD'] ?? getenv('DATABASE_PASSWORD') ?: 'FALLBACK_PASS';

// Escribir a stderr para asegurar que aparezca en CloudWatch
error_log("===== DB CONNECTION DEBUG START =====");
error_log("DATABASE_HOST from env: " . ($host ?? 'NULL'));
error_log("DATABASE_NAME from env: " . ($dbname ?? 'NULL'));
error_log("DATABASE_USER from env: " . ($user ?? 'NULL'));
error_log("PASSWORD length: " . strlen($password));
error_log("===== DB CONNECTION DEBUG END =====");

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
    error_log("===== DB CONNECTION SUCCESSFUL =====");
    return $pdo;
} catch (PDOException $e) {
    error_log("===== DB CONNECTION FAILED =====");
    error_log("Error: " . $e->getMessage());
    error_log("Host tried: $host");
    error_log("Database: $dbname");
    error_log("User: $user");
    http_response_code(500);
    die("Database connection failed: " . $e->getMessage());
}