<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/UserController.php';
require_once '../../CONTROLLER/ModerationController.php';
require_once '../../CONTROLLER/MessageController.php';

if ($_SESSION['role'] !== 'administrator') {
    header("Location: index.php");
    exit();
}

$msgCtrl = new MessageController();
$modCtrl = new ModerationController();
$conversations = $msgCtrl->getConversations($_SESSION['user_id']); // This might only show some, but let's fetch all group chats

// Fetch all group chats for summary
require_once '../../MODEL/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("SELECT * FROM chat_conversations WHERE type = 'group'");
$groupChats = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<div class="page-content-wrapper p-xxl-4">
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <h1 class="h3 mb-0">Chat Insights & Moderation</h1>
            <p class="text-muted">AI-powered analysis and summaries of user conversations.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- AI Summary Section -->
        <div class="col-lg-8">
            <div class="card shadow border-0 h-100">
                <div class="card-header border-bottom bg-transparent">
                    <h5 class="card-header-title mb-0"><i class="bi bi-robot me-2 text-primary"></i>AI Conversation Summaries</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($groupChats as $chat): ?>
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?php echo htmlspecialchars($chat['title']); ?></h6>
                                        <button class="btn btn-xs btn-primary-soft" onclick="getAISummary(<?php echo $chat['id']; ?>)">
                                            <i class="bi bi-stars me-1"></i> Generate AI Summary
                                        </button>
                                    </div>
                                    <div id="summary-<?php echo $chat['id']; ?>" class="small text-muted italic">
                                        Click the button to generate a summary of the latest activity.
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Moderation Stats -->
        <div class="col-lg-4">
            <div class="card shadow border-0 mb-4">
                <div class="card-header border-bottom bg-transparent">
                    <h5 class="card-header-title mb-0"><i class="bi bi-shield-exclamation me-2 text-danger"></i>Moderation Status</h5>
                </div>
                <div class="card-body">
                    <?php
                    $stmt = $db->query("
                        SELECT m.*, u.name, u.email 
                        FROM user_moderation m
                        JOIN users u ON m.user_id = u.id
                        WHERE m.warning_count > 0 OR m.is_banned = TRUE
                    ");
                    $flaggedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php if (empty($flaggedUsers)): ?>
                        <p class="text-muted small">No users currently flagged for moderation.</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($flaggedUsers as $user): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 small"><?php echo htmlspecialchars($user['name']); ?></h6>
                                            <small class="text-muted"><?php echo $user['warning_count']; ?> Warning(s)</small>
                                        </div>
                                        <?php if ($user['is_banned']): ?>
                                            <span class="badge bg-danger">Banned</span>
                                        <?php else: ?>
                                            <button class="btn btn-xs btn-outline-danger" onclick="banUser(<?php echo $user['user_id']; ?>)">Ban</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Bot Configuration -->
            <div class="card shadow border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <h6 class="mb-3">Bot Assistant Active <span class="badge bg-success ms-2">Live</span></h6>
                    <p class="small mb-0">The AI is currently monitoring all chats and will automatically issue warnings or bans based on platform rules.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function getAISummary(convId) {
    const area = document.getElementById(`summary-${convId}`);
    area.innerHTML = '<div class="spinner-border spinner-border-sm text-primary me-2"></div> AI is analyzing...';
    
    try {
        const response = await fetch(`../../CONTROLLER/ChatHandler.php?action=get_ai_summary&conv_id=${convId}`);
        const data = await response.json();
        area.innerHTML = `<div class="bg-white p-2 rounded border mt-2" style="font-size: 0.85rem; line-height: 1.5;">${data.summary}</div>`;
    } catch (err) {
        area.innerHTML = '<span class="text-danger">Failed to generate summary. Make sure AI server is running.</span>';
    }
}

async function banUser(userId) {
    if(!confirm('Are you sure you want to manually ban this user?')) return;
    location.href = `../../CONTROLLER/UserController.php?action=ban&id=${userId}`;
}
</script>

<?php require_once 'footer.php'; ?>
