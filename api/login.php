<?php
// 1. Session must be the absolute first thing. 
// No spaces, no HTML, no echoes before this.
session_start();
include 'db.php'; 

// 2. Break the loop: If already logged in, go to orders.
if (isset($_SESSION['staff_id'])) {
    header("Location: /admin/orders");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_input = $_POST['username'] ?? '';
    $pass_input = $_POST['password'] ?? '';

    try {
        // Query the MySQL staff table
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM staff WHERE username = ?");
        $stmt->execute([$user_input]);
        $user = $stmt->fetch();

        // Use password_verify to check against the hash in MySQL
        if ($user && password_verify($pass_input, $user['password'])) {
            
            // Clean up the old session and start a fresh one for security
            session_regenerate_id(true);
            
            $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the clean route defined in vercel.json
            header("Location: /admin/orders");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error = "A system error occurred. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | McExpress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --mcd-red: #db0007; --mcd-yellow: #ffbc0d; }
        body { 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            background: #f4f4f4; 
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; margin: 0; 
        }
        .login-card { 
            background: #fff; padding: 40px; border-radius: 12px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            width: 100%; max-width: 400px; text-align: center;
        }
        .logo { font-size: 2.2rem; font-weight: 800; color: var(--mcd-red); margin-bottom: 5px; }
        .logo span { color: var(--mcd-yellow); }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 0.9rem; }
        
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
        .form-group input { 
            width: 100%; padding: 12px; border: 1px solid #ddd; 
            border-radius: 8px; box-sizing: border-box; font-size: 1rem;
        }
        .form-group input:focus { outline: 2px solid var(--mcd-yellow); border-color: transparent; }
        
        .login-btn { 
            background: var(--mcd-yellow); color: #222; border: none; 
            width: 100%; padding: 14px; border-radius: 8px; 
            font-weight: bold; font-size: 1rem; cursor: pointer; 
            transition: transform 0.2s, background 0.2s; 
        }
        .login-btn:hover { background: #e5a80b; transform: translateY(-1px); }
        
        .error-msg { 
            background: #ffebee; color: #c62828; padding: 12px; 
            border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .back-link { margin-top: 25px; display: block; color: #888; text-decoration: none; font-size: 0.85rem; }
        .back-link:hover { color: var(--mcd-red); }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo">Mc<span>Express</span></div>
    <div class="subtitle">Internal Staff Portal</div>

    <?php if ($error): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/login">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Enter your username" autocomplete="username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter your password" autocomplete="current-password">
        </div>
        <button type="submit" class="login-btn">Log In to Dashboard</button>
    </form>
    
    <a href="/" class="back-link">
        <i class="fas fa-arrow-left"></i> Return to Storefront
    </a>
</div>

</body>
</html>
