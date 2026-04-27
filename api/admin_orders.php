<?php
// 1. ABSOLUTE FIRST LINE
require_once __DIR__ . '/auth_check.php'; 
require_once __DIR__ . '/db.php';

// 2. Fetch data from Aiven
try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --pending: #f39c12;
            --preparing: #3498db;
            --delivered: #27ae60;
            --bg-light: #f4f7f6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: var(--bg-light);
            margin: 0;
            display: flex;
        }

        .admin-main {
            flex-grow: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .admin-header h1 { margin: 0; font-size: 1.5rem; color: #333; }

        .btn-refresh {
            background: var(--mcd-yellow);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-refresh:hover { background: #eab106; transform: rotate(15deg); }

        /* Order Table */
        .order-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #eee;
            color: #777;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid #f9f9f9;
            vertical-align: top;
        }

        /* Order Items List */
        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .item-list li {
            background: #fff9e6;
            margin-bottom: 4px;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.85rem;
            border-left: 3px solid var(--mcd-yellow);
        }

        /* Status Dropdown */
        .status-select {
            padding: 8px;
            border-radius: 6px;
            border: 2px solid #eee;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
        }

        /* Logic for colored borders based on status */
        .status-Pending { border-color: var(--pending); color: var(--pending); }
        .status-Preparing { border-color: var(--preparing); color: var(--preparing); }
        .status-Delivered { border-color: var(--delivered); color: var(--delivered); }

        .no-orders {
            text-align: center;
            padding: 100px 0;
            color: #ccc;
        }

        .customer-info strong { color: var(--mcd-red); }
        .total-amount { font-size: 1.1rem; font-weight: 800; color: #333; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-utensils" style="color: var(--mcd-red);"></i> Live Order Monitor</h1>
            <button onclick="location.reload()" class="btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh Feed
            </button>
        </header>

        <section class="order-container">
            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <i class="fas fa-receipt fa-4x" style="margin-bottom: 20px;"></i>
                    <p>No orders yet. They will appear here when customers check out.</p>
                </div>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Arrival Time</th>
                            <th>Customer & Address</th>
                            <th>Order Details</th>
                            <th>Total</th>
                            <th>Order Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><strong>#<?php echo $order['id']; ?></strong></td>
                            <td><i class="far fa-clock"></i> <?php echo date('h:i A', strtotime($order['created_at'])); ?></td>
                            <td class="customer-info">
                                <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                                <small style="color: #666;"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($order['address']); ?></small>
                            </td>
                            <td>
                                <ul class="item-list">
                                    <?php 
                                    $items = json_decode($order['items'], true);
                                    if (is_array($items)) {
                                        foreach ($items as $i) {
                                            echo "<li><strong>" . $i['quantity'] . "x</strong> " . htmlspecialchars($i['name']) . "</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </td>
                            <td class="total-amount">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <select class="status-select status-<?php echo $order['status']; ?>" onchange="updateStatus(<?php echo $order['id']; ?>, this.value)">
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

    <script>
        function updateStatus(id, newStatus) {
            // Flash effect for UX
            fetch('api/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&status=${newStatus}`
            })
            .then(res => {
                if(res.ok) {
                    alert("Order #" + id + " updated to " + newStatus);
                    location.reload(); // Refresh to update colors
                }
            });
        }
    </script>
</body>
</html>
