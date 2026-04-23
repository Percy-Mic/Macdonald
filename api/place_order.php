<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data['name'] || !$data['address']) {
    echo json_encode(['success' => false, 'error' => 'Missing details']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO orders (customer_name, phone, address, total_amount, items) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['name'],
        $data['phone'],
        $data['address'],
        $data['total'],
        json_encode($data['cart']) // Save the cart array as MySQL JSON
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
