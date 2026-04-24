<?php

$host = "mysql-62ea972-percymicnono-12e0.g.aivencloud.com";
$port = "18500"; 
$dbname = "defaultdb";
$user = "avnadmin";
$password = getenv('dbPass');

$options = [
    // This is what you mentioned - it tells PHP to use SSL even if we don't provide a file
    PDO::MYSQL_ATTR_SSL_CA => null, 
    
    // Forcing the mode to 'REQUIRED' ensures the connection is encrypted
    // This is the most modern way to handle it in PHP 7.4+
    PDO::MYSQL_ATTR_SSL_MODE => PDO::MYSQL_ATTR_SSL_MODE_REQUIRED,
    
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    // This will now catch SSL handshake failures specifically
    error_log("Database Error: " . $e->getMessage());
    die("Connection failed. Please check your network or SSL settings.");
}
?>
