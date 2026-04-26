<?php
// view/comments/add.php - CONTROLLE DE SAISIE (Logic Layer)
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../controller/CommentC.php';
$commentCtrl = new CommentC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenu        = trim($_POST['contenu'] ?? '');
    $auteur         = trim($_POST['auteur'] ?? '');
    $publication_id = $_POST['publication_id'] ?? '';

    $errors = [];
    if (empty($contenu)) $errors[] = "Comment content is required.";
    elseif (strlen($contenu) < 5) $errors[] = "Comment must be at least 5 characters.";
    if (empty($auteur)) $errors[] = "Author name is required.";
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) $errors[] = "Author name can only contain letters and spaces.";
    if (empty($publication_id)) $errors[] = "Publication ID is missing.";

    if (empty($errors)) {
        $commentCtrl->addComment($contenu, $auteur, $publication_id);
        header("Location: /projetweb/index1.php?action=show&id=$publication_id");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
    }
}

// Delegate to Design Layer
include __DIR__ . '/../design/comments/add.php';
?>
