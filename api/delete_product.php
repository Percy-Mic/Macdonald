<?php
header('Content-Type: application/json');
require_once __DIR__ . '/auth_check.php';
include 'db.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
$success = $stmt->execute([$id]);
echo json_encode(['success' => $success]);
?>
