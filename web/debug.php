<?php
header('Content-Type: text/plain');

echo "=== NETWORK DEBUG ===\n\n";

// 1. Variables de entorno
echo "DATABASE_HOST env: " . ($_ENV['DATABASE_HOST'] ?? getenv('DATABASE_HOST') ?? 'NOT SET') . "\n";
echo "DATABASE_NAME env: " . ($_ENV['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?? 'NOT SET') . "\n";
echo "DATABASE_USER env: " . ($_ENV['DATABASE_USER'] ?? getenv('DATABASE_USER') ?? 'NOT SET') . "\n";
echo "DATABASE_PASSWORD set: " . (empty($_ENV['DATABASE_PASSWORD']) && empty(getenv('DATABASE_PASSWORD')) ? 'NO' : 'YES') . "\n\n";

// 2. DNS Resolution
$host = $_ENV['DATABASE_HOST'] ?? getenv('DATABASE_HOST') ?? 'lab2-tf-database-service.database-name-space';
echo "=== DNS LOOKUP ===\n";
echo "Trying to resolve: $host\n";
$ip = gethostbyname($host);
if ($ip === $host) {
    echo "ERROR: DNS resolution FAILED\n";
} else {
    echo "SUCCESS: Resolved to IP: $ip\n";
}
echo "\n";

// 3. TCP Connection test
echo "=== TCP CONNECTION TEST ===\n";
echo "Trying to connect to $ip:3306\n";
$fp = @fsockopen($ip, 3306, $errno, $errstr, 5);
if (!$fp) {
    echo "ERROR: Cannot connect - $errstr ($errno)\n";
} else {
    echo "SUCCESS: TCP connection established\n";
    fclose($fp);
}
echo "\n";

// 4. MySQL PDO Connection test
echo "=== MYSQL PDO CONNECTION TEST ===\n";
try {
    $dbname = $_ENV['DATABASE_NAME'] ?? getenv('DATABASE_NAME') ?? 'app_db';
    $user = $_ENV['DATABASE_USER'] ?? getenv('DATABASE_USER') ?? 'root';
    $password = $_ENV['DATABASE_PASSWORD'] ?? getenv('DATABASE_PASSWORD') ?? 'password';
    
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_TIMEOUT => 5]
    );
    echo "SUCCESS: MySQL connection established\n";
    echo "MySQL version: " . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>