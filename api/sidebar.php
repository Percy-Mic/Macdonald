<?php
// Get the current page filename to highlight the active link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar">
    <div class="logo">🍟 Mc<span>Express</span> Admin</div>
    
    <div class="staff-info">
        <small>Logged in as:</small>
        <strong><?php echo htmlspecialchars($_SESSION['staff_username'] ?? 'Staff'); ?></strong>
    </div>

    <nav>
        <a href="/admin/orders" class="<?php echo ($current_page == 'admin_orders.php') ? 'active' : ''; ?>">
            <i class="fas fa-receipt"></i> Incoming Orders
        </a>
        <a href="/admin/products" class="<?php echo ($current_page == 'admin.php') ? 'active' : ''; ?>">
            <i class="fas fa-hamburger"></i> Manage Products
        </a>
        <a href="/admin/staff" class="<?php echo ($current_page == 'admin_staff.php') ? 'active' : ''; ?>">
            <i class="fas fa-users-cog"></i> Staff & Security
        </a>
        <a href="/admin/settings" class="<?php echo ($current_page == 'change_password.php') ? 'active' : ''; ?>">
            <i class="fas fa-key"></i> Change Password
        </a>
        
        <div class="nav-footer">
            <a href="/logout" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
</div>

<style>
/* Sidebar Container */
.admin-sidebar { 
    width: 240px; 
    height: 100vh; 
    background: #1a1a1a; 
    color: #fff; 
    position: fixed; 
    left: 0; 
    top: 0; 
    padding: 20px; 
    box-sizing: border-box; 
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

/* Logo Styling */
.admin-sidebar .logo { 
    font-size: 1.5rem; 
    font-weight: bold; 
    color: #ffbc0d; 
    margin-bottom: 10px; 
    text-align: center; 
}
.admin-sidebar .logo span { color: #db0007; }

/* Staff Info Box */
.staff-info {
    background: #2a2a2a;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: center;
    border: 1px solid #333;
}
.staff-info small { display: block; color: #888; font-size: 0.75rem; }

/* Navigation Links */
.admin-sidebar nav { flex-grow: 1; }
.admin-sidebar nav a { 
    display: flex;
    align-items: center;
    color: #bbb; 
    text-decoration: none; 
    padding: 12px 15px; 
    border-radius: 8px; 
    transition: 0.3s; 
    margin-bottom: 8px; 
    font-size: 0.95rem;
}
.admin-sidebar nav a i { margin-right: 12px; width: 20px; text-align: center; }

/* Hover & Active States */
.admin-sidebar nav a:hover { 
    background: #333; 
    color: #ffbc0d; 
}
.admin-sidebar nav a.active { 
    background: #db0007; 
    color: white; 
    font-weight: bold;
}

/* Footer & Logout */
.nav-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid #333; }
.logout-btn { background: #333 !important; color: #ff4444 !important; }
.logout-btn:hover { background: #ff4444 !important; color: white !important; }

/* Main Content Adjustment */
body { 
    margin-left: 240px; 
    background: #f8f9fa; 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
}
</style>
