<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if staff is NOT logged in
if (!isset($_SESSION['staff_id'])) {
    // Only redirect if we are NOT already on the login page
    // Using strpos to check the URL path
    if (strpos($_SERVER['REQUEST_URI'], '/admin/login') === false) {
        header("Location: /admin/login");
        exit();
    }
}
