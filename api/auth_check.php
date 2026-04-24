<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_uri = $_SERVER['REQUEST_URI'];

// BREAK THE LOOP: If we are not logged in AND not already on the login page, redirect.
if (!isset($_SESSION['staff_id'])) {
    if (strpos($current_uri, '/admin/login') === false) {
        header("Location: /admin/login");
        exit();
    }
}
