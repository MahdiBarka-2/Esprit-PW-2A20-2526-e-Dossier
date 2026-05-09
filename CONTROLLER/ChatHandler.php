<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'MessageController.php';
require_once 'UserController.php';

$msgCtrl = new MessageController();
$msgCtrl->syncGlobalGroup(); // Keep everyone in the loop

$action = $_GET['action'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

switch ($action) {
    case 'get_unread_count':
        echo json_encode(['count' => $msgCtrl->getUnreadCount($user_id)]);
        break;

    case 'get_conversations':
        echo json_encode($msgCtrl->getConversations($user_id));
        break;

    case 'get_all_users':
        echo json_encode($msgCtrl->getAllUsers($user_id));
        break;

    case 'start_chat':
        $target_id = $_POST['target_id'] ?? 0;
        echo json_encode($msgCtrl->getOrCreateConversation($user_id, $target_id));
        break;

    case 'mark_read':
        $conv_id = $_POST['conv_id'] ?? 0;
        echo json_encode(['status' => $msgCtrl->markAsRead($user_id, $conv_id)]);
        break;

    case 'get_messages':
        $conv_id = $_GET['conv_id'] ?? 0;
        echo json_encode($msgCtrl->getMessages($conv_id));
        break;

    case 'send':
        $conv_id = $_POST['conv_id'] ?? 0;
        $content = $_POST['content'] ?? '';
        echo json_encode($msgCtrl->sendMessage($conv_id, $user_id, $content));
        break;

    case 'create_group':
        $title = $_POST['title'] ?? 'New Group';
        $participants = $_POST['participants'] ?? []; // Array of IDs
        if (is_string($participants)) $participants = json_decode($participants, true);
        echo json_encode($msgCtrl->createGroupChat($title, $user_id, $participants));
        break;

    case 'upload_file':
        if (!empty($_FILES['chat_file'])) {
            $upload_dir = '../assets/uploads/chat/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $file_name = time() . '_' . basename($_FILES['chat_file']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['chat_file']['tmp_name'], $target_file)) {
                $conv_id = $_POST['conv_id'] ?? 0;
                $file_url = '../../assets/uploads/chat/' . $file_name;
                echo json_encode($msgCtrl->sendMessage($conv_id, $user_id, $file_url, 'file'));
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Upload failed']);
            }
        }
        break;

    case 'get_ai_summary':
        if ($_SESSION['role'] !== 'administrator') {
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $conv_id = $_GET['conv_id'] ?? 0;
        $messages = $msgCtrl->getMessages($conv_id, 100);
        require_once 'ModerationController.php';
        $mod = new ModerationController();
        echo json_encode(['summary' => $mod->generateSummary($messages)]);
        break;

    case 'search_users':
        $query = $_GET['query'] ?? '';
        // We'll use a simple search from UserController context or DB
        require_once '../MODEL/Database.php';
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT id, name, email, profile_image_url FROM users WHERE (name LIKE ? OR email LIKE ?) AND status != 'banned' LIMIT 10");
        $stmt->execute(["%$query%", "%$query%"]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>
