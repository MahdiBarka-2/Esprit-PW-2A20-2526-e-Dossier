<?php
require_once 'MODEL/Database.php';
$db = new Database();
$conn = $db->getConnection();

try {
    $conn->exec("ALTER TABLE comment ADD COLUMN status VARCHAR(20) DEFAULT 'Approved'");
    echo "Column 'status' added successfully to 'comment' table.";
} catch (PDOException $e) {
    echo "Error or column already exists: " . $e->getMessage();
}
?>
