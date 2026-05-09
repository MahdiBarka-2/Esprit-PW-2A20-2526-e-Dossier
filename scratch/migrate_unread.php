<?php
require_once __DIR__ . '/../MODEL/Database.php';
$db = (new Database())->getConnection();

try {
    // Add last_read_at to chat_participants
    $db->exec("ALTER TABLE chat_participants ADD COLUMN last_read_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "Added last_read_at to chat_participants\n";
} catch (Exception $e) {
    echo "Note: " . $e->getMessage() . "\n";
}

echo "Migration complete.\n";
