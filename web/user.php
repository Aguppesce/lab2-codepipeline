<?php
error_log("=== USER.PHP STARTED ===");
error_log("User ID requested: " . ($_GET["id"] ?? "NOT SET"));

try {
    $user = (require "dic/users.php")->getById($_GET["id"]);
    error_log("User retrieved: " . ($user ? "YES" : "NULL"));
    
    if ($user === null) {
        error_log("User not found, returning 404");
        http_response_code(404);
        return;
    }
    
    $tweetsService = (require "dic/tweets.php");
    $tweets = $tweetsService->getLastByUser($_GET["id"]);
    $tweetsCount = $tweetsService->getTweetsCount($_GET["id"]);
    
    switch (require "dic/negotiated_format.php") {
        case "text/html":
            (new Views\Layout(
                "Tweets from @$_GET[id]",
                new Views\Tweets\Listing($user, $tweets, $tweetsCount)
            ))();
            exit;
        case "application/json":
            header("Content-Type: application/json");
            echo json_encode(["count" => $tweetsCount, "last20" => $tweets]);
            exit;
    }
    http_response_code(406);
    
} catch (Exception $e) {
    error_log("FATAL ERROR in user.php: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    die("Internal server error. Check logs for details.");
}