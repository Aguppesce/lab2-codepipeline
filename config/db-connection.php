<?php

// Leer variables de entorno con fallbacks
$db_host = getenv('DATABASE_HOST') ?: 'lab2-tf-database-service.database-name-space';
$db_name = getenv('DATABASE_NAME') ?: 'app_db';
$db_user = getenv('DATABASE_USER') ?: 'root';
$db_pass = getenv('DATABASE_PASSWORD') ?: 'password';

// Debug: log de variables 
error_log("DB Config - Host: " . $db_host);
error_log("DB Config - Name: " . $db_name);
error_log("DB Config - User: " . $db_user);
error_log("DB Config - Pass: " . ($db_pass ? 'SET' : 'NOT SET'));

if (empty($db_host) || empty($db_name) || empty($db_user) || empty($db_pass)) {
    error_log("ERROR: Missing database environment variables");
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error", true, 500);
    exit("Database configuration error");
}

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name}",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
    error_log("DB Connection successful");
    return $pdo;
} catch (PDOException $e) {
    error_log("DB Connection failed: " . $e->getMessage());
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error", true, 500);
    exit("Database connection error");
}