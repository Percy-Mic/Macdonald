<?php
include_once 'db.php';

$token = $_COOKIE['auth_token'] ?? null;
$staff_id = null;

if ($token) {
    $stmt = $pdo->prepare("SELECT staff_id FROM staff_sessions WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $session = $stmt->fetch();

    if ($session) {
        $staff_id = $session['staff_id'];
    }
}

// Redirect if not logged in, unless already on the login page
$current_uri = $_SERVER['REQUEST_URI'];
if (!$staff_id && strpos($current_uri, '/admin/login') === false) {
    header("Location: /admin/login");
    exit();
}
