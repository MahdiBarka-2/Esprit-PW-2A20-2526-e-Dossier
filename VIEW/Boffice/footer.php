    <!-- FOOTER START -->
    </div> <!-- Close page-content -->
</main>


<?php
require_once __DIR__ . '/../../CONTROLLER/VoiceController.php';
require_once __DIR__ . '/../../CONTROLLER/ChatController.php';
echo renderVocalAssistant($lang ?? 'en');
echo renderChatAssistant();
?>

</body>
</html>

