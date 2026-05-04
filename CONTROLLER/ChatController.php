<?php
/**
 * Controller for the Refined Integrated 3D Chat Panel (v2)
 */
function renderChatAssistant()
{
    ob_start();
    ?>
    <!-- Chat Assistant Styles -->
    <style>
        #chat-assistant-toggle {
            position: fixed;
            bottom: 110px;
            right: 30px;
            z-index: 10000;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid white;
            font-size: 1.8rem;
            animation: chat-float 3.5s ease-in-out infinite;
        }

        @keyframes chat-float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-8px) scale(1.05);
            }
        }

        #chat-right-panel {
            position: fixed;
            top: 50px; /* Shifted slightly up from 70px */
            right: 100px;
            width: 32vw;
            height: calc(100vh - 70px); /* Taller height, reaching closer to the bottom */
            background: #0b0a12;
            z-index: 20000;
            transform: translateX(calc(100% + 150px));
            transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: -10px 10px 40px rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 30px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #chat-right-panel.open {
            transform: translateX(0);
        }

        #chat-panel-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            transition: filter 1.2s ease;
        }

        #chat-panel-bg.blurred {
            filter: blur(12px) brightness(0.6);
        }

        .chat-messages-scroll {
            position: relative;
            z-index: 10;
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 25px;
            padding-right: 5px;
            display: none;
            /* Invisible until first message */
            flex-direction: column;
            gap: 15px;
            scrollbar-width: none;
        }

        .chat-messages-scroll::-webkit-scrollbar {
            display: none;
        }

        .msg-bubble {
            padding: 12px 16px;
            border-radius: 15px;
            font-size: 0.9rem;
            max-width: 90%;
            line-height: 1.4;
            animation: msg-fade-in 0.3s ease forwards;
        }

        @keyframes msg-fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-bubble.bot {
            background: rgba(255, 255, 255, 0.1);
            color: #f5f5dc;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
        }

        .msg-bubble.user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }

        .chat-panel-footer {
            position: relative;
            z-index: 20;
            margin-top: auto;
            padding-bottom: 30px;
            transform: translateY(50px);
            /* Moved to the top with 6px as requested */
            display: flex;
            flex-direction: column;
            gap: 15px;
            pointer-events: auto;
        }

        .chat-input-wrapper {
            display: flex;
            align-items: center;
            background: rgba(44, 44, 44, 0.6); /* Dark grey transparent */
            border: 2px solid rgba(255, 255, 255, 0.3); /* Balanced border */
            border-radius: 12px;
            padding: 8px 18px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Deeper shadow for dark style */
        }

        .chat-input-wrapper:focus-within {
            border-color: #f5f5dc; 
            background: rgba(55, 55, 55, 0.8); /* Slightly more solid dark grey on focus */
            box-shadow: 0 0 20px rgba(245, 245, 220, 0.2);
        }

        #chat-panel-input {
            flex-grow: 1;
            background: transparent;
            border: none;
            padding: 12px 5px;
            color: white;
            outline: none;
            font-size: 1.1rem;
        }

        #chat-panel-input::placeholder {
            color: rgba(245, 245, 220, 0.7); /* Beige placeholder */
        }

        .chat-send-btn-minimal {
            background: none;
            border: none;
            color: #f5f5dc;
            /* Beige */
            font-size: 1.4rem;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-send-btn-minimal:hover {
            transform: scale(1.1);
            color: white;
        }

        .chat-back-btn-dark {
            flex-grow: 1;
            padding: 18px;
            background: #2c2c2c;
            /* Dark Grey */
            color: #ffffff !important;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .chat-back-btn-dark:hover {
            background: #404040;
            transform: translateY(-2px);
        }
    </style>

    <!-- Floating Toggle Button -->
    <div id="chat-assistant-toggle" onclick="toggleChatPanel()" title="Open 3D Assistant">
        <i class="fas fa-comment-dots"></i>
    </div>

    <!-- The Refined 32% Width Integrated Panel -->
    <div id="chat-right-panel">
        <div id="chat-panel-bg">
            <script type="module" src="https://unpkg.com/@splinetool/viewer@1.12.86/build/spline-viewer.js"></script>
            <spline-viewer url="https://prod.spline.design/4znDRl9fX535felp/scene.splinecode"></spline-viewer>
        </div>

        <div class="chat-messages-scroll" id="chat-messages-area">
            <!-- Messages appear here dynamicly after first interaction -->
        </div>


        <div class="chat-panel-footer">
            <div class="chat-input-wrapper">
                <input type="text" id="chat-panel-input" placeholder="Type a message..." autocomplete="off">
                <button class="chat-send-btn-minimal" onclick="handleRefinedSend()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

            <div style="display: flex; align-items: center; gap: 10px;">
                <button class="chat-back-btn-dark" onclick="toggleChatPanel()">
                    <i class="fas fa-arrow-left me-2"></i> Back to Website
                </button>
            </div>
        </div>
    </div>

    <script>
        let hasInteracted = false;

        function toggleChatPanel() {
            const panel = document.getElementById('chat-right-panel');
            panel.classList.toggle('open');
        }

        const msgArea = document.getElementById('chat-messages-area');
        const inputField = document.getElementById('chat-panel-input');
        const bgContainer = document.getElementById('chat-panel-bg');

        function handleRefinedSend() {
            const val = inputField.value.trim();
            if (!val) return;

            // Trigger Effects on First Interaction
            if (!hasInteracted) {
                bgContainer.classList.add('blurred');
                msgArea.style.display = 'flex'; // Show message area
                hasInteracted = true;
            }

            // Add Messages
            renderBubble(val, 'user');
            inputField.value = '';

            setTimeout(() => {
                renderBubble("I'm processing your request. How else can I help?", 'bot');
            }, 800);
        }

        function renderBubble(text, type) {
            const bubble = document.createElement('div');
            bubble.className = `msg-bubble ${type}`;
            bubble.innerText = text;
            msgArea.appendChild(bubble);
            msgArea.scrollTop = msgArea.scrollHeight;
        }

        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleRefinedSend();
        });
    </script>
    <?php
    return ob_get_clean();
}
