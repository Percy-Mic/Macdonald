<?php include 'api/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Order Now</title>
    <link rel="stylesheet" href="styles/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div id="notification-container" class="notification-area"></div>

    <nav class="navbar">
        <h1 class="logo">Mc<span>Express</span></h1>
        <button onclick="toggleCart()" class="cart-trigger">
            <i class="fas fa-shopping-basket"></i>
            <span id="cart-count">0</span>
        </button>
    </nav>

    <main class="grid-container">
        <?php
        $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY id DESC");
        while ($row = $stmt->fetch()) {
            echo "
            <div class='card'>
                <img src='{$row['image_url']}' alt='{$row['name']}'>
                <div class='card-info'>
                    <h3>{$row['name']}</h3>
                    <p class='category'>{$row['category']}</p>
                    <p class='price'>\${$row['price']}</p>
                    <button onclick=\"addToCart({$row['id']}, '{$row['name']}', {$row['price']})\">Add to Cart</button>
                </div>
            </div>";
        }
        ?>
    </main>

    <aside id="cart-sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>Your Order</h3>
            <button onclick="toggleCart()">&times;</button>
        </div>
        <div id="cart-items" class="cart-body"></div>
        <div class="sidebar-footer">
            <div class="total-row">Total: <span id="cart-total">$0.00</span></div>
            <button class="checkout-btn" onclick="processOrder()">Place Order</button>
        </div>
    </aside>

    <script src="scripts/app.js"></script>
</body>
</html>
