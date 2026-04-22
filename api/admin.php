<?php
// Fix: Use the correct path since admin.php is in the root or same folder as db.php
include 'db.php'; 

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

// Fetch current items to display
$items = $pdo->query("SELECT * FROM menu_items ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>McExpress Admin</title>
    <link rel="stylesheet" href="/styles/admin.css"></head>
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
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['category']); ?></td>
                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                <td><button>Delete</button></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>
