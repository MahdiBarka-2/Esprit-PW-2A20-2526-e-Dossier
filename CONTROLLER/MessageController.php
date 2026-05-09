<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../MODEL/Database.php';

class MessageController {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function sendMessage($conversation_id, $sender_id, $content, $type = 'text') {
        // Moderation Check (Call AI) ONLY for text
        if ($type === 'text') {
            $moderation = $this->analyzeContent($content, $sender_id);
            if ($moderation['blocked']) {
                return ['status' => 'error', 'message' => $moderation['reason']];
            }
        }

        $stmt = $this->db->prepare("INSERT INTO chat_messages (conversation_id, sender_id, content, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$conversation_id, $sender_id, $content, $type]);
        return ['status' => 'success', 'message_id' => $this->db->lastInsertId(), 'type' => $type];
    }

    public function createGroupChat($title, $creator_id, $participant_ids) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO chat_conversations (title, type) VALUES (?, 'group')");
            $stmt->execute([$title]);
            $conv_id = $this->db->lastInsertId();

            // Add participants
            $stmt = $this->db->prepare("INSERT INTO chat_participants (conversation_id, user_id) VALUES (?, ?)");
            $stmt->execute([$conv_id, $creator_id]);
            foreach ($participant_ids as $p_id) {
                if ($p_id != $creator_id) {
                    $stmt->execute([$conv_id, $p_id]);
                }
            }

            // System message
            $this->db->prepare("INSERT INTO chat_messages (conversation_id, sender_id, content, type) VALUES (?, ?, ?, 'system')")
                     ->execute([$conv_id, $creator_id, "Group created: $title"]);

            $this->db->commit();
            return ['status' => 'success', 'conversation_id' => $conv_id];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function getMessages($conversation_id, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.name as sender_name, u.profile_image_url 
            FROM chat_messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at ASC
            LIMIT ?
        ");
        $stmt->bindValue(1, $conversation_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConversations($user_id) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   u.name as other_user_name,
                   u.profile_image_url as other_user_image,
                   (SELECT content FROM chat_messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message,
                   (SELECT created_at FROM chat_messages WHERE conversation_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message_time
            FROM chat_conversations c
            JOIN chat_participants p ON c.id = p.conversation_id
            LEFT JOIN chat_participants p2 ON c.id = p2.conversation_id AND p2.user_id != p.user_id AND c.type = 'private'
            LEFT JOIN users u ON p2.user_id = u.id
            WHERE p.user_id = ?
            GROUP BY c.id
            ORDER BY last_message_time DESC, c.id DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function startPrivateChat($user_id1, $user_id2) {
        // Check if exists
        $stmt = $this->db->prepare("
            SELECT p1.conversation_id 
            FROM chat_participants p1
            JOIN chat_participants p2 ON p1.conversation_id = p2.conversation_id
            JOIN chat_conversations c ON p1.conversation_id = c.id
            WHERE p1.user_id = ? AND p2.user_id = ? AND c.type = 'private'
        ");
        $stmt->execute([$user_id1, $user_id2]);
        $existing = $stmt->fetch();

        if ($existing) return $existing['conversation_id'];

        // Create new
        $this->db->beginTransaction();
        try {
            $this->db->exec("INSERT INTO chat_conversations (type) VALUES ('private')");
            $conv_id = $this->db->lastInsertId();
            $this->db->prepare("INSERT INTO chat_participants (conversation_id, user_id) VALUES (?, ?), (?, ?)")
                     ->execute([$conv_id, $user_id1, $conv_id, $user_id2]);
            $this->db->commit();
            return $conv_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getOrCreateConversation($user_id1, $user_id2) {
        // Check if private chat exists
        $stmt = $this->db->prepare("
            SELECT c.id 
            FROM chat_conversations c
            JOIN chat_participants p1 ON c.id = p1.conversation_id
            JOIN chat_participants p2 ON c.id = p2.conversation_id
            WHERE c.type = 'private' 
              AND p1.user_id = ? 
              AND p2.user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$user_id1, $user_id2]);
        $id = $stmt->fetchColumn();

        if ($id) return $id;

        // Create new
        $this->db->exec("INSERT INTO chat_conversations (type) VALUES ('private')");
        $id = $this->db->lastInsertId();
        $this->db->prepare("INSERT INTO chat_participants (conversation_id, user_id) VALUES (?, ?), (?, ?)")->execute([$id, $user_id1, $id, $user_id2]);
        return $id;
    }

    public function markAsRead($user_id, $conv_id) {
        $stmt = $this->db->prepare("UPDATE chat_participants SET last_read_at = NOW() WHERE user_id = ? AND conversation_id = ?");
        return $stmt->execute([$user_id, $conv_id]);
    }

    public function getUnreadCount($user_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as unread 
            FROM chat_messages m
            JOIN chat_participants p ON m.conversation_id = p.conversation_id
            WHERE p.user_id = ? 
            AND m.sender_id != ?
            AND m.created_at > p.last_read_at
        ");
        $stmt->execute([$user_id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['unread'] ?? 0);
    }

    public function getAllUsers($current_user_id) {
        $stmt = $this->db->prepare("SELECT id, name, email, profile_image_url FROM users WHERE id != ? AND status != 'banned' LIMIT 50");
        $stmt->execute([$current_user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function syncGlobalGroup() {
        try {
            // Ensure "E-Dossier" exists and all users are participants
            $logo = '../../assets/images/e_dossier.png';
            $stmt = $this->db->prepare("SELECT id FROM chat_conversations WHERE title = 'E-Dossier' AND type = 'group' LIMIT 1");
            $stmt->execute();
            $conv_id = $stmt->fetchColumn();

            if (!$conv_id) {
                $this->db->prepare("INSERT INTO chat_conversations (title, type, icon_url) VALUES ('E-Dossier', 'group', ?)")->execute([$logo]);
                $conv_id = $this->db->lastInsertId();
            }
            
            if ($conv_id) {
                // Bulk insert ignore
                $this->db->prepare("INSERT IGNORE INTO chat_participants (conversation_id, user_id) 
                                 SELECT ?, id FROM users WHERE status != 'banned'")->execute([$conv_id]);
            }
            return $conv_id;
        } catch (Exception $e) {
            return null;
        }
    }

    private function analyzeContent($content, $user_id) {
        // This will call the AI Moderation logic
        require_once __DIR__ . '/ModerationController.php';
        $mod = new ModerationController();
        return $mod->checkMessage($content, $user_id);
    }
}
?>
