<?php
/**
 * 1. PRE-OUTPUT LOGIC
 * No spaces, HTML, or BOM characters should exist before this opening tag.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

/**
 * 2. REDIRECT LOOP PREVENTION
 * If the user already has a session, push them to the dashboard immediately.
 */
if (isset($_SESSION['staff_id'])) {
    header("Location: /admin/orders");
    exit();
}

$error = "";

/**
 * 3. AUTHENTICATION LOGIC
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = trim($_POST['username'] ?? '');
    $pass_input = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM staff WHERE username = ?");
        $stmt->execute([$user_input]);
        $user = $stmt->fetch();

        // Verify password against the hash in the database
        if ($user && password_verify($pass_input, $user['password'])) {
            
            // Security: Prevent session fixation
            session_regenerate_id(true);
            
            $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect using the clean route defined in vercel.json
            header("Location: /admin/orders");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        $error = "A system error occurred. Please try again later.";
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f8f9fa; 
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; margin: 0; 
        }
        .login-card { 
            background: #fff; padding: 40px; border-radius: 12px; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); 
            width: 100%; max-width: 380px; text-align: center;
        }
        .brand { font-size: 2.2rem; font-weight: 800; color: var(--mcd-red); margin-bottom: 5px; }
        .brand span { color: var(--mcd-yellow); }
        .subtitle { color: #6c757d; margin-bottom: 30px; font-size: 0.9rem; }
        
        .form-group { text-align: left; margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #333; }
        .form-group input { 
            width: 100%; padding: 12px; border: 1px solid #ced4da; 
            border-radius: 6px; box-sizing: border-box; font-size: 1rem;
        }
        .form-group input:focus { outline: none; border-color: var(--mcd-yellow); box-shadow: 0 0 0 3px rgba(255, 188, 13, 0.2); }
        
        .login-btn { 
            background: var(--mcd-yellow); color: #000; border: none; 
            width: 100%; padding: 14px; border-radius: 6px; 
            font-weight: 700; font-size: 1rem; cursor: pointer; 
            transition: all 0.2s ease; margin-top: 10px;
        }
        .login-btn:hover { background: #e5a80b; }
        
        .error-box { 
            background: #f8d7da; color: #842029; padding: 10px; 
            border-radius: 6px; margin-bottom: 20px; font-size: 0.85rem;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .back-home { margin-top: 20px; display: block; color: #6c757d; text-decoration: none; font-size: 0.85rem; }
        .back-home:hover { color: var(--mcd-red); }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand">Mc<span>Express</span></div>
    <div class="subtitle">Staff Management Portal</div>

    <?php if ($error): ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/login">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required placeholder="Enter username" autocomplete="username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter password" autocomplete="current-password">
        </div>
        <button type="submit" class="login-btn">Log In to Dashboard</button>
    </form>
    
    <a href="/" class="back-home">
        <i class="fas fa-arrow-left"></i> Return to Storefront
    </a>
</div>

</body>
</html>
