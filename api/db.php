<?php
$host = "mysql-62ea972-percymicnono-12e0.g.aivencloud.com";
$port = "18500"; 
$dbname = "defaultdb";
$user = "avnadmin";
$password = getenv('dbPass');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Use the CA => null trick since the constant failed
        PDO::MYSQL_ATTR_SSL_CA => null,
        // If the constant above still fails, we use the raw integer for 'REQUIRED' (which is 1)
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false 
    ];

    $pdo = new PDO($dsn, $user, $password, $options);

} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Database connection error. Please check SSL settings.");
}
