<?php
header('Content-Type: application/json');
include 'db.php';

// Get the data sent from the JavaScript fetch
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['price'])) {
    try {
        $stmt = $pdo->prepare("UPDATE menu_items SET price = :price WHERE id = :id");
        $stmt->execute([
            ':price' => $data['price'],
            ':id' => $data['id']
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing data']);
}
?>
