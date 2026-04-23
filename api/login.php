<?php
session_start();
include 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ?");
    $stmt->execute([$user]);
    $staff = $stmt->fetch();

    if ($staff && password_verify($pass, $staff['password'])) {
        $_SESSION['staff_id'] = $staff['id'];
        $_SESSION['role'] = $staff['role'];
        header("Location: /admin/products");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<form method="POST">
    <h2>Staff Login</h2>
    <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
