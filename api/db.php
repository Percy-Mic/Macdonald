<?php
$host = "mysql-62ea972-percymicnono-12e0.g.aivencloud.com";
$port = "18500"; 
$dbname = "defaultdb";
$user = "avnadmin";
$password = getenv('db_Pass');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
