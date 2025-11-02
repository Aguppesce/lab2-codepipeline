<?php
error_log("========================================");
error_log("INDEX.PHP LOADED");
error_log("Time: " . date('Y-m-d H:i:s'));
error_log("========================================");

// CARGAR AUTOLOADER PRIMERO - Esto resuelve el error de clases
require_once __DIR__ . '/../autoloader.php';

// CARGAR AUTOLOADER DE COMPOSER - Para dependencias externas
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

$lastJoinedUsers = (require "dic/users.php")->getLastJoined();

switch (require "dic/negotiated_format.php") {
    case "text/html":
        (new Views\Layout(
            "Twitter - Newcomers", new Views\Users\Listing($lastJoinedUsers), true
        ))();
        exit;

    case "application/json":
        header("Content-Type: application/json");
        echo json_encode($lastJoinedUsers);
        exit;
}

http_response_code(406);
?>
