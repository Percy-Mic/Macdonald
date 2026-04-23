<?php 
include 'auth_check.php'; 
include 'db.php';

if (isset($_POST['add_staff'])) {
    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['username'], $hashed, $_POST['role']]);
}

$staff = $pdo->query("SELECT id, username, role FROM staff")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Staff Management</title></head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Manage Team</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="New Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role"><option>Staff</option><option>Admin</option></select>
            <button type="submit" name="add_staff">Create Account</button>
        </form>
        <hr>
        <ul>
            <?php foreach($staff as $s): ?>
                <li><?= $s['username'] ?> (<?= $s['role'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
