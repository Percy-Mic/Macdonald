<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Get the current URL path
$current_uri = $_SERVER['REQUEST_URI'];

// 2. ONLY redirect if the user is NOT logged in AND is NOT already on the login page
if (!isset($_SESSION['staff_id'])) {
    // Check if the current URL contains 'login'
    if (strpos($current_uri, '/admin/login') === false && strpos($current_uri, 'login.php') === false) {
        header("Location: /admin/login");
        exit();
    }
}
