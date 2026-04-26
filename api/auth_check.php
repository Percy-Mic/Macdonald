<?php
include_once 'db.php';

$token = $_COOKIE['auth_token'] ?? null;
$is_logged_in = false;

if ($token) {
    // Check if the token exists in Aiven and hasn't expired
    $stmt = $pdo->prepare("SELECT staff_id FROM staff_sessions WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $session = $stmt->fetch();
    
    if ($session) {
        $is_logged_in = true;
        $staff_id = $session['staff_id'];
    }
}

// Redirect logic
$current_page = basename($_SERVER['PHP_SELF']);
if (!$is_logged_in && $current_page !== 'login.php') {
    header("Location: /admin/login");
    exit();
}
