<?php 
// 1. Security First
include 'auth_check.php'; 
include 'db.php';

$message = "";

// 2. Handle adding new products
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    try {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $image_url]);
        $message = "success|Successfully added: " . htmlspecialchars($name);
    } catch (PDOException $e) {
        $message = "error|Database Error: " . $e->getMessage();
    }
}

// 3. Fetch all products
$items = $pdo->query("SELECT * FROM menu_items ORDER BY category, name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management | McExpress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --bg-gray: #f4f6f9;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-gray);
            margin: 0;
            display: flex;
        }

        .admin-main {
            flex-grow: 1;
            padding: 2rem;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Header & Alerts */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        .alert-success { background: #d4edda; color: #155724; border-left: 5px solid #28a745; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; }

        /* Cards */
        .admin-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .admin-card h2 {
            margin-top: 0;
            font-size: 1.2rem;
            color: #333;
            border-bottom: 2px solid var(--bg-gray);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        /* Form */
        .horizontal-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .horizontal-form input, .horizontal-form select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            flex: 1;
            min-width: 180px;
        }

        .btn-primary {
            background: var(--mcd-red);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover { background: #a00014; }

        /* Table Design */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            text-align: left;
            background: #f8f9fa;
            padding: 15px;
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
        }

        .admin-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        .thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .category-badge {
            background: #e9ecef;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #495057;
        }

        /* Inline Edit Styling */
        .price-edit-input {
            width: 80px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-weight: bold;
            color: var(--mcd-red);
        }

        .btn-save { color: #28a745; background: none; border: none; font-size: 1.1rem; cursor: pointer; margin-right: 10px; }
        .btn-delete { color: #dc3545; background: none; border: none; font-size: 1.1rem; cursor: pointer; }
        
        .btn-save:hover, .btn-delete:hover { transform: scale(1.2); }

    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-hamburger" style="color: var(--mcd-red);"></i> Menu Management</h1>
            
            <?php if ($message): 
                list($type, $text) = explode('|', $message); ?>
                <div class="alert alert-<?php echo $type; ?>">
                    <?php echo $text; ?>
                </div>
            <?php endif; ?>
        </header>

        <section class="admin-card">
            <h2><i class="fas fa-plus-circle"></i> Add New Menu Item</h2>
            <form method="POST" class="horizontal-form">
                <input type="text" name="name" placeholder="Item Name (e.g. McDouble)" required>
                
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
            <h2><i class="fas fa-list"></i> Current Menu Items</h2>
            <div style="overflow-x: auto;">
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
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image_url'] ?: '/img/no-food.png'); ?>" 
                                     class="thumb" 
                                     onerror="this.src='https://via.placeholder.com/60?text=Food'">
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                            <td><span class="category-badge"><?php echo $item['category']; ?></span></td>
                            <td>
                                ₱<input type="number" step="0.01" 
                                       value="<?php echo $item['price']; ?>" 
                                       class="price-edit-input" 
                                       id="price-input-<?php echo $item['id']; ?>">
                            </td>
                            <td>
                                <button onclick="updatePrice(<?php echo $item['id']; ?>)" class="btn-save" title="Save Price">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button onclick="deleteProduct(<?php echo $item['id']; ?>)" class="btn-delete" title="Delete Product">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        function updatePrice(id) {
            const newPrice = document.getElementById('price-input-' + id).value;
            // You'll need an api/update_price.php to handle this fetch call
            fetch('api/update_price.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&price=${newPrice}`
            })
            .then(res => res.text())
            .then(data => alert("Price updated!"));
        }

        function deleteProduct(id) {
            if(confirm("Remove this item from the menu?")) {
                window.location.href = "api/delete_item.php?id=" + id;
            }
        }
    </script>
</body>
</html>
