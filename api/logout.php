<?php
// 1. Start the session to gain access to it
session_start();

// 2. Unset all session variables
$_SESSION = array();

// 3. If it's desired to kill the session, also delete the session cookie.
// This is a professional security standard.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session on the server
session_destroy();

// 5. Redirect the user to the login page
// We use /admin/login because that matches your vercel.json route
header("Location: /admin/login");
exit();
?>
