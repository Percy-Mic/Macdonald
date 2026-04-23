<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is NOT logged in
if (!isset($_SESSION['staff_id'])) {
    // Force redirect to the clean URL route
    header("Location: /admin/login");
    exit();
}
// If they ARE logged in, the script just continues...
?>
