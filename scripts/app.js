let cart = [];

function toggleCart() {
    document.getElementById('cart-sidebar').classList.toggle('active');
}

function notify(msg, type = 'info') {
    const container = document.getElementById('notification-container');
    if(!container) return;
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = msg;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

function addToCart(id, name, price) {
    const item = cart.find(i => i.id === id);
    if (item) { item.quantity++; } 
    else { cart.push({id, name, price, quantity: 1}); }
    
    notify(`🍔 Added ${name} to order!`, 'success');
    updateUI();
}

function updateUI() {
    const list = document.getElementById('cart-items');
    const totalDisp = document.getElementById('cart-total');
    const countDisp = document.getElementById('cart-count');
    
    let total = 0;
    let count = 0;
    
    list.innerHTML = cart.map(item => {
        total += item.price * item.quantity;
        count += item.quantity;
        return `
            <div class="cart-item">
                <span><b>${item.name}</b> x${item.quantity}</span>
                <span>$${(item.price * item.quantity).toFixed(2)}</span>
            </div>`;
    }).join('');
    
    totalDisp.innerText = `$${total.toFixed(2)}`;
    countDisp.innerText = count;
}

function processOrder() {
    if (cart.length === 0) return notify("Your cart is empty!", 'error');
    notify("✅ Order received! We're starting your meal.", "success");
    
    cart = [];
    updateUI();
    toggleCart();
}

// DELETE FUNCTION
async function deleteProduct(id) {
    if (!confirm('Are you sure you want to delete this?')) return;

    const response = await fetch(`/api/delete_product.php?id=${id}`, { method: 'DELETE' });
    const result = await response.json();
    
    if (result.success) {
        location.reload(); // Refresh to show updated list
    } else {
        alert('Delete failed: ' + result.error);
    }
}

// UPDATE PRICE FUNCTION
async function updatePrice(id) {
    const input = document.getElementById('price-input-${id}')
    
    const newPrice = input.value;

    const response = await fetch('/api/update_price.php', {
        method: 'POST',
        body: JSON.stringify({ id: id, price: newPrice }),
        headers: { 'Content-Type': 'application/json' }
    });

    if (response.ok) {
        alert('Price updated!');
    }
}

function calculateTotal() {
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

async function placeOrder() {
    const orderData = {
        name: document.getElementById('cust_name').value,
        phone: document.getElementById('cust_phone').value,
        address: document.getElementById('cust_address').value,
        cart: cart, // Assuming your cart array is defined here
        total: calculateTotal() 
    };

    if(!orderData.name || !orderData.address) {
        alert("Please fill in delivery details!");
        return;
    }

    const response = await fetch('/api/place_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    });

    const result = await response.json();
    if(result.success) {
        alert("Order Received! Preparing your meal...");
        cart = []; // Clear cart
        location.reload();
    }
}
async function updateOrderStatus(orderId, newStatus) {
    const response = await fetch('/api/update_order_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: orderId, status: newStatus })
    });

    const result = await response.json();
    if (result.success) {
        // Optional: Change the color of the dropdown based on status
        alert("Order #" + orderId + " updated to " + newStatus);
    }
}

// Client Reminder Logic
if (document.title.includes("Order Now")) {
    setTimeout(() => {
        if (cart.length === 0) notify("🍟 Hungry? Our fries are waiting!", "promo");
    }, 15000);
}
