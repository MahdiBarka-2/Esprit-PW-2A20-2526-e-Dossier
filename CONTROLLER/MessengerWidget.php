<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function renderMessengerWidget() {
    if (!isset($_SESSION['user_id'])) return '';
    
    $user_id = $_SESSION['user_id'];
    ob_start();
    ?>
    <style>
        :root {
            --ms-bg: #ffffff;
            --ms-side: #f0f2f5;
            --ms-text: #1c1e21;
            --ms-muted: #65676b;
            --ms-border: #dddfe2;
            --ms-bubble-r: #e4e6eb;
            --ms-bubble-s: #0084ff;
            --ms-accent: #0084ff;
            --ms-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }

        [data-theme="dark"], .dark-mode, .dark-theme {
            --ms-bg: #242526;
            --ms-side: #1c1e21;
            --ms-text: #e4e6eb;
            --ms-muted: #b0b3b8;
            --ms-border: #3e4042;
            --ms-bubble-r: #3e4042;
            --ms-bubble-s: #0084ff;
            --ms-shadow: 0 12px 28px rgba(0,0,0,0.5);
        }

        #messenger-floating-toggle {
            position: fixed;
            bottom: 190px;
            right: 30px;
            z-index: 10000;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 114, 255, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid white;
            font-size: 1.8rem;
            animation: msgr-float 3.5s ease-in-out infinite;
        }

        #msgr-notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff3b30;
            color: white;
            border-radius: 50%;
            min-width: 24px;
            height: 24px;
            padding: 0 6px;
            font-size: 0.75rem;
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        #messenger-panel {
            position: fixed;
            top: 50px;
            right: 100px;
            width: 32vw;
            height: calc(100vh - 70px);
            background: var(--ms-bg);
            border-radius: 25px;
            box-shadow: var(--ms-shadow);
            z-index: 20000;
            display: flex;
            flex-direction: row;
            overflow: hidden;
            border: 1px solid var(--ms-border);
            transform: translateX(calc(100% + 150px));
            transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);
        }

        #messenger-panel.open {
            transform: translateX(0);
        }

        .msgr-sidebar {
            width: 120px;
            background: var(--ms-side);
            border-right: 1px solid var(--ms-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            gap: 25px;
            overflow-y: auto;
        }

        .msgr-conv-item {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .msgr-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid transparent;
            padding: 2px;
            background: var(--ms-bg);
        }

        .msgr-conv-item.active .msgr-avatar {
            border-color: var(--ms-accent);
            box-shadow: 0 0 15px rgba(0, 132, 255, 0.4);
        }

        .msgr-conv-label {
            font-size: 0.65rem;
            color: var(--ms-text);
            margin-top: 5px;
            font-weight: 600;
            max-width: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #msgr-active-title {
            color: #003366 !important; /* Dark Blue */
        }

        .msgr-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--ms-bg);
        }

        .msgr-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--ms-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .msgr-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: var(--ms-bg);
        }

        .msgr-bubble {
            max-width: 85%;
            padding: 12px 18px;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.4;
            color: var(--ms-text);
        }

        .msgr-bubble.sent {
            background: var(--ms-bubble-s);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .msgr-bubble.received {
            background: var(--ms-bubble-r);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            color: #000000; /* Main message in black */
        }

        .msgr-sender-name {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--ms-accent);
            margin-bottom: 4px;
            display: block;
        }

        .msgr-bubble.system {
            background: none;
            align-self: center;
            font-size: 0.75rem;
            color: var(--ms-muted);
            text-transform: uppercase;
            font-weight: 800;
        }

        .msgr-emoji-picker {
            position: absolute;
            bottom: 80px;
            right: 20px;
            width: 250px;
            background: var(--ms-bg);
            border: 1px solid var(--ms-border);
            border-radius: 15px;
            box-shadow: var(--ms-shadow);
            padding: 10px;
            display: none;
            grid-template-columns: repeat(6, 1fr);
            gap: 5px;
            z-index: 20005;
            max-height: 200px;
            overflow-y: auto;
        }

        .msgr-emoji-item {
            font-size: 1.2rem;
            cursor: pointer;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
            transition: background 0.2s;
        }

        .msgr-emoji-item:hover {
            background: rgba(0, 132, 255, 0.1);
        }

        #msgr-emoji-btn {
            color: #8e8e8e; /* Subtle Grey */
            margin-left: 10px;
            font-size: 1.3rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        #msgr-emoji-btn:hover {
            color: var(--ms-accent);
        }

        #msgr-group-modal h5 {
            color: #003366 !important; /* Dark Blue */
            font-weight: 700;
        }

        #msgr-group-modal input[type="text"], 
        #msgr-group-modal .form-control {
            color: #666666 !important; /* Grey text */
            border-color: var(--ms-border);
        }

        #msgr-group-modal .msgr-check-label {
            color: #666666;
            font-size: 0.85rem;
        }

        .msgr-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--ms-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .msgr-input-wrapper {
            flex: 1;
            background: var(--ms-bubble-r);
            border-radius: 25px;
            padding: 10px 18px;
            display: flex;
            align-items: center;
        }

        .msgr-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--ms-text);
            font-size: 0.95rem;
            outline: none;
        }

        .msgr-action-btn {
            background: none;
            border: none;
            color: #0084ff; /* Explicit Messenger Blue */
            font-size: 1.4rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .msgr-action-btn:hover { transform: scale(1.15); }

        /* Sidebar Tabs */
        .msgr-tab-btn {
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: bold;
            cursor: pointer;
            border: 1px solid var(--ms-border);
            color: var(--ms-muted);
            margin-bottom: 10px;
        }
        .msgr-tab-btn.active {
            background: var(--ms-accent);
            color: white;
            border-color: var(--ms-accent);
        }

        @keyframes msgr-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>

    <div id="messenger-floating-toggle" onclick="toggleMessenger()">
        <i class="fab fa-facebook-messenger"></i>
        <div id="msgr-notification-badge">0</div>
    </div>

    <div id="messenger-panel">
        <div class="msgr-sidebar">
            <div class="d-flex flex-column align-items-center mb-3">
                <div class="msgr-tab-btn active" id="tab-chats" onclick="switchTab('chats')">CHATS</div>
                <div class="msgr-tab-btn" id="tab-users" onclick="switchTab('users')">GUESTS</div>
            </div>
            <div id="msgr-sidebar-list" class="w-100">
                <!-- Items load here -->
            </div>
        </div>

        <div class="msgr-content">
            <div class="msgr-header">
                <div class="d-flex align-items-center overflow-hidden">
                    <img src="../../assets/images/logo-icon.svg" id="msgr-header-icon" class="me-2" style="width: 32px; height: 32px; border-radius: 50%;">
                    <h6 class="mb-0 fw-bold text-truncate" id="msgr-active-title" style="color: var(--ms-text); font-size: 1rem;">Messenger</h6>
                </div>
                <div class="d-flex gap-2">
                    <button class="msgr-action-btn" title="New Group" onclick="showGroupModal()"><i class="bi bi-plus-square-fill"></i></button>
                    <button class="msgr-action-btn" title="Refresh" onclick="loadSidebarItems()"><i class="bi bi-arrow-clockwise"></i></button>
                    <button class="msgr-action-btn" onclick="toggleMessenger()" style="color: var(--ms-muted)"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            </div>

            <div class="msgr-messages" id="msgr-chat-body">
                <div class="text-center mt-5 opacity-50 small" style="color: var(--ms-text)">Welcome to E-Dossier Messenger</div>
            </div>

            <div class="msgr-footer">
                <label class="msgr-action-btn" style="cursor: pointer;">
                    <i class="bi bi-plus-circle-fill" style="color: #0084ff !important;"></i>
                    <input type="file" id="msgr-file-input" style="display: none;" onchange="uploadChatFile()">
                </label>
                <div class="msgr-input-wrapper">
                    <input type="text" id="msgr-text-input" class="msgr-input" placeholder="Type a message..." autocomplete="off">
                    <i class="bi bi-emoji-smile-fill" id="msgr-emoji-btn" onclick="toggleEmojiPicker()"></i>
                </div>
                <button class="msgr-action-btn" id="msgr-send-btn"><i class="bi bi-send-fill"></i></button>
            </div>

            <!-- Emoji Picker -->
            <div id="msgr-emoji-picker" class="msgr-emoji-picker"></div>
        </div>

        <div id="msgr-group-modal" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background: var(--ms-bg); z-index:20001; flex-direction:column; padding:30px;">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-arrow-left me-3" style="font-size: 1.5rem; color: #003366; cursor: pointer;" onclick="hideGroupModal()"></i>
                <h5 class="fw-bold mb-0" style="color: var(--ms-text)">Create New Group</h5>
             </div>
             <input type="text" id="msgr-new-group-name" class="form-control mb-4" placeholder="Group Name">
             <div id="msgr-user-selection" class="flex-grow-1 overflow-auto mb-4 border rounded p-2"></div>
             <button class="btn btn-primary w-100 rounded-pill" onclick="createNewGroup()">Create Group</button>
        </div>
    </div>

    <script>
        const handlerPath = '../../CONTROLLER/ChatHandler.php';
        const msgrUserId = <?php echo $_SESSION['user_id']; ?>;
        let msgrConvId = null;
        let msgrPolling = null;
        let activeTab = 'chats'; // 'chats' or 'users'

        const emojis = ['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😝','😜','🤪','🤨','🧐','🤓','😎','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','☹️','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳','🥵','🥶','😱','😨','😰','😥','😓','🤗','🤔','🤭','🤫','🤥','😶','😐','😑','😬','🙄','😯','😦','😧','😮','😲','🥱','😴','🤤','😪','😵','🤐','🥴','🤢','🤮','🤧','😷','🤒','🤕','🤑','🤠','😈','👿','👹','👺','🤡','💩','👻','💀','☠️','👽','👾','🤖','🎃','😺','😸','😻','😼','😽','🙀','😿','😾','🤲','👐','🙌','👏','🤝','👍','👎','👊','✊','🤛','🤜','🤞','✌️','🤟','🤘','👌','🤏','👈','👉','👆','👇','☝️','✋','🤚','🖐','🖖','👋','🤙','💪','🦾','🖕','✍️','🙏','🦶','🦵','🦿','💄','💋','👄','🦷','👅','👂','🦻','👃','👣','👁','👀','🧠','🗣','👤','👥'];

        function initEmojiPicker() {
            const picker = document.getElementById('msgr-emoji-picker');
            if (!picker) return;
            picker.innerHTML = '';
            emojis.forEach(e => {
                const span = document.createElement('span');
                span.className = 'msgr-emoji-item';
                span.innerText = e;
                span.onclick = () => {
                    const input = document.getElementById('msgr-text-input');
                    input.value += e;
                    input.focus();
                };
                picker.appendChild(span);
            });
        }

        function toggleEmojiPicker() {
            const picker = document.getElementById('msgr-emoji-picker');
            picker.style.display = (picker.style.display === 'grid') ? 'none' : 'grid';
        }

        async function toggleMessenger() {
            const panel = document.getElementById('messenger-panel');
            panel.classList.toggle('open');
            if (panel.classList.contains('open')) {
                loadSidebarItems();
                updateBadge(0);
                initEmojiPicker();
            } else {
                if (msgrPolling) clearInterval(msgrPolling);
                document.getElementById('msgr-emoji-picker').style.display = 'none';
            }
        }

        function switchTab(tab) {
            activeTab = tab;
            document.getElementById('tab-chats').classList.toggle('active', tab === 'chats');
            document.getElementById('tab-users').classList.toggle('active', tab === 'users');
            loadSidebarItems();
        }

        async function loadSidebarItems() {
            const sidebar = document.getElementById('msgr-sidebar-list');
            sidebar.innerHTML = '<div class="text-center small py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
            
            try {
                const action = activeTab === 'chats' ? 'get_conversations' : 'get_all_users';
                const response = await fetch(`${handlerPath}?action=${action}`);
                const items = await response.json();
                sidebar.innerHTML = '';
                
                if (items.length === 0) {
                    sidebar.innerHTML = '<div class="text-center small opacity-50 px-2 mt-3">No items found</div>';
                    return;
                }

                items.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'msgr-conv-item' + (msgrConvId == item.id ? ' active' : '');
                    div.dataset.id = item.id;
                    
                    let title, imgUrl;
                    
                    if (activeTab === 'users') {
                        // Guest list tab
                        title = item.name || 'Guest';
                        imgUrl = item.profile_image_url || '../../assets/images/avatar/01.jpg';
                    } else {
                        // Conversations tab
                        const isPrivate = item.type === 'private';
                        title = isPrivate ? (item.other_user_name || 'Private Chat') : (item.title || 'Group');
                        imgUrl = isPrivate ? (item.other_user_image || '../../assets/images/avatar/01.jpg') : (item.icon_url || '../../assets/images/e_dossier.png');
                    }
                    
                    div.innerHTML = `
                        <img src="${imgUrl}" class="msgr-avatar" title="${title}">
                        <div class="msgr-conv-label">${title}</div>
                    `;
                    
                    if (activeTab === 'chats') {
                        div.onclick = () => selectConversation(item.id, title, div);
                        // Auto-load first one if E-Dossier
                        if (!msgrConvId && title === 'E-Dossier') selectConversation(item.id, title, div);
                    } else {
                        div.onclick = () => startPrivateChat(item.id, title);
                    }
                    
                    sidebar.appendChild(div);
                });
            } catch (err) {
                sidebar.innerHTML = '<div class="text-center small text-danger">Error loading</div>';
            }
        }

        async function startPrivateChat(targetId, name) {
            try {
                const response = await fetch(handlerPath + '?action=start_chat', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `target_id=${targetId}`
                });
                const convId = await response.json();
                
                // Switch to chats tab
                activeTab = 'chats';
                document.getElementById('tab-chats').classList.add('active');
                document.getElementById('tab-users').classList.remove('active');
                
                // Load chats and then select the new one
                await loadSidebarItems();
                selectConversation(convId, name);
            } catch (err) {
                console.error('Start chat error:', err);
            }
        }

        async function markRead(convId) {
            if (!convId) return;
            await fetch(handlerPath + '?action=mark_read', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `conv_id=${convId}`
            });
            updateBadgeCount();
        }

        function selectConversation(id, title, elem) {
            msgrConvId = id;
            document.getElementById('msgr-active-title').innerText = title;
            
            // UI highlight
            document.querySelectorAll('.msgr-conv-item').forEach(el => el.classList.remove('active'));
            if (elem) {
                elem.classList.add('active');
            } else {
                // Find by ID in the list if no element provided
                const items = document.querySelectorAll('.msgr-conv-item');
                items.forEach(item => {
                    if (item.dataset.id == id) item.classList.add('active');
                });
            }
            
            // Update header icon
            const img = elem ? elem.querySelector('.msgr-avatar') : document.querySelector(`.msgr-conv-item[data-id="${id}"] .msgr-avatar`);
            document.getElementById('msgr-header-icon').src = img ? img.src : '../../assets/images/e_dossier.png';
            
            document.getElementById('msgr-chat-body').innerHTML = '';
            fetchMessages();
            markRead(id);
            
            if (msgrPolling) clearInterval(msgrPolling);
            msgrPolling = setInterval(fetchMessages, 3000);
        }

        async function fetchMessages() {
            if (!msgrConvId) return;
            try {
                const response = await fetch(`${handlerPath}?action=get_messages&conv_id=${msgrConvId}`);
                const messages = await response.json();
                renderMessages(messages);
            } catch (err) {
                console.error('Fetch messages error:', err);
            }
        }

        function renderMessages(messages) {
            const body = document.getElementById('msgr-chat-body');
            const atBottom = body.scrollHeight - body.scrollTop <= body.clientHeight + 100;
            body.innerHTML = '';
            
            if (!Array.isArray(messages)) return;

            messages.forEach(msg => {
                const bubble = document.createElement('div');
                const isMine = msg.sender_id == msgrUserId;
                bubble.className = `msgr-bubble ${isMine ? 'sent' : 'received'}`;
                
                if (msg.type === 'system') {
                    bubble.className = 'msgr-bubble system';
                    bubble.innerText = msg.content;
                } else if (msg.type === 'file') {
                    if (!isMine) {
                        const name = document.createElement('div');
                        name.className = 'msgr-sender-name';
                        name.innerText = msg.sender_name || 'User';
                        bubble.appendChild(name);
                    }
                    if (msg.content.match(/\.(jpg|jpeg|png|gif)$/i)) {
                        bubble.innerHTML += `<img src="${msg.content}" style="max-width:100%; border-radius:10px;">`;
                    } else {
                        bubble.innerHTML += `<a href="${msg.content}" target="_blank" style="color:inherit;"><i class="bi bi-file-earmark-arrow-down"></i> File</a>`;
                    }
                } else {
                    if (!isMine) {
                        const name = document.createElement('div');
                        name.className = 'msgr-sender-name';
                        name.innerText = msg.sender_name || 'User';
                        bubble.appendChild(name);
                    }
                    const text = document.createElement('div');
                    text.innerText = msg.content;
                    bubble.appendChild(text);
                }
                body.appendChild(bubble);
            });
            if (atBottom) body.scrollTop = body.scrollHeight;
        }

        async function sendMessage() {
            const input = document.getElementById('msgr-text-input');
            if (!input.value.trim() || !msgrConvId) return;
            const val = input.value.trim();
            input.value = '';
            try {
                const response = await fetch(handlerPath + '?action=send', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `conv_id=${msgrConvId}&content=${encodeURIComponent(val)}`
                });
                const result = await response.json();
                if (result.status === 'error') {
                    alert('Moderation: ' + result.message);
                }
                fetchMessages();
            } catch (err) {
                console.error('Send error:', err);
            }
        }

        async function uploadChatFile() {
            const fileInput = document.getElementById('msgr-file-input');
            if (!fileInput.files.length || !msgrConvId) return;
            const formData = new FormData();
            formData.append('chat_file', fileInput.files[0]);
            formData.append('conv_id', msgrConvId);
            try {
                await fetch(handlerPath + '?action=upload_file', { method: 'POST', body: formData });
                fetchMessages();
            } catch (err) {}
        }

        function showGroupModal() {
            document.getElementById('msgr-group-modal').style.display = 'flex';
            loadUsersForSelection();
        }
        function hideGroupModal() { document.getElementById('msgr-group-modal').style.display = 'none'; }

        async function loadUsersForSelection() {
            const response = await fetch(`${handlerPath}?action=get_all_users`);
            const users = await response.json();
            const list = document.getElementById('msgr-user-selection');
            list.innerHTML = '';
            users.forEach(u => {
                const d = document.createElement('div');
                d.className = 'd-flex align-items-center mb-2';
                d.innerHTML = `<input type="checkbox" class="msgr-check me-2" value="${u.id}"> <img src="${u.profile_image_url || '../../assets/images/avatar/01.jpg'}" class="rounded-circle me-2" width="30" height="30"> <span class="msgr-check-label">${u.name}</span>`;
                list.appendChild(d);
            });
        }

        async function createNewGroup() {
            const name = document.getElementById('msgr-new-group-name').value;
            const participants = Array.from(document.querySelectorAll('.msgr-check:checked')).map(el => el.value);
            await fetch(handlerPath + '?action=create_group', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `title=${encodeURIComponent(name)}&participants=${JSON.stringify(participants)}`
            });
            hideGroupModal();
            switchTab('chats');
        }

        async function updateBadgeCount() {
            // Only update badge if panel is closed
            const isOpen = document.getElementById('messenger-panel').classList.contains('open');
            if (isOpen) {
                updateBadge(0);
                return;
            }
            
            const response = await fetch(`${handlerPath}?action=get_unread_count`);
            const data = await response.json();
            updateBadge(data.count);
        }

        function updateBadge(count) {
            const b = document.getElementById('msgr-notification-badge');
            if (count > 0) { b.innerText = count > 9 ? '9+' : count; b.style.display = 'flex'; }
            else { b.style.display = 'none'; }
        }

        document.getElementById('msgr-send-btn').onclick = sendMessage;
        document.getElementById('msgr-text-input').onkeypress = (e) => { if (e.key === 'Enter') sendMessage(); };
        
        setInterval(updateBadgeCount, 10000);
        updateBadgeCount();
    </script>
    <?php
    return ob_get_clean();
}
?>
