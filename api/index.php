<?php
include 'db.php'; 

try {
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category ASC, name ASC");
    $menu_items = $stmt->fetchAll();
} catch (PDOException $e) {
    $menu_items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McExpress | Order Now</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --dark: #292b2c;
            --light: #f8f9fa;
        }

        body { font-family: 'Poppins', sans-serif; background: var(--light); margin: 0; }
        
        /* Navbar */
        .navbar {
            background: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo { font-size: 1.5rem; font-weight: 800; color: var(--mcd-red); }
        .logo span { color: var(--mcd-yellow); }

        .nav-actions { display: flex; gap: 20px; align-items: center; }
        .track-link { text-decoration: none; color: var(--dark); font-weight: 600; font-size: 0.9rem; }

        .cart-trigger { position: relative; cursor: pointer; font-size: 1.2rem; }
        #cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--mcd-red);
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
        }

        /* Hero */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1350');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            padding: 40px 5%;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .product-card:hover { transform: translateY(-5px); }

        .img-container img { width: 100%; height: 200px; object-fit: cover; }
        .product-details { padding: 15px; }
        .category { font-size: 0.8rem; color: #888; text-transform: uppercase; margin: 0; }
        .price-row { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
        .price { font-weight: 800; color: var(--mcd-red); font-size: 1.2rem; }

        .add-btn {
            background: var(--mcd-yellow);
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }
        .add-btn:hover { background: var(--dark); color: white; }

        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 350px;
            height: 100%;
            background: white;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
            transition: 0.4s;
            z-index: 2000;
            display: flex;
            flex-direction: column;
        }
        .cart-sidebar.active { right: 0; }

        .cart-header { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .cart-items-body { flex: 1; overflow-y: auto; padding: 20px; }
        
        /* Individual Cart Item Style */
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f9f9f9;
        }
        .item-info h4 { margin: 0; font-size: 0.9rem; }
        .item-controls { display: flex; align-items: center; gap: 10px; }
        .remove-btn { color: #ccc; cursor: pointer; border: none; background: none; }
        .remove-btn:hover { color: var(--mcd-red); }

        .cart-footer { padding: 20px; background: #fdfdfd; border-top: 1px solid #eee; }
        .checkout-form input, .checkout-form textarea {
            width: 100%; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;
        }
        .checkout-btn {
            width: 100%; padding: 15px; background: var(--mcd-red); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer;
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">Mc<span>Express</span></div>
    <div class="nav-actions">
        <a href="track_order.php" class="track-link"><i class="fas fa-map-marker-alt"></i> Track Order</a>
        <div class="cart-trigger" onclick="toggleCart()">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count">0</span>
        </div>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Craving? We Deliver.</h1>
        <p>Your favorite meals delivered fresh in Mandaluyong.</p>
    </section>

    <section class="menu-grid">
        <?php foreach ($menu_items as $item): ?>
            <div class="product-card">
                <div class="img-container">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                </div>
                <div class="product-details">
                    <p class="category"><?= htmlspecialchars($item['category']) ?></p>
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <div class="price-row">
                        <span class="price">₱<?= number_format($item['price'], 2) ?></span>
                        <button class="add-btn" onclick="addToCart(<?= $item['id'] ?>, '<?= addslashes($item['name']) ?>', <?= $item['price'] ?>)">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <aside id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2>Your Tray</h2>
            <button onclick="toggleCart()" style="background:none; border:none; font-size:1.5rem;">&times;</button>
        </div>

        <div id="cart-items" class="cart-items-body">
            </div>

        <div class="cart-footer">
            <div class="total-section" style="display:flex; justify-content:space-between; margin-bottom:15px; font-weight:bold;">
                <span>Total:</span>
                <span id="cart-total">₱0.00</span>
            </div>
            <div class="checkout-form">
                <div id="receipt_section" style="display:none; margin-bottom: 10px;">
                    <label style="font-size: 0.8rem; color: #666;">Upload Receipt / Screenshot:</label>
                    <input type="file" id="receipt_file" accept="image/*">
                </div>
                <input type="text" id="cust_name" placeholder="Full Name" required>
                <input type="tel" id="cust_phone" placeholder="Phone Number" required>
                <textarea id="cust_address" placeholder="Delivery Address" required></textarea>
                <button class="checkout-btn" onclick="placeOrder()">Place Order</button>
            </div>
        </div>
    </aside>
</main>

<script>
let cart = [];

function toggleCart() {
    document.getElementById('cart-sidebar').classList.toggle('active');
}

function toggleReceiptUpload() {
    const method = document.getElementById('payment_method').value;
    document.getElementById('receipt_section').style.display = (method === 'GCash') ? 'block' : 'none';
}

function addToCart(id, name, price) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
    updateCartUI();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCartUI();
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    const countSpan = document.getElementById('cart-count');
    const totalSpan = document.getElementById('cart-total');
    
    container.innerHTML = '';
    let total = 0;
    let count = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
        count += item.quantity;
        
        container.innerHTML += `
            <div class="cart-item">
                <div class="item-info">
                    <h4>${item.name}</h4>
                    <small>₱${item.price.toFixed(2)} x ${item.quantity}</small>
                </div>
                <button class="remove-btn" onclick="removeFromCart(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    });

    countSpan.innerText = count;
    totalSpan.innerText = '₱' + total.toFixed(2);
}

function placeOrder() {
    if (cart.length === 0) return alert("Your tray is empty!");
    
    const formData = new FormData();
    formData.append('name', document.getElementById('cust_name').value);
    formData.append('phone', document.getElementById('cust_phone').value);
    formData.append('address', document.getElementById('cust_address').value);
    formData.append('payment_method', document.getElementById('payment_method').value);
    formData.append('items', JSON.stringify(cart));
    formData.append('total', cart.reduce((sum, item) => sum + (item.price * item.quantity), 0));

    // Append receipt if GCash was selected
    const receiptInput = document.getElementById('receipt_file');
    if (receiptInput.files.length > 0) {
        formData.append('receipt', receiptInput.files[0]);
    }

    fetch('api/place_order.php', {
        method: 'POST',
        body: formData // Note: Don't set Content-Type header manually when using FormData
    })
    .then(res => res.json())
    .then(data => {
        alert("Order #" + data.id + " placed via " + data.method + "!");
        location.reload();
    });
}
</script>
</body>
</html>
