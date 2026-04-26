<?php
// view/comments/comments.php - Unified Engagement Hub (Controlle de Saisie)
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../controller/CommentC.php';
$commentCtrl = new CommentC();

// Handle "Add Comment" POST logic (Merged from add.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $contenu        = trim($_POST['contenu'] ?? '');
    $auteur         = trim($_POST['auteur'] ?? '');
    $publication_id = $_POST['publication_id'] ?? '';

    $errors = [];
    if (empty($contenu)) $errors[] = "Comment content is required for verification.";
    elseif (strlen($contenu) < 5) $errors[] = "Feedback must be at least 5 characters.";
    
    if (empty($auteur)) $errors[] = "Actor identification is mandatory.";
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) $errors[] = "Identification string must be alphabetic.";

    if (empty($errors)) {
        $commentCtrl->addComment($contenu, $auteur, $publication_id);
        header("Location: " . $_SERVER['REQUEST_URI']); // Refresh to show new comment
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_comment'] = $_POST;
    }
}

// Fetch list and stats for rendering
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'date_desc';

$list = $commentCtrl->getAllComments($search, $sort);
$stats = $commentCtrl->getCommentStats();

// Delegate to Unified Design Layer
include __DIR__ . '/../design/comments/comments.php';
?>
