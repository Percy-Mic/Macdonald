<?php 
include 'auth_check.php'; 
include 'db.php';
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Incoming Orders</title>
    <link rel="stylesheet" href="/styles/admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Live Orders</h1>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <tr style="background:#ffbc0d">
                <th>Customer</th><th>Address</th><th>Order Details</th><th>Total</th><th>Status</th>
            </tr>
            <?php foreach($orders as $o): ?>
            <tr>
                <td><strong><?= htmlspecialchars($o['customer_name']) ?></strong><br><?= $o['phone'] ?></td>
                <td><?= htmlspecialchars($o['address']) ?></td>
                <td>
                    <?php 
                    $cart = json_decode($o['items'], true);
                    foreach($cart as $item) echo "• {$item['name']} (x{$item['quantity']})<br>";
                    ?>
                </td>
                <td>₱<?= number_format($o['total_amount'], 2) ?></td>
                <td>
                    <select onchange="updateOrderStatus(<?= $o['id'] ?>, this.value)">
                        <option value="Pending" <?= $o['status']=='Pending'?'selected':'' ?>>Pending</option>
                        <option value="Preparing" <?= $o['status']=='Preparing'?'selected':'' ?>>Preparing</option>
                        <option value="Delivered" <?= $o['status']=='Delivered'?'selected':'' ?>>Delivered</option>
                    </select>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script src="/scripts/app.js"></script>
</body>
</html>
