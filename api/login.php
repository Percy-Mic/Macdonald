<?php
session_start();
include 'db.php'; // MySQL connection

// Redirect if already logged in
if (isset($_SESSION['staff_id'])) {
    header("Location: /admin/orders");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    try {
        // Query the MySQL staff table
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ?");
        $stmt->execute([$user_input]);
        $user = $stmt->fetch();

        // Use password_verify to check the MySQL-stored hash
        if ($user && password_verify($pass_input, $user['password'])) {
            // Regeneration protects against session fixation
            session_regenerate_id();
            
            $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: /admin/orders");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress Staff Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --mcd-red: #db0007; --mcd-yellow: #ffbc0d; }
        body { 
            font-family: 'Segoe UI', Tahoma, sans-serif; 
            background: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-box { 
            background: #fff; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 380px; 
            text-align: center;
        }
        .logo { font-size: 2rem; font-weight: 800; color: var(--mcd-red); margin-bottom: 10px; }
        .logo span { color: var(--mcd-yellow); }
        
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
        .form-group input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 1rem;
        }
        
        .btn-login { 
            background: var(--mcd-yellow); 
            color: #222; 
            border: none; 
            width: 100%; 
            padding: 14px; 
            border-radius: 6px; 
            font-weight: bold; 
            font-size: 1rem; 
            cursor: pointer; 
            transition: 0.2s; 
        }
        .btn-login:hover { background: #e5a80b; }
        
        .error-msg { 
            background: #ffebee; 
            color: #c62828; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 20px; 
            font-size: 0.9rem; 
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">Mc<span>Express</span></div>
    <p style="color: #666; margin-bottom: 30px;">Admin Portal Login</p>

    <?php if ($error): ?>
        <div class="error-msg"><i class="fas fa-times-circle"></i> <?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Enter username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>
        <button type="submit" class="btn-login">Login to Dashboard</button>
    </form>
    
    <p style="margin-top: 25px;">
        <a href="/" style="color: #888; text-decoration: none; font-size: 0.85rem;">
            <i class="fas fa-arrow-left"></i> Back to Store
        </a>
    </p>
</div>

</body>
</html>
