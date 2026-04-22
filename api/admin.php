<?php 
include 'db.php'; 

// 1. ADD Product
if (isset($_POST['add_product'])) {
    $sql = "INSERT INTO menu_items (name, price, category, image_url) VALUES (?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([$_POST['name'], $_POST['price'], $_POST['category'], $_POST['image_url']]);
}

// 2. DELETE Product
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin.php");
}

// 3. UPDATE Price (Quick Edit)
if (isset($_POST['update_price'])) {
    $stmt = $pdo->prepare("UPDATE menu_items SET price = ? WHERE id = ?");
    $stmt->execute([$_POST['new_price'], $_POST['product_id']]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>McExpress Admin | Inventory</title>
    <link rel="stylesheet" href="/../admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <header>
            <h1><i class="fas fa-tools"></i> Menu Management</h1>
            <a href="index.php" target="_blank" class="view-site">View Storefront</a>
        </header>

        <section class="admin-section">
            <h2>Add New Item</h2>
            <form method="POST" class="add-form">
                <input type="text" name="name" placeholder="Item Name" required>
                <input type="number" step="0.01" name="price" placeholder="Price" required>
                <input type="text" name="category" placeholder="Category (e.g., Burgers)">
                <input type="text" name="image_url" placeholder="Image URL">
                <button type="submit" name="add_product" class="btn-add">Add to Database</button>
            </form>
        </section>

        <section class="admin-section">
            <h2>Current Menu Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items = $pdo->query("SELECT * FROM menu_items ORDER BY category ASC")->fetchAll();
                    foreach($items as $i): ?>
                        <tr>
                            <td><img src="<?= $i['image_url'] ?>" width="50" style="border-radius:5px"></td>
                            <td><?= $i['name'] ?></td>
                            <td><?= $i['category'] ?></td>
                            <td>
                                <form method="POST" style="display:inline-flex; gap:5px;">
                                    <input type="hidden" name="product_id" value="<?= $i['id'] ?>">
                                    $<input type="number" step="0.01" name="new_price" value="<?= $i['price'] ?>" style="width:60px">
                                    <button type="submit" name="update_price" class="btn-save"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                            <td>
                                <a href="admin.php?delete=<?= $i['id'] ?>" class="btn-delete" onclick="return confirm('Delete this item?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
