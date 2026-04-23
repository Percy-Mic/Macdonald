<?php
// Start the session to access stored login data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is NOT logged in.
 * We check for 'staff_id' because we set this in login.php 
 * upon a successful password match.
 */
if (!isset($_SESSION['staff_id'])) {
    // Redirect to the login page using the clean URL from vercel.json
    header("Location: /admin/login");
    exit(); // Always call exit after a header redirect
}

/**
 * Optional: Helper variables to use across admin pages
 * This makes it easier to display "Welcome, Percy" in your sidebar.
 */
$current_user = $_SESSION['staff_username'] ?? 'Staff';
$current_role = $_SESSION['role'] ?? 'Staff';
?>
