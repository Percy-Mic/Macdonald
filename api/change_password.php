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
    $staff_id = $_SESSION['staff_id']; // This comes from your new auth_check.php logic

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Settings | McExpress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --bg-gray: #f4f6f9;
            --text-dark: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-gray);
            margin: 0;
            display: flex;
        }

        .admin-main {
            flex-grow: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .admin-header {
            width: 100%;
            max-width: 500px;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .admin-header h1 {
            font-size: 1.8rem;
            color: var(--text-dark);
            margin: 0;
        }

        .admin-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }

        .admin-card h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Alerts */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .alert-error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--mcd-yellow);
        }

        .btn-primary {
            background-color: var(--mcd-red);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #a00014;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .security-tip {
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-shield-alt" style="color: var(--mcd-red);"></i> Security Settings</h1>
        </header>

        <section class="admin-card">
            <h2><i class="fas fa-lock"></i> Update Password</h2>
            
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required placeholder="At least 6 characters">
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required placeholder="Repeat new password">
                </div>

                <button type="submit" name="update_pass" class="btn-primary">
                    <i class="fas fa-key"></i> Save New Password
                </button>
            </form>

            <div class="security-tip">
                <i class="fas fa-info-circle"></i> 
                For better security, use a mix of letters, numbers, and symbols.
            </div>
        </section>
    </main>

</body>
</html>
