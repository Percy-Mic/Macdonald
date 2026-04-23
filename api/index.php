<?php
include 'db.php'; // Database connection

// Fetch products from the database
try {
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category ASC");
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    // In a production environment, log this instead of echoing
    die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Order Now</title>
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header class="navbar">
    <div class="logo">Mc<span>Express</span></div>
    <div class="cart-icon" onclick="toggleCart()">
        <i class="fas fa-shopping-basket"></i>
        <span id="cart-count">0</span>
    </div>
</header>

<main class="container">
    <section class="menu-grid">
        <?php foreach ($items as $item): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                <div class="card-info">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
                    <p class="price">₱<?php echo number_format($item['price'], 2); ?></p>
                    <button class="add-btn" onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', <?php echo $item['price']; ?>)">
                        Add to Cart
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <aside id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2>Your Order</h2>
            <button class="close-btn" onclick="toggleCart()">&times;</button>
        </div>
        
        <div id="cart-items-list">
            </div>

        <div class="checkout-section">
            <h3>Delivery Details</h3>
            <input type="text" id="cust_name" placeholder="Full Name" required>
            <input type="text" id="cust_phone" placeholder="Phone Number" required>
            <textarea id="cust_address" placeholder="Complete Delivery Address" required></textarea>
            
            <div class="total-bar">
                <span>Total:</span>
                <span id="cart-total-display">₱0.00</span>
            </div>
            
            <button class="confirm-btn" onclick="placeOrder()">Confirm Order</button>
        </div>
    </aside>
</main>

<script src="/scripts/app.js"></script>
</body>
</html>
