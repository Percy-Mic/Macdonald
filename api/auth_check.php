<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: /admin-login"); // Use the clean URL from vercel.json
    exit();
}
?>
