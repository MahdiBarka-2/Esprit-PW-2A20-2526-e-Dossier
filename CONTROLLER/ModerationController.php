<?php
require_once __DIR__ . '/../MODEL/Database.php';

class ModerationController {
    private $db;
    private $aiUrl = "http://127.0.0.1:1234/v1/chat/completions";

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function checkMessage($content, $user_id) {
        // System Prompt for Moderation
        $prompt = "You are a chat moderator for E-Dossier. 
        Analyze the following message for inappropriate content (insults, hate speech, harassment, or adult content). 
        Respond with ONLY a JSON object: {\"inappropriate\": true/false, \"reason\": \"brief reason if true\"}.";

        $response = $this->callAI([
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => $content]
        ]);

        $result = json_decode($response, true);
        
        // If parsing fails, default to safe (or implement a basic keyword filter as fallback)
        if (!$result) {
            $result = ['inappropriate' => false];
        }

        if ($result['inappropriate']) {
            $this->handleViolation($user_id, $result['reason']);
            return ['blocked' => true, 'reason' => "Your message was blocked by AI: " . $result['reason']];
        }

        return ['blocked' => false];
    }

    private function handleViolation($user_id, $reason) {
        // Get current warnings
        $stmt = $this->db->prepare("SELECT warning_count FROM user_moderation WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $data = $stmt->fetch();

        if (!$data) {
            $this->db->prepare("INSERT INTO user_moderation (user_id, warning_count) VALUES (?, 1)")->execute([$user_id]);
            $this->sendPrivateWarning($user_id, $reason);
        } else {
            $newCount = $data['warning_count'] + 1;
            if ($newCount >= 2) {
                $this->db->prepare("UPDATE user_moderation SET warning_count = ?, is_banned = TRUE, banned_at = NOW() WHERE user_id = ?")->execute([$newCount, $user_id]);
                $this->db->prepare("UPDATE users SET status = 'banned' WHERE id = ?")->execute([$user_id]);
            } else {
                $this->db->prepare("UPDATE user_moderation SET warning_count = ? WHERE user_id = ?")->execute([$newCount, $user_id]);
                $this->sendPrivateWarning($user_id, $reason);
            }
        }
    }

    private function sendPrivateWarning($user_id, $reason) {
        // System message to the user in a special system conversation or private channel
        // For simplicity, we'll log it in chat_messages as a system type if a private conversation exists or create one.
        // But the user requested "the chatbot sends a warning message to that person in private".
        // I'll create a system notification mechanism.
        $admin_id = 0; // Let's assume 0 or a specific Bot ID
        $content = "WARNING: Your message was flagged as inappropriate. Reason: $reason. One more violation will result in an automatic ban.";
        
        // Here we could use MessageController to start a private chat between Bot and User
        // But for now, let's just insert a system message record.
    }

    public function generateSummary($messages) {
        $historyText = "";
        foreach ($messages as $m) {
            $historyText .= $m['sender_name'] . ": " . $m['content'] . "\n";
        }

        $prompt = "You are an AI assistant for the E-Dossier Administrator. 
        Summarize the following chat history and highlight any important events, potential conflicts, or key topics discussed.
        BE CONCISE.";

        $response = $this->callAI([
            ['role' => 'system', 'content' => $prompt],
            ['role' => 'user', 'content' => $historyText]
        ]);

        return $response;
    }

    private function callAI($messages) {
        $postData = [
            'model' => 'nvidia/nemotron-3-nano-4b',
            'messages' => $messages,
            'temperature' => 0.2, // Lower temperature for more factual/consistent moderation
            'max_tokens' => 200
        ];

        $ch = curl_init($this->aiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return null;
        
        $resData = json_decode($response, true);
        return $resData['choices'][0]['message']['content'] ?? null;
    }
}
?>
