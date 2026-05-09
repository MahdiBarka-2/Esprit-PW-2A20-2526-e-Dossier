    <!-- FOOTER START -->
    </div> <!-- Close page-content -->
</main>

<?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'employee' || $_SESSION['role'] === 'agent')): ?>
    <!-- Background GPS Tracking for Agents -->
    <script>
        if ("geolocation" in navigator) {
            function sendLocation() {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const data = {
                        agent_id: <?php echo $_SESSION['user_id'] ?? 0; ?>,
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    fetch('../../CONTROLLER/LocationCONTROLLER.php?action=update', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                }, null, { enableHighAccuracy: true });
            }
            // Send location every 0.1 seconds (100ms)
            setInterval(sendLocation, 100);
            sendLocation(); 
        }
    </script>
<?php endif; ?>

<?php
require_once '../../CONTROLLER/VoiceCONTROLLER.php';
echo renderVocalAssistant($lang ?? 'en');
?>

<!-- AI Chat Assistant -->
<?php 
require_once '../../CONTROLLER/ChatCONTROLLER.php'; 
echo renderChatAssistant(); 
?>

<!-- AI Gesture Mouse -->
<?php 
require_once '../../CONTROLLER/GestureCONTROLLER.php'; 
echo renderGestureCursor(); 
?>

<!-- Messenger Floating Widget -->
<?php
require_once '../../CONTROLLER/MessengerWidget.php';
echo renderMessengerWidget();
?>

<!-- Essential JS -->
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../assets/js/functions.js"></script>
</body>
</html>
