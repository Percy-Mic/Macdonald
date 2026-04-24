<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if staff_id is missing AND we are not already on the login page
if (!isset($_SESSION['staff_id'])) {
    // Get the current path to prevent looping on the login page itself
    $current_uri = $_SERVER['REQUEST_URI'];
    if (strpos($current_uri, '/admin/login') === false) {
        header("Location: /admin/login");
        exit();
    }
}
