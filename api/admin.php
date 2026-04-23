<?php
include 'db.php'; 
include 'auth_check.php';

$message = "";

// Handle adding new menu items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    try {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $image_url]);
        $message = "Item added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
// Fetch all orders, newest first
$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
// Fetch current items
$items = $pdo->query("SELECT * FROM menu_items ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>McExpress Admin</title>
    <link rel="stylesheet" href="/styles/admin.css">
</head>
<body>
    <h1>McExpress Management</h1>
    
    <?php if ($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>

    <section>
        <h2>Add New Item</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Item Name" required>
            <select name="category">
                <option value="Burgers">Burgers</option>
                <option value="Chicken">Chicken</option>
                <option value="Drinks">Drinks</option>
                <option value="Desserts">Desserts</option>
            </select>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="image_url" placeholder="Image URL">
            <button type="submit" name="add_item">Add to Menu</button>
        </form>
    </section>

    <hr>

    <section>
        <h2>Current Menu</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                             alt="item" width="50" 
                             style="border-radius: 5px;">
                    </td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td>
                        ₱<input type="number" 
                                step="0.01" 
                                class="price-input" 
                                id="price-<?php echo $item['id']; ?>" 
                                value="<?php echo $item['price']; ?>" 
                                style="width: 80px;">
                    </td>
                    <td>
                        <button onclick="updatePrice(<?php echo $item['id']; ?>)">Save</button>
                        <button onclick="deleteProduct(<?php echo $item['id']; ?>)" style="color:red">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="admin-orders">
        <h2>Incoming Orders</h2>
        <table border="1" class="order-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Customer</th>
                    <th>Details</th>
                    <th>Order Items</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo date('M d, h:i A', strtotime($order['created_at'])); ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong><br>
                        <small><?php echo htmlspecialchars($order['phone']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td>
                        <ul class="order-item-list">
                            <?php 
                            $items = json_decode($order['items'], true);
                            foreach ($items as $item): 
                            ?>
                                <li><?php echo $item['name']; ?> (x<?php echo $item['quantity']; ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <select onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)" 
                            class="status-select <?php echo strtolower($order['status']); ?>">
                            <option value="Pending" <?php if($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Preparing" <?php if($order['status'] == 'Preparing') echo 'selected'; ?>>Preparing</option>
                            <option value="Delivered" <?php if($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script src="/scripts/app.js"></script>
</body>
</html>
