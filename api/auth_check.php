<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current URL path
$current_uri = $_SERVER['REQUEST_URI'];

// Only redirect if NOT logged in AND NOT already on the login page
if (!isset($_SESSION['staff_id'])) {
    // Check for both the clean route and the physical file name
    if (strpos($current_uri, '/admin/login') === false && strpos($current_uri, 'login.php') === false) {
        header("Location: /admin/login");
        exit();
    }
}
