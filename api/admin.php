<?php 
include 'auth_check.php'; 
include 'db.php';

$msg = "";
if (isset($_POST['add_item'])) {
    $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, price, image_url) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$_POST['name'], $_POST['category'], $_POST['price'], $_POST['image_url']])) {
        $msg = "Product added!";
    }
}

$items = $pdo->query("SELECT * FROM menu_items ORDER BY category, name")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="/styles/admin.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>Menu Management</h1>
        <?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
        
        <form method="POST" class="admin-form">
            <input type="text" name="name" placeholder="Item Name" required>
            <select name="category">
                <option value="Burgers">Burgers</option>
                <option value="Chicken">Chicken</option>
                <option value="Drinks">Drinks</option>
                <option value="Desserts">Desserts</option>
            </select>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="image_url" placeholder="Image URL">
            <button type="submit" name="add_item">Add Item</button>
        </form>

        <table>
            <tr><th>Img</th><th>Name</th><th>Category</th><th>Price</th><th>Action</th></tr>
            <?php foreach($items as $i): ?>
            <tr>
                <td><img src="<?= $i['image_url'] ?>" width="40"></td>
                <td><?= $i['name'] ?></td>
                <td><?= $i['category'] ?></td>
                <td>₱<?= number_format($i['price'], 2) ?></td>
                <td>
                    <button onclick="deleteProduct(<?= $i['id'] ?>)" style="color:red">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script src="/scripts/app.js"></script>
</body>
</html>
