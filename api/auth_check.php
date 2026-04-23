<?php
// Ensure session is started before checking variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the ID is missing
if (!isset($_SESSION['staff_id'])) {
    // Redirect ONLY if we aren't already on the login page
    // Using root-relative path
    header("Location: /admin/login");
    exit();
}
?>
