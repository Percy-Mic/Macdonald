<?php
include_once 'db.php';

if (isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    // Delete from Database
    $stmt = $pdo->prepare("DELETE FROM staff_sessions WHERE token = ?");
    $stmt->execute([$token]);
    // Clear Cookie
    setcookie("auth_token", "", time() - 3600, "/");
}

header("Location: /admin/login");
exit();
