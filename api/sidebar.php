<div class="admin-sidebar">
    <div class="logo">🍟 McExpress Admin</div>
    <nav>
        <a href="/admin/orders">📋 Incoming Orders</a>
        <a href="/admin/products">🍔 Manage Products</a>
        <a href="/admin/staff">👥 Staff & Security</a>
        <a href="/admin/settings">🔑 Change Password</a>
        <div class="nav-footer">
            <a href="/logout" class="logout-btn">Logout</a>
        </div>
    </nav>
</div>

<style>
.admin-sidebar { width: 240px; height: 100vh; background: #222; color: #fff; position: fixed; left: 0; top: 0; padding: 20px; box-sizing: border-box; }
.admin-sidebar .logo { font-size: 1.5rem; font-weight: bold; color: #ffbc0d; margin-bottom: 30px; text-align: center; }
.admin-sidebar nav a { display: block; color: #ccc; text-decoration: none; padding: 12px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
.admin-sidebar nav a:hover { background: #db0007; color: white; }
body { margin-left: 240px; background: #f4f4f4; font-family: sans-serif; }
</style>
