<?php 
// 1. Protection & Connection
include 'auth_check.php'; 
include 'db.php';

$message = "";
$error = "";

// 2. Handle the Update Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_pass'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $staff_id = $_SESSION['staff_id'];

    // Basic Validation
    if ($new_pass !== $confirm_pass) {
        $error = "New passwords do not match!";
    } elseif (strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Verify current password against database
        $stmt = $pdo->prepare("SELECT password FROM staff WHERE id = ?");
        $stmt->execute([$staff_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_pass, $user['password'])) {
            // Hash the new password
            $new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            
            // Update the database
            $update_stmt = $pdo->prepare("UPDATE staff SET password = ? WHERE id = ?");
            if ($update_stmt->execute([$new_hashed, $staff_id])) {
                $message = "Password updated successfully!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password | McExpress</title>
    <link rel="stylesheet" href="/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-key"></i> Security Settings</h1>
        </header>

        <section class="admin-card" style="max-width: 500px; margin: 20px auto;">
            <h2>Update Password</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-success" style="color: green; background: #e8f5e9; padding: 10px; margin-bottom: 15px;">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error" style="color: red; background: #ffebee; padding: 10px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required placeholder="Enter current password">
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required placeholder="Minimum 6 characters">
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required placeholder="Repeat new password">
                </div>

                <button type="submit" name="update_pass" class="btn-primary" style="width: 100%; margin-top: 20px;">
                    Update Password
                </button>
            </form>
        </section>
    </main>

</body>
</html>
