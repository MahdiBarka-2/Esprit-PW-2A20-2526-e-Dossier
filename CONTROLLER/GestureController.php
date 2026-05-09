<?php
/**
 * GestureController - Real-time Finger Tracking and Gesture Mouse Control
 */
function renderGestureCursor() {
    ob_start();
    ?>
    <!-- Mediapipe Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/hands/hands.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils/camera_utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@mediapipe/drawing_utils/drawing_utils.js"></script>

    <style>
        #gesture-cursor {
            position: fixed;
            width: 20px;
            height: 20px;
            background: rgba(6, 106, 201, 0.5);
            border: 2px solid #fff;
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            display: none;
            box-shadow: 0 0 15px rgba(6, 106, 201, 0.8);
            transition: transform 0.1s ease;
        }
        #gesture-cursor.clicking {
            transform: scale(0.6);
            background: rgba(220, 53, 69, 0.8);
        }
        #topbar-gesture-toggle.active {
            background: #28a745 !important;
            color: white !important;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }
        #gesture-video-container {
            position: fixed;
            bottom: 90px;
            right: 210px;
            width: 150px;
            height: 112px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid #066ac9;
            display: none;
            z-index: 998;
            background: #000;
        }
        #gesture-video {
            width: 100%;
            height: 100%;
            transform: scaleX(-1);
        }
    </style>

    <div id="gesture-cursor"></div>
    <div id="gesture-video-container">
        <video id="gesture-video" autoplay muted></video>
    </div>

    <script>
        let gestureActive = localStorage.getItem('gestureActive') === 'true';
        const cursorEl = document.getElementById('gesture-cursor');
        const statusIcon = document.getElementById('topbar-gesture-toggle');
        const videoContainer = document.getElementById('gesture-video-container');
        const videoEl = document.getElementById('gesture-video');
        
        let hands;
        let camera;

        // Auto-start if it was active on the last page
        document.addEventListener('DOMContentLoaded', () => {
            if (gestureActive) {
                statusIcon.classList.add('active');
                cursorEl.style.display = 'block';
                // Keep videoContainer hidden as requested, but we need the video element to work
                videoContainer.style.display = 'none'; 
                initHandsTracking();
            }
        });

        async function toggleGestureMode() {
            gestureActive = !gestureActive;
            localStorage.setItem('gestureActive', gestureActive);
            
            statusIcon.classList.toggle('active');
            cursorEl.style.display = gestureActive ? 'block' : 'none';

            if (gestureActive) {
                if (!hands) {
                    initHandsTracking();
                } else if (camera) {
                    await camera.start();
                }
            } else {
                if (camera) await camera.stop();
            }
        }

        function initHandsTracking() {
            hands = new Hands({
                locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
            });

            hands.setOptions({
                maxNumHands: 1,
                modelComplexity: 1,
                minDetectionConfidence: 0.7,
                minTrackingConfidence: 0.7
            });

            hands.onResults(onHandResults);

            camera = new Camera(videoEl, {
                onFrame: async () => {
                    if (gestureActive) await hands.send({image: videoEl});
                },
                width: 640,
                height: 480
            });
            camera.start();
        }

        let lastClickTime = 0;
        function onHandResults(results) {
            if (!gestureActive) return;

            if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
                const landmarks = results.multiHandLandmarks[0];
                
                // Track Index Finger Tip (Landmark 8)
                const indexTip = landmarks[8];
                const thumbTip = landmarks[4];

                // Smooth mapping
                const x = (1 - indexTip.x) * window.innerWidth;
                const y = indexTip.y * window.innerHeight;

                cursorEl.style.left = x + 'px';
                cursorEl.style.top = y + 'px';

                // Detect Pinch (Distance between index and thumb)
                const distance = Math.sqrt(
                    Math.pow(indexTip.x - thumbTip.x, 2) + 
                    Math.pow(indexTip.y - thumbTip.y, 2)
                );

                if (distance < 0.05) { // Pinch detected
                    cursorEl.classList.add('clicking');
                    const now = Date.now();
                    if (now - lastClickTime > 800) { // Slightly faster throttle
                        simulateClick(x, y);
                        lastClickTime = now;
                    }
                } else {
                    cursorEl.classList.remove('clicking');
                }
            }
        }

        function simulateClick(x, y) {
            const el = document.elementFromPoint(x, y);
            if (el) {
                el.click();
                // Visual feedback
                const ripple = document.createElement('div');
                ripple.className = 'gesture-ripple';
                ripple.style.left = (x - 15) + 'px';
                ripple.style.top = (y - 15) + 'px';
                document.body.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            }
        }
    </script>
    <style>
        .gesture-ripple {
            position: fixed;
            width: 30px;
            height: 30px;
            border: 3px solid #ff4757;
            border-radius: 50%;
            pointer-events: none;
            z-index: 10001;
            animation: ripple-out 0.6s ease-out forwards;
        }
        @keyframes ripple-out {
            from { transform: scale(1); opacity: 1; }
            to { transform: scale(4); opacity: 0; }
        }
    </style>
    <?php
    return ob_get_clean();
}
