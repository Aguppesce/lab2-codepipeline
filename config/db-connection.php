<?php
// Leer variables de entorno
$host = getenv('DATABASE_HOST') ?: 'localhost';
$dbname = getenv('DATABASE_NAME') ?: 'app_db';
$user = getenv('DATABASE_USER') ?: 'root';
$password = getenv('DATABASE_PASSWORD') ?: 'password';

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
    return $pdo;
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please check logs.");
}