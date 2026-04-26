<?php
// view/publications/delete.php - Secure Asset Removal Protocol
include_once __DIR__ . '/../../controller/PublicationC.php';

$id = $_GET['id'] ?? '';
if (!empty($id)) {
    $pubCtrl = new PublicationC();
    $pubCtrl->deletePublication($id);
}

// Redirect back to dashboard matrix
header('Location: /projetweb/view/back-office/index.php');
exit();
?>
