<?php
error_log("=== DIC/USERS.PHP STARTED ===");

try {
    error_log("About to require config/db-connection.php");
    $pdo = require "config/db-connection.php";
    
    if ($pdo === null) {
        error_log("ERROR: PDO is NULL after requiring config/db-connection.php");
        throw new Exception("Database connection returned NULL");
    }
    
    error_log("PDO connection obtained successfully");
    error_log("PDO class: " . get_class($pdo));
    
    error_log("About to create UsersService");
    $usersService = new Service\UsersService($pdo);
    error_log("UsersService created successfully");
    
    return $usersService;
    
} catch (Exception $e) {
    error_log("=== ERROR IN DIC/USERS.PHP ===");
    error_log("Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    throw $e;
}