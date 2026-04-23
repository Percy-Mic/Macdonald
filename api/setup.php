<?php
include 'db.php';

// We will force the password to 'admin123'
$new_hashed_password = password_hash('admin123', PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE staff SET password = ? WHERE username = ?");
    $stmt->execute([$new_hashed_password, 'admin']);
    
    if ($stmt->rowCount() > 0) {
        echo "Success! The 'admin' account password has been reset to: admin123";
    } else {
        echo "No changes made. The user 'admin' might not exist or the password is already set to this.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
