<?php 
// 1. Security Check (Only Logged-in Staff can see this)
include 'auth_check.php'; 
include 'db.php';

$message = "";

// 2. Handle adding new staff members
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Securely hash the password before saving to PostgreSQL
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $role]);
        $message = "Staff account created for: " . htmlspecialchars($username);
    } catch (PDOException $e) {
        $message = "Error: Username might already exist.";
    }
}

// 3. Fetch all staff members
$staff_list = $pdo->query("SELECT id, username, role, created_at FROM staff ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management | McExpress</title>
    <link rel="stylesheet" href="/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-users-cog"></i> Staff Management</h1>
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>
        </header>

        <section class="admin-card">
            <h2>Add New Staff Member</h2>
            <form method="POST" class="horizontal-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role">
                    <option value="Staff">Staff</option>
                    <option value="Admin">Admin</option>
                </select>
                <button type="submit" name="add_staff" class="btn-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
        </section>

        <section class="admin-card">
            <h2>Active Team Members</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff_list as $person): ?>
                    <tr>
                        <td>#<?php echo $person['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($person['username']); ?></strong></td>
                        <td>
                            <span class="role-badge <?php echo strtolower($person['role']); ?>">
                                <?php echo $person['role']; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($person['created_at'])); ?></td>
                        <td>
                            <?php if ($person['username'] !== $_SESSION['staff_username']): ?>
                                <button onclick="deleteStaff(<?php echo $person['id']; ?>)" class="btn-delete">
                                    <i class="fas fa-user-minus"></i> Remove
                                </button>
                            <?php else: ?>
                                <small>(You)</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="/scripts/app.js"></script>
</body>
</html>
