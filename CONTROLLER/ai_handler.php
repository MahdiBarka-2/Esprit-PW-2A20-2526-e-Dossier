<?php
require_once __DIR__ . '/PublicationC.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ai_assist') {
    $ctrl = new PublicationC();
    $ctrl->aiAssistAction();
}
?>
