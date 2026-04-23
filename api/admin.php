<?php 
// 1. Security First: Protect the page
include 'auth_check.php'; 
include 'db.php';

$message = "";

// 2. Handle adding new products
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    try {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $image_url]);
        $message = "Successfully added: " . htmlspecialchars($name);
    } catch (PDOException $e) {
        $message = "Database Error: " . $e->getMessage();
    }
}

// 3. Fetch all products to show in the table
$items = $pdo->query("SELECT * FROM menu_items ORDER BY category, name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management | McExpress</title>
    <link rel="stylesheet" href="/styles/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-hamburger"></i> Menu Management</h1>
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>
        </header>

        <section class="admin-card">
            <h2>Add New Menu Item</h2>
            <form method="POST" class="horizontal-form">
                <input type="text" name="name" placeholder="Item Name (e.g. Big Mac)" required>
                
                <select name="category">
                    <option value="Burgers">Burgers</option>
                    <option value="Chicken">Chicken</option>
                    <option value="Drinks">Drinks</option>
                    <option value="Desserts">Desserts</option>
                </select>

                <input type="number" step="0.01" name="price" placeholder="Price (₱)" required>
                <input type="text" name="image_url" placeholder="Image URL (Direct link)">
                
                <button type="submit" name="add_item" class="btn-primary">
                    <i class="fas fa-plus"></i> Add to Menu
                </button>
            </form>
        </section>

        <section class="admin-card">
            <h2>Current Menu</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr id="row-<?php echo $item['id']; ?>">
                            <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="thumb"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><span class="badge"><?php echo $item['category']; ?></span></td>
                            <td>
                                ₱<input type="number" step="0.01" 
                                       value="<?php echo $item['price']; ?>" 
                                       class="price-edit-input" 
                                       id="price-input-<?php echo $item['id']; ?>">
                            </td>
                            <td>
                                <button onclick="updatePrice(<?php echo $item['id']; ?>)" class="btn-save" title="Save Price">
                                    <i class="fas fa-save"></i>
                                </button>
                                <button onclick="deleteProduct(<?php echo $item['id']; ?>)" class="btn-delete" title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="/scripts/app.js"></script>
</body>
</html>
