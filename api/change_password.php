<?php
include 'auth_check.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE staff SET password = ? WHERE id = ?");
    $stmt->execute([$new_pass, $_SESSION['staff_id']]);
    echo "Password updated!";
}
?>
