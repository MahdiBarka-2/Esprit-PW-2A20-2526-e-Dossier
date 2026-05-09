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
                    fetch('../../CONTROLLER/LocationController.php?action=update', {
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
require_once '../../CONTROLLER/VoiceController.php';
echo renderVocalAssistant($lang ?? 'en');
?>

<!-- AI Chat Assistant -->
<?php 
require_once '../../CONTROLLER/ChatController.php'; 
echo renderChatAssistant(); 
?>

<!-- AI Gesture Mouse -->
<?php 
require_once '../../CONTROLLER/GestureController.php'; 
echo renderGestureCursor(); 
?>

<!-- Messenger Floating Widget -->
<?php
require_once '../../CONTROLLER/MessengerWidget.php';
echo renderMessengerWidget();
?>

</body>
</html>
