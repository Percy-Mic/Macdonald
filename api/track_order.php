<?php
include 'db.php';

$order_id = $_GET['id'] ?? '';
$order_data = null;
$error = "";

if ($order_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $order_data = $stmt->fetch();
        
        if (!$order_data) {
            $error = "Order ID not found. Please check and try again.";
        }
    } catch (PDOException $e) {
        $error = "System error. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order | McExpress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --mcd-red: #bd0017;
            --mcd-yellow: #ffc107;
            --gray: #e0e0e0;
            --success: #27ae60;
        }

        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        
        .container { max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        
        .logo { text-align: center; font-size: 2rem; font-weight: 800; color: var(--mcd-red); text-decoration: none; display: block; margin-bottom: 20px; }
        .logo span { color: var(--mcd-yellow); }

        h2 { text-align: center; margin-bottom: 30px; color: #333; }

        /* Search Box */
        .search-box { display: flex; gap: 10px; margin-bottom: 30px; }
        .search-box input { flex: 1; padding: 12px; border: 2px solid var(--gray); border-radius: 8px; font-size: 1rem; }
        .search-box input:focus { outline: none; border-color: var(--mcd-yellow); }
        .btn-track { background: var(--mcd-red); color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; }

        /* Status Tracker */
        .status-container { margin: 40px 0; display: flex; justify-content: space-between; position: relative; }
        .status-container::before { content: ''; position: absolute; top: 15px; left: 0; width: 100%; height: 4px; background: var(--gray); z-index: 1; }
        
        .step { z-index: 2; text-align: center; width: 80px; }
        .step .icon { width: 35px; height: 35px; background: white; border: 4px solid var(--gray); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; transition: 0.3s; }
        .step p { font-size: 0.75rem; font-weight: 700; color: #888; margin: 0; }

        /* Active Status Logic */
        .step.active .icon { border-color: var(--mcd-yellow); color: var(--mcd-yellow); transform: scale(1.2); }
        .step.active p { color: var(--dark); }
        .step.completed .icon { background: var(--success); border-color: var(--success); color: white; }
        .step.completed p { color: var(--success); }

        /* Order Details */
        .details { background: #fff9e6; padding: 20px; border-radius: 10px; border-left: 5px solid var(--mcd-yellow); }
        .details h4 { margin: 0 0 10px 0; color: #555; }
        .item-row { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 5px; }

        .error-msg { color: var(--mcd-red); text-align: center; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="logo">Mc<span>Express</span></a>
    <h2>Track Your Order</h2>

    <form action="track_order.php" method="GET" class="search-box">
        <input type="text" name="id" placeholder="Enter Order ID (e.g. 101)" value="<?= htmlspecialchars($order_id) ?>" required>
        <button type="submit" class="btn-track">Track</button>
    </form>

    <?php if ($error): ?>
        <p class="error-msg"><i class="fas fa-exclamation-circle"></i> <?= $error ?></p>
    <?php endif; ?>

    <?php if ($order_data): ?>
        <?php 
            $status = $order_data['status'];
            $items = json_decode($order_data['items'], true);
        ?>
        
        <div class="status-container">
            <div class="step <?= ($status == 'Pending' || $status == 'Preparing' || $status == 'Delivered') ? 'completed' : '' ?>">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <p>Pending</p>
            </div>
            <div class="step <?= ($status == 'Preparing' || $status == 'Delivered') ? ($status == 'Delivered' ? 'completed' : 'active') : '' ?>">
                <div class="icon"><i class="fas fa-fire-burner"></i></div>
                <p>Preparing</p>
            </div>
            <div class="step <?= ($status == 'Delivered') ? 'completed' : '' ?>">
                <div class="icon"><i class="fas fa-check"></i></div>
                <p>Delivered</p>
            </div>
        </div>

        <div class="details">
            <h4>Order Details for #<?= $order_data['id'] ?></h4>
            <p><strong>Customer:</strong> <?= htmlspecialchars($order_data['customer_name']) ?></p>
            <hr style="border: 0; border-top: 1px solid #ddd; margin: 15px 0;">
            <?php foreach ($items as $i): ?>
                <div class="item-row">
                    <span><?= htmlspecialchars($i['name']) ?> x<?= $i['quantity'] ?></span>
                    <span>₱<?= number_format($i['price'] * $i['quantity'], 2) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="item-row" style="margin-top: 10px; font-weight: 800; font-size: 1.1rem; border-top: 1px solid #ccc; padding-top: 10px;">
                <span>Total Amount</span>
                <span>₱<?= number_format($order_data['total_amount'], 2) ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
