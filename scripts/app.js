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
    const input = document.querySelector(`.price-input[data-id='${id}']`);
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

async function savePrice(id) {
    const newPrice = document.getElementById(`price-${id}`).value;

    const response = await fetch('/api/update_price.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, price: newPrice })
    });

    const result = await response.json();
    if (result.success) {
        alert("Price updated successfully!");
    } else {
        alert("Error: " + result.error);
    }
}

// Client Reminder Logic
if (document.title.includes("Order Now")) {
    setTimeout(() => {
        if (cart.length === 0) notify("🍟 Hungry? Our fries are waiting!", "promo");
    }, 15000);
}
