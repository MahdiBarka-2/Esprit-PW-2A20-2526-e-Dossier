    <!-- FOOTER START -->
    </div> <!-- Close page-content -->
</main>


<?php
require_once '../../CONTROLLER/VoiceController.php';
require_once '../../CONTROLLER/ChatController.php';
echo renderVocalAssistant($lang ?? 'en');
echo renderChatAssistant();
?>

</body>
</html>

