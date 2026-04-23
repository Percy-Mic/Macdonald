<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Order Now</title>
    <link rel="stylesheet" href="/styles/main.css">
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
            <div id="cart-section" class="cart-container">
                <h2>Your Order</h2>
                <div id="cart-items"></div> <hr>
    
                <div id="checkout-form">
                    <h3>Delivery Details</h3>
                    <input type="text" id="cust_name" placeholder="Full Name" required>
                    <input type="text" id="cust_phone" placeholder="Phone Number" required>
                    <textarea id="cust_address" placeholder="Complete Address" required></textarea>
                    <div class="total-display">Total: ₱<span id="cart-total">0.00</span></div>
                    <button onclick="placeOrder()" class="place-order-btn">Confirm Order</button>
                </div>
            </div>
        </div>
    </aside>

    <script src="/scripts/app.js"></script>
</body>
</html>
