<?php
// view/publications/delete.php - Secure Asset Removal Protocol
include_once __DIR__ . '/../../CONTROLLER/PublicationC.php';

$id = $_GET['id'] ?? '';
if (!empty($id)) {
    $pubCtrl = new PublicationC();
    $pubCtrl->deletePublication($id);
}

// Redirect back to dashboard matrix
header('Location: /Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php');
exit();
?>
