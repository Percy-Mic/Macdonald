<?php
//include
include 'auth_check.php';
include 'db.php';

try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Orders | McExpress Admin</title>
    <link rel="stylesheet" href="/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-truck-loading"></i> Incoming Orders</h1>
            <button onclick="location.reload()" class="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh List
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
                            <th>Customer Info</th>
                            <th>Items Ordered</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr class="status-row-<?php echo strtolower($order['status']); ?>">
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('h:i A', strtotime($order['created_at'])); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                <small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($order['phone']); ?></small><br>
                                <small><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($order['address']); ?></small>
                            </td>
                            <td>
                                <ul class="item-summary-list">
                                    <?php 
                                    $cartItems = json_decode($order['items'], true);
                                    if (is_array($cartItems)) {
                                        foreach ($cartItems as $item) {
                                            echo "<li>" . htmlspecialchars($item['name']) . " <span>x" . $item['quantity'] . "</span></li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>
                            <td class="order-price">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <select onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)" 
                                        class="status-dropdown <?php echo strtolower($order['status']); ?>">
                                    <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>🕒 Pending</option>
                                    <option value="Preparing" <?php echo $order['status'] == 'Preparing' ? 'selected' : ''; ?>>🍳 Preparing</option>
                                    <option value="Delivered" <?php echo $order['status'] == 'Delivered' ? 'selected' : ''; ?>>✅ Delivered</option>
                                    <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
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
