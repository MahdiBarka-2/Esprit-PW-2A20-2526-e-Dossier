<?php
// view/publications/show.php - CONTROLLE DE SAISIE (Logic Layer)
if (!isset($pubCtrl)) {
    include_once __DIR__ . '/../../controller/PublicationC.php';
    include_once __DIR__ . '/../../controller/CommentC.php';
    $pubCtrl = new PublicationC();
}

$id = $_GET['id'] ?? '';
$publication = $pubCtrl->getOnePublication($id);

if (!$publication) {
    header("Location: /projetweb/index1.php");
    exit();
}

$commentCtrl = new CommentC();
$comments = $commentCtrl->getComments($publication['id']);

// Delegate to Design Layer
include __DIR__ . '/../design/publications/show.php';
?>
