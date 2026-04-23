<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If the session variable isn't set, kick them out
if (!isset($_SESSION['staff_id'])) {
    // Check if we are already on the login page to avoid the loop
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header("Location: /admin/login");
        exit();
    }
}
