<?php
include 'db.php';

// The password you want to use
$plain_password = 'admin123'; 
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("INSERT INTO staff (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $hashed_password, 'Admin']);
    echo "Success! Username: admin | Password: admin123";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
