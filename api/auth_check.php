<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the staff_id is NOT in the session
if (!isset($_SESSION['staff_id'])) {
    // Only redirect if the current page isn't already the login page
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header("Location: /admin/login");
        exit();
    }
}
?>
