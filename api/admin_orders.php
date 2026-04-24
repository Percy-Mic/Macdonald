<?php
// 1. ABSOLUTE FIRST LINE: No spaces or lines before this.
require_once __DIR__ . '/auth_check.php'; 
require_once __DIR__ . '/db.php';

// 2. Fetch data from MySQL
try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    // If the table doesn't exist yet, we handle it gracefully
    error_log($e->getMessage());
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Orders | McExpress Admin</title>
    <link rel="stylesheet" href="/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-truck-loading"></i> Incoming Orders</h1>
            <button onclick="location.reload()" class="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </header>

        <section class="order-container">
            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <i class="fas fa-receipt fa-3x"></i>
                    <p>No orders yet. They will appear here when customers check out.</p>
                </div>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('h:i A', strtotime($order['created_at'])); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                <small><?php echo htmlspecialchars($order['address']); ?></small>
                            </td>
                            <td>
                                <ul class="item-list">
                                    <?php 
                                    $items = json_decode($order['items'], true);
                                    if (is_array($items)) {
                                        foreach ($items as $i) {
                                            echo "<li>" . htmlspecialchars($i['name']) . " x" . $i['quantity'] . "</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>
                            <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <select class="status-select" onchange="updateStatus(<?php echo $order['id']; ?>, this.value)">
                                    <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Preparing" <?php echo $order['status'] == 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                    <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </main>

    <script src="/scripts/app.js"></script>
</body>
</html>
