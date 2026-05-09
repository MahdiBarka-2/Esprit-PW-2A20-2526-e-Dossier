<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../CONTROLLER/MessageCONTROLLER.php';
require_once '../../CONTROLLER/UserCONTROLLER.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Boffice/sign-in.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msgCtrl = new MessageCONTROLLER();
$conversations = $msgCtrl->getConversations($user_id);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title>e_dossier - Messenger</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    
    <style>
        :root {
            --chat-bg: #0f172a;
            --chat-sidebar: #1e293b;
            --chat-accent: #3b82f6;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--chat-bg);
            color: #f5f5dc;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .messenger-wrapper {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .chat-sidebar {
            width: 350px;
            background: var(--chat-sidebar);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
        }

        .conversation-list {
            flex: 1;
            overflow-y: auto;
        }

        .conversation-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        }

        .conversation-item:hover, .conversation-item.active {
            background: rgba(59, 130, 246, 0.1);
        }

        .conversation-item .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        /* Chat Window */
        .chat-window {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(15, 23, 42, 0.8);
            position: relative;
        }

        .chat-header {
            padding: 15px 25px;
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-messages {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.4;
            position: relative;
        }

        .message-bubble.received {
            background: var(--glass-bg);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .message-bubble.sent {
            background: var(--chat-accent);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .chat-footer {
            padding: 20px 25px;
            background: rgba(30, 41, 59, 0.5);
            border-top: 1px solid var(--glass-border);
        }

        .chat-input-group {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 5px 20px;
            display: flex;
            align-items: center;
        }

        .chat-input {
            flex: 1;
            background: transparent;
            border: none;
            color: white;
            padding: 10px;
            outline: none;
        }

        .chat-input::placeholder {
            color: rgba(245, 245, 220, 0.5);
        }

        /* Search Users Modal */
        .user-search-result {
            padding: 10px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Empty State */
        .empty-chat {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0.5;
        }

        .empty-chat i {
            font-size: 5rem;
            margin-bottom: 20px;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="messenger-wrapper">
        <!-- Sidebar -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 text-white">Chats</h4>
                    <button class="btn btn-sm btn-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#searchUsersModal">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                <div class="chat-input-group py-1 px-3" style="border-radius: 10px;">
                    <i class="bi bi-search opacity-50 me-2"></i>
                    <input type="text" class="chat-input" placeholder="Search chats...">
                </div>
            </div>
            
            <div class="conversation-list" id="conversationList">
                <?php if (empty($conversations)): ?>
                    <div class="text-center p-5 opacity-50 small">No conversations yet</div>
                <?php else: ?>
                    <?php foreach ($conversations as $conv): ?>
                        <div class="conversation-item" onclick="loadChat(<?php echo $conv['id']; ?>, '<?php echo htmlspecialchars($conv['title'] ?: 'Private Chat'); ?>')">
                            <img src="../../assets/images/avatar/01.jpg" class="avatar" alt="">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-0 text-white text-truncate"><?php echo htmlspecialchars($conv['title'] ?: 'Private Chat'); ?></h6>
                                    <small class="opacity-50">12:45</small>
                                </div>
                                <p class="mb-0 small text-truncate opacity-50"><?php echo htmlspecialchars($conv['last_message'] ?: 'Start a conversation'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Main Chat -->
        <main class="chat-window">
            <div id="chatDefault" class="empty-chat">
                <i class="bi bi-chat-dots"></i>
                <h5>Select a chat to start messaging</h5>
            </div>

            <div id="chatActive" style="display: none; height: 100%; flex-direction: column;">
                <div class="chat-header">
                    <div class="d-flex align-items-center">
                        <img src="../../assets/images/avatar/01.jpg" id="activeChatAvatar" class="avatar me-3" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
                        <div>
                            <h6 class="mb-0 text-white" id="activeChatTitle">Chat Name</h6>
                            <small class="text-success small"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Online</small>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <button class="btn btn-sm btn-link text-white opacity-50"><i class="bi bi-telephone"></i></button>
                        <button class="btn btn-sm btn-link text-white opacity-50"><i class="bi bi-info-circle"></i></button>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessagesArea">
                    <!-- Messages will load here -->
                </div>

                <div class="chat-footer">
                    <div class="chat-input-group">
                        <button class="btn btn-link text-white opacity-50 p-0 me-3"><i class="bi bi-plus-circle"></i></button>
                        <input type="text" id="messageInput" class="chat-input" placeholder="Type a message...">
                        <button class="btn btn-link text-primary p-0 ms-3" id="sendBtn"><i class="bi bi-send-fill fs-5"></i></button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- User Search Modal -->
    <div class="modal fade" id="searchUsersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Start new chat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="chat-input-group mb-3">
                        <i class="bi bi-search opacity-50 me-2"></i>
                        <input type="text" id="userSearchInput" class="chat-input" placeholder="Search users by name or email...">
                    </div>
                    <div id="userSearchResults" class="overflow-auto" style="max-height: 300px;">
                        <!-- Results -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentConvId = null;
        let pollingInterval = null;

        function loadChat(convId, title) {
            currentConvId = convId;
            document.getElementById('chatDefault').style.display = 'none';
            document.getElementById('chatActive').style.display = 'flex';
            document.getElementById('activeChatTitle').innerText = title;
            
            // Clear messages
            document.getElementById('chatMessagesArea').innerHTML = '';
            
            fetchMessages();
            
            // Restart polling
            if (pollingInterval) clearInterval(pollingInterval);
            pollingInterval = setInterval(fetchMessages, 3000);
        }

        async function fetchMessages() {
            if (!currentConvId) return;
            try {
                const response = await fetch(`../../CONTROLLER/ChatHandler.php?action=get_messages&conv_id=${currentConvId}`);
                const data = await response.json();
                renderMessages(data);
            } catch (err) {
                console.error("Error fetching messages:", err);
            }
        }

        function renderMessages(messages) {
            const area = document.getElementById('chatMessagesArea');
            const currentScroll = area.scrollTop;
            const isAtBottom = area.scrollHeight - area.scrollTop === area.clientHeight;
            
            area.innerHTML = '';
            messages.forEach(msg => {
                const bubble = document.createElement('div');
                const isMine = msg.sender_id == <?php echo $user_id; ?>;
                bubble.className = `message-bubble ${isMine ? 'sent' : 'received'}`;
                bubble.innerText = msg.content;
                area.appendChild(bubble);
            });
            
            if (isAtBottom) {
                area.scrollTop = area.scrollHeight;
            } else {
                area.scrollTop = currentScroll;
            }
        }

        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const val = input.value.trim();
            if (!val || !currentConvId) return;
            
            input.value = '';
            
            try {
                const response = await fetch('../../CONTROLLER/ChatHandler.php?action=send', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `conv_id=${currentConvId}&content=${encodeURIComponent(val)}`
                });
                const result = await response.json();
                if (result.status === 'error') {
                    alert(result.message);
                }
                fetchMessages();
            } catch (err) {
                console.error("Error sending message:", err);
            }
        }

        document.getElementById('sendBtn').onclick = sendMessage;
        document.getElementById('messageInput').onkeypress = (e) => {
            if (e.key === 'Enter') sendMessage();
        };

        // User Search Logic
        document.getElementById('userSearchInput').oninput = async function() {
            const query = this.value.trim();
            if (query.length < 2) return;
            
            try {
                const response = await fetch(`../../CONTROLLER/ChatHandler.php?action=search_users&query=${encodeURIComponent(query)}`);
                const users = await response.json();
                const resultsArea = document.getElementById('userSearchResults');
                resultsArea.innerHTML = '';
                
                users.forEach(user => {
                    if (user.id == <?php echo $user_id; ?>) return;
                    const item = document.createElement('div');
                    item.className = 'user-search-result';
                    item.innerHTML = `
                        <div class="d-flex align-items-center">
                            <img src="${user.profile_image_url || '../../assets/images/avatar/01.jpg'}" class="avatar me-2" style="width: 35px; height: 35px;">
                            <div>
                                <h6 class="mb-0 small">${user.name}</h6>
                                <small class="opacity-50" style="font-size: 0.7rem;">${user.email}</small>
                            </div>
                        </div>
                        <button class="btn btn-xs btn-primary py-1 px-3" onclick="startChat(${user.id}, '${user.name.replace("'", "\\'")}')">Chat</button>
                    `;
                    resultsArea.appendChild(item);
                });
            } catch (err) {
                console.error("Search error:", err);
            }
        };

        async function startChat(targetId, name) {
            try {
                const response = await fetch('../../CONTROLLER/ChatHandler.php?action=start_chat', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `target_id=${targetId}`
                });
                const convId = await response.json();
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('searchUsersModal')).hide();
                
                // Reload page or dynamicly add to sidebar and load
                location.reload(); 
            } catch (err) {
                console.error("Error starting chat:", err);
            }
        }
    </script>
</body>
</html>
