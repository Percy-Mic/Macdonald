<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if NOT logged in
if (!isset($_SESSION['staff_id'])) {
    // We use root-relative paths for Vercel
    header("Location: /admin/login");
    exit();
}
?>
