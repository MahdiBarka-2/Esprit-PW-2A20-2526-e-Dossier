<?php
require_once 'MODEL/Database.php';

try {
    $db = (new Database())->getConnection();

    // 1. Conversations Table
    $db->exec("CREATE TABLE IF NOT EXISTS chat_conversations (
        id INT PRIMARY KEY AUTO_INCREMENT,
        title VARCHAR(255) DEFAULT NULL,
        type ENUM('private', 'group') DEFAULT 'private',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Participants Table
    $db->exec("CREATE TABLE IF NOT EXISTS chat_participants (
        conversation_id INT NOT NULL,
        user_id INT NOT NULL,
        joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (conversation_id, user_id),
        FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE
    )");

    // 3. Messages Table
    $db->exec("CREATE TABLE IF NOT EXISTS chat_messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        conversation_id INT NOT NULL,
        sender_id INT NOT NULL,
        content TEXT NOT NULL,
        type ENUM('text', 'system') DEFAULT 'text',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE
    )");

    // 4. Friendships Table (Private Chat Access)
    $db->exec("CREATE TABLE IF NOT EXISTS friendships (
        user_id1 INT NOT NULL,
        user_id2 INT NOT NULL,
        status ENUM('pending', 'accepted', 'blocked') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (user_id1, user_id2)
    )");

    // 5. Moderation Table
    $db->exec("CREATE TABLE IF NOT EXISTS user_moderation (
        user_id INT PRIMARY KEY,
        warning_count INT DEFAULT 0,
        is_banned BOOLEAN DEFAULT FALSE,
        banned_at DATETIME DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Ensure a default Global Group Chat exists
    $stmt = $db->query("SELECT id FROM chat_conversations WHERE type = 'group' AND title = 'General Lounge' LIMIT 1");
    if (!$stmt->fetch()) {
        $db->exec("INSERT INTO chat_conversations (title, type) VALUES ('General Lounge', 'group')");
        echo "Created General Lounge group chat.\n";
    }

    echo "Messaging tables created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
