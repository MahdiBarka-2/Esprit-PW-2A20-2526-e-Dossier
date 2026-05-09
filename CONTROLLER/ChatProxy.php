<?php
header('Content-Type: application/json');

require_once '../MODEL/Database.php';

// 1. Get Database Context
$db = new Database();
$conn = $db->getConnection();

$stats = [
    'users' => 0,
    'events' => 0,
    'active_events' => 0,
    'categories' => 0
];

try {
    $stats['users'] = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $stats['events'] = $conn->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
    $stats['active_events'] = $conn->query("SELECT COUNT(*) FROM evenements WHERE statut = 'active'")->fetchColumn();
    $stats['categories'] = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();
} catch (Exception $e) {
    // Fail silently if tables don't exist yet
}

if (session_status() === PHP_SESSION_NONE) session_start();
$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator');

// 2. Prepare the System Prompt
$convContext = "";
if ($isAdmin) {
    try {
        // Fetch last 10 active conversations to give AI context
        $convs = $conn->query("SELECT id, title, type FROM chat_conversations ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
        $convContext = "\nADMINISTRATOR PRIVILEGES ENABLED. You have access to chat insights. 
        AVAILABLE CONVERSATIONS:\n";
        foreach ($convs as $c) {
            $name = $c['title'] ?: "Private Chat ID " . $c['id'];
            $convContext .= "- [ID: {$c['id']}] Name: \"{$name}\" (Type: {$c['type']})\n";
        }
        $convContext .= "\nIf the administrator asks for a summary of one of these, I will automatically provide you with the message history in the next prompt.";
    } catch (Exception $e) {}
}

$systemPrompt = "You are the E-Dossier AI Assistant, a professional and helpful guide for the E-Dossier management platform. 
Your goal is to assist administrators with data-related questions, platform management, and chat summarization.

CURRENT DATABASE CONTEXT:
- Total Registered Users: {$stats['users']}
- Total Events Managed: {$stats['events']}
- Currently Active Events: {$stats['active_events']}
- Total Product/Service Categories: {$stats['categories']}
{$convContext}

CONSTRAINTS:
- Answer ONLY questions related to E-Dossier, document management, portal administration, or the data provided above.
- If a user asks for a summary, provide a professional and concise analysis of the key points.
- Maintain a professional, polite, and beige-themed personality (consistent with the UI).";

// 3. Get User Input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$history = $input['history'] ?? [];

// 3.5 Auto-Summary Trigger (for Admins only)
if ($isAdmin && (stripos($userMessage, 'summarize') !== false || stripos($userMessage, 'summary') !== false)) {
    try {
        // Try to match conversation ID or Title from user message
        $targetId = null;
        $convs = $conn->query("SELECT id, title FROM chat_conversations")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($convs as $c) {
            $name = $c['title'] ?: "Chat " . $c['id'];
            if (stripos($userMessage, $name) !== false) {
                $targetId = $c['id'];
                break;
            }
        }
        
        if ($targetId) {
            // Fetch last 50 messages to summarize
            $stmt = $conn->prepare("
                SELECT u.name as sender_name, m.content 
                FROM chat_messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE m.conversation_id = ? 
                ORDER BY m.created_at DESC 
                LIMIT 50
            ");
            $stmt->execute([$targetId]);
            $msgs = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
            
            $historyText = "CONVERSATION HISTORY FOR \"{$name}\":\n";
            foreach ($msgs as $m) {
                $historyText .= "- {$m['sender_name']}: {$m['content']}\n";
            }
            
            // Prepend to user message so AI can see it
            $userMessage = "PLEASE SUMMARIZE THIS CONVERSATION:\n\n{$historyText}\n\nUSER REQUEST: {$userMessage}";
        }
    } catch (Exception $e) {}
}

if (empty($userMessage)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

// 4. Build Messages Array for LM Studio
$messages = [
    ['role' => 'system', 'content' => $systemPrompt]
];

// Add history
foreach ($history as $msg) {
    $messages[] = $msg;
}

// Add current user message
$messages[] = ['role' => 'user', 'content' => $userMessage];

// 5. Call LM Studio API
$lmStudioUrl = "http://127.0.0.1:1234/v1/chat/completions";
$postData = [
    'model' => 'nvidia/nemotron-3-nano-4b', // LM Studio uses local-model as alias for loaded model
    'messages' => $messages,
    'temperature' => 0.7,
    'max_tokens' => 500
];

$ch = curl_init($lmStudioUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'LM Studio Error: ' . curl_error($ch)]);
} elseif ($httpCode !== 200) {
    echo json_encode(['error' => 'LM Studio Server returned error code ' . $httpCode . '. Is the Local Server running?']);
} else {
    $resData = json_decode($response, true);
    $aiResponse = $resData['choices'][0]['message']['content'] ?? "I'm sorry, I couldn't generate a response.";
    echo json_encode(['response' => $aiResponse]);
}

curl_close($ch);
