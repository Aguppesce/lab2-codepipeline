<?php
error_log("=== DIC/USERS.PHP STARTED ===");
error_log("About to require config/db-connection.php");

try {
    $pdo = require "config/db-connection.php";
    error_log("PDO connection returned successfully");
    error_log("PDO is: " . ($pdo ? "OBJECT" : "NULL"));
    
    $usersService = new Service\UsersService($pdo);
    error_log("UsersService created successfully");
    
    return $usersService;
} catch (Exception $e) {
    error_log("ERROR in dic/users.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    throw $e;
}