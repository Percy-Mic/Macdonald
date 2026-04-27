<?php 
// 1. Security Check
include 'auth_check.php'; 
include 'db.php';

$message = "";

// 2. Handle adding new staff members
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_staff'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $role]);
        $message = "success|Staff account created for: " . htmlspecialchars($username);
    } catch (PDOException $e) {
        $message = "error|Error: Username already exists.";
    }
}

// 3. Fetch all staff members
$staff_list = $pdo->query("SELECT id, username, role, created_at FROM staff ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management | McExpress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --dark-gray: #343a40;
            --light-gray: #f4f6f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
            margin: 0;
            display: flex;
        }

        /* Container Layout */
        .admin-main {
            flex-grow: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        /* Alert Styling */
        .alert {
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }

        /* Card Panels */
        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .admin-card h2 {
            margin-top: 0;
            font-size: 1.25rem;
            color: var(--dark-gray);
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }

        /* Horizontal Form */
        .horizontal-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .horizontal-form input, .horizontal-form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            flex: 1;
            min-width: 150px;
        }

        .btn-primary {
            background: var(--mcd-red);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary:hover { background: #a00014; }

        /* Table Styling */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            background: var(--light-gray);
            padding: 12px;
            color: #666;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .admin-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
        }

        /* Badges */
        .role-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .role-badge.admin { background: #e3f2fd; color: #0d47a1; }
        .role-badge.staff { background: #fff3e0; color: #e65100; }

        .btn-delete {
            background: none;
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-users-cog" style="color: var(--mcd-red);"></i> Staff Management</h1>
            
            <?php if ($message): 
                list($type, $text) = explode('|', $message); ?>
                <div class="alert alert-<?php echo $type; ?>">
                    <i class="fas <?php echo ($type == 'success') ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $text; ?>
                </div>
            <?php endif; ?>
        </header>

        <section class="admin-card">
            <h2><i class="fas fa-plus-circle"></i> Add New Staff Member</h2>
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
            <h2><i class="fas fa-list"></i> Active Team Members</h2>
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
                        <td style="color: #999;">#<?php echo $person['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($person['username']); ?></strong></td>
                        <td>
                            <span class="role-badge <?php echo strtolower($person['role']); ?>">
                                <?php echo $person['role']; ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($person['created_at'])); ?></td>
                        <td>
                            <?php 
                            // Only show delete button if it's not the currently logged in user
                            // Assumes you stored username in session during login
                            if ($person['username'] !== ($_SESSION['username'] ?? '')): ?>
                                <button onclick="deleteStaff(<?php echo $person['id']; ?>)" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            <?php else: ?>
                                <span style="color: #bbb; font-style: italic;">(You)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        function deleteStaff(id) {
            if(confirm("Are you sure you want to remove this staff member? This action cannot be undone.")) {
                // You would typically redirect to a delete script here
                window.location.href = "delete_staff.php?id=" + id;
            }
        }
    </script>
</body>
</html>
