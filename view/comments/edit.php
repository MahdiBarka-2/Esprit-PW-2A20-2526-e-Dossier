<?php
// view/comments/edit.php - CONTROLLE DE SAISIE (Logic Layer)
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($commentCtrl)) {
    include_once __DIR__ . '/../../controller/CommentC.php';
    $commentCtrl = new CommentC();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = $_POST['id'] ?? '';
    $contenu        = trim($_POST['contenu'] ?? '');
    $auteur         = trim($_POST['auteur'] ?? '');
    $publication_id = $_POST['publication_id'] ?? '';

    $errors = [];
    if (empty($contenu)) $errors[] = "Comment content is required.";
    elseif (strlen($contenu) < 5) $errors[] = "Comment must be at least 5 characters.";

    if (empty($errors)) {
        $commentCtrl->updateComment($id, $contenu, $auteur);
        header("Location: /projetweb/index1.php?action=show&id=$publication_id");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
    }
}

// Prepare data for design
$id = $_GET['id'] ?? $_POST['id'] ?? '';
$comment = $commentCtrl->getOneComment($id);

if (!$comment) {
    header("Location: /projetweb/index1.php");
    exit();
}

// Delegate to Design Layer
include __DIR__ . '/../design/comments/edit.php';
?>
