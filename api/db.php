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
        PDO::MYSQL_ATTR_SSL_CA => null,
        1014 => false // Equivalent to PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT
    ];
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Connection error.");
}
