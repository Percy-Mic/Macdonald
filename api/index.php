<?php
// 1. Connection to MySQL
include 'db.php'; 

// 2. Fetch Menu Items
try {
    // We order by category so Burgers, Chicken, etc., stay grouped
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category ASC, name ASC");
    $menu_items = $stmt->fetchAll();
} catch (PDOException $e) {
    // Silently fail or log for production
    $menu_items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Fresh & Fast</title>
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Mc<span>Express</span></div>
    <div class="cart-trigger" onclick="toggleCart()">
        <i class="fas fa-shopping-cart"></i>
        <span id="cart-count">0</span>
    </div>
</header>

<main class="container">
    <section class="hero">
        <h1>Craving? We Deliver.</h1>
        <p>Order your favorites in just a few clicks.</p>
    </section>

    <section class="menu-grid">
        <?php if (empty($menu_items)): ?>
            <p>No items found in the menu. Check back later!</p>
        <?php else: ?>
            <?php foreach ($menu_items as $item): ?>
                <div class="product-card">
                    <div class="img-container">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="product-details">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="category"><?= htmlspecialchars($item['category']) ?></p>
                        <div class="price-row">
                            <span class="price">₱<?= number_format($item['price'], 2) ?></span>
                            <button class="add-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <aside id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2>Your Tray</h2>
            <button class="close-cart" onclick="toggleCart()">&times;</button>
        </div>

        <div id="cart-items" class="cart-items-body">
            </div>

        <div class="cart-footer">
            <div class="checkout-form">
                <h3>Delivery Info</h3>
                <input type="text" id="cust_name" placeholder="Name" required>
                <input type="tel" id="cust_phone" placeholder="Phone" required>
                <textarea id="cust_address" placeholder="Address" required></textarea>
                
                <div class="total-section">
                    <span>Total:</span>
                    <strong id="cart-total">₱0.00</strong>
                </div>
                
                <button class="checkout-btn" onclick="placeOrder()">
                    Confirm Order
                </button>
            </div>
        </div>
    </aside>
</main>

<script src="scripts/app.js"></script>
</body>
</html>
