<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ?");
    $stmt->execute([$user]);
    $staff = $stmt->fetch();

    if ($staff && password_verify($pass, $staff['password'])) {
        $_SESSION['staff_id'] = $staff['id'];
        $_SESSION['role'] = $staff['role'];
        header("Location: /admin/dashboard");
    } else {
        $error = "Invalid credentials!";
    }
}
?>
