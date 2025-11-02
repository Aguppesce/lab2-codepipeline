<?php
// Configurar para mostrar errores en pantalla (TEMPORAL para debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Escribir a error log
error_log("========================================");
error_log("USER.PHP STARTED");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("GET params: " . print_r($_GET, true));
error_log("========================================");

try {
    error_log("About to require dic/users.php");
    
    $usersService = require "dic/users.php";
    error_log("UsersService loaded successfully");
    
    $userId = $_GET["id"] ?? "NOT_SET";
    error_log("Requesting user ID: " . $userId);
    
    $user = $usersService->getById($userId);
    error_log("User retrieved: " . ($user ? "YES - " . $user->name : "NULL"));
    
    if ($user === null) {
        error_log("User not found, returning 404");
        http_response_code(404);
        echo "<h1>404 - User not found</h1>";
        return;
    }
    
    error_log("Loading tweets service");
    $tweetsService = (require "dic/tweets.php");
    
    error_log("Getting tweets for user");
    $tweets = $tweetsService->getLastByUser($userId);
    $tweetsCount = $tweetsService->getTweetsCount($userId);
    
    error_log("Tweets loaded: " . $tweetsCount);
    
    switch (require "dic/negotiated_format.php") {
        case "text/html":
            error_log("Rendering HTML view");
            (new Views\Layout(
                "Tweets from @$userId",
                new Views\Tweets\Listing($user, $tweets, $tweetsCount)
            ))();
            exit;
        case "application/json":
            error_log("Rendering JSON");
            header("Content-Type: application/json");
            echo json_encode(["count" => $tweetsCount, "last20" => $tweets]);
            exit;
    }
    http_response_code(406);
    
} catch (Exception $e) {
    error_log("========================================");
    error_log("FATAL ERROR in user.php");
    error_log("Error: " . $e->getMessage());
    error_log("File: " . $e->getFile() . " Line: " . $e->getLine());
    error_log("Stack trace:");
    error_log($e->getTraceAsString());
    error_log("========================================");
    
    http_response_code(500);
    echo "<h1>500 - Internal Server Error</h1>";
    echo "<pre>Error: " . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>File: " . htmlspecialchars($e->getFile()) . " Line: " . $e->getLine() . "</pre>";
    echo "<h3>Check CloudWatch logs for full stack trace</h3>";
}