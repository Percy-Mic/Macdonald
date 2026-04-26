<?php
include_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password_input = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password FROM staff WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password_input, $user['password'])) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

        $stmt = $pdo->prepare("INSERT INTO staff_sessions (staff_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expires]);

        setcookie("auth_token", $token, [
            'expires' => time() + (86400 * 30),
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        header("Location: /admin/orders");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Admin Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ffc107; /* McDonald's-style Yellow */
            --secondary-color: #bd0017; /* McDonald's-style Red */
            --bg-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-header i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .login-header h2 {
            margin: 0 0 1.5rem 0;
            color: #333;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background-color: #a00014;
        }

        .error-msg {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <i class="fas fa-utensils"></i>
        <h2>McExpress Admin</h2>
    </div>

    <?php if ($error): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
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
        <button type="submit" class="btn-login">Sign In</button>
    </form>
</div>

</body>
</html>
