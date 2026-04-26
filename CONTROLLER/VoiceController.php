<?php
require_once __DIR__ . '/../MODEL/VoiceAssistant.php';

/**
 * Functional Controller for Vocal Assistance
 * Renders the JavaScript and CSS for the vocal assistant.
 * 
 * @param string $lang Current language of the site (en, fr, ar)
 * @return string HTML/JS/CSS block
 */
function renderVocalAssistant($lang = 'en') {
    $assistant = new VoiceAssistant($lang);
    
    // Configuration from Model (direct property access)
    $rate = $assistant->rate;
    $pitch = $assistant->pitch;
    $volume = $assistant->volume;
    
    // Map language codes to TTS locales
    $voiceLang = 'en-US';
    if ($lang === 'fr') $voiceLang = 'fr-FR';
    if ($lang === 'ar') $voiceLang = 'ar-SA';

    ob_start();
    ?>
    <!-- Cute Vocal Assistant - UI Components -->
    <style>
        #vocal-assistant-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 10000;
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); /* Cute Pink Gradient */
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(255, 154, 158, 0.4);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid white;
            font-size: 1.8rem;
            animation: cute-float 3s ease-in-out infinite;
        }
        @keyframes cute-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        #vocal-assistant-toggle.active {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); /* Cute Purple Gradient */
            box-shadow: 0 8px 20px rgba(161, 140, 209, 0.4);
            animation: cute-bounce 0.5s ease-in-out;
        }
        @keyframes cute-bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        #vocal-assistant-toggle:hover {
            transform: scale(1.15) rotate(10deg);
        }
        .vocal-hover-highlight {
            outline: 3px solid #ff9a9e !important;
            background-color: rgba(255, 154, 158, 0.15) !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 0 10px rgba(255, 154, 158, 0.2);
        }
    </style>

    <button id="vocal-assistant-toggle" title="Vocal Assistance (Now with a cute voice!)">
        <i class="fas fa-magic" id="vocal-icon"></i>
    </button>

    <script>
        (function() {
            const config = {
                lang: '<?php echo $voiceLang; ?>',
                rate: <?php echo $rate; ?>,
                pitch: <?php echo $pitch; ?>,
                volume: <?php echo $volume; ?>
            };

            let isActive = false;
            const toggleBtn = document.getElementById('vocal-assistant-toggle');
            const icon = document.getElementById('vocal-icon');
            const synth = window.speechSynthesis;

            toggleBtn.addEventListener('click', () => {
                isActive = !isActive;
                toggleBtn.classList.toggle('active', isActive);
                
                if (isActive) {
                    icon.className = 'fas fa-microphone-alt';
                    // Fix: Use startsWith to handle ar-SA correctly
                    speak(config.lang.startsWith('ar') ? "أهلا! أنا هنا لمساعدتك" : (config.lang.startsWith('fr') ? "Coucou! Je suis là pour t'aider" : "Hi! I'm here to help you"));
                } else {
                    icon.className = 'fas fa-magic';
                    synth.cancel();
                    speak(config.lang.startsWith('ar') ? "إلى اللقاء" : (config.lang.startsWith('fr') ? "Au revoir!" : "Bye bye!"));
                }
            });

            function speak(text) {
                if (!text || (!isActive && !text.toLowerCase().includes("bye") && !text.toLowerCase().includes("hi") && !text.includes("أهلا"))) return;
                
                synth.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                
                // Enhanced Voice Selection
                const voices = synth.getVoices();
                let selectedVoice = voices.find(v => v.lang.startsWith(config.lang));
                
                // Fallback for Arabic (broader check)
                if (!selectedVoice && config.lang.startsWith('ar')) {
                    selectedVoice = voices.find(v => v.lang.startsWith('ar'));
                }

                if (selectedVoice) {
                    utterance.voice = selectedVoice;
                }
                
                utterance.lang = config.lang;
                utterance.rate = config.rate;
                utterance.pitch = config.pitch;
                utterance.volume = config.volume;
                synth.speak(utterance);
            }

            // Fix for Chrome: Ensure voices are initialized
            if (synth.onvoiceschanged !== undefined) {
                synth.onvoiceschanged = () => synth.getVoices();
            }
            // Trigger voice load
            synth.getVoices();

            // Global event listener for hover
            document.addEventListener('mouseover', (e) => {
                if (!isActive) return;
                
                const el = e.target.closest('h1, h2, h3, h4, h5, h6, p, a, button, label, span, input, img, li, td, th, small, strong, em');
                
                if (el && !el.closest('#vocal-assistant-toggle')) {
                    e.stopPropagation();
                    
                    let textToRead = "";
                    if (el.tagName === 'IMG') {
                        textToRead = el.alt || "Image description";
                    } else if (el.tagName === 'INPUT') {
                        textToRead = el.placeholder || el.value || "Input field";
                    } else {
                        textToRead = el.innerText.trim();
                    }

                    if (textToRead && textToRead.length < 500 && textToRead !== el.dataset.lastRead) {
                        el.classList.add('vocal-hover-highlight');
                        speak(textToRead);
                        el.dataset.lastRead = textToRead;
                    }
                }
            });

            document.addEventListener('mouseout', (e) => {
                const el = e.target.closest('h1, h2, h3, h4, h5, h6, p, a, button, label, span, input, img, li, td, th, small, strong, em');
                if (el) {
                    el.classList.remove('vocal-hover-highlight');
                    delete el.dataset.lastRead;
                }
            });
        })();
    </script>
    <?php
    return ob_get_clean();
}
