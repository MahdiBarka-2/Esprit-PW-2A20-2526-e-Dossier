<?php
// view/comments/delete.php - Secure Feedback Redaction Protocol
include_once __DIR__ . '/../../controller/CommentC.php';

$id = $_GET['id'] ?? '';
$pub_id = $_GET['publication_id'] ?? '';

if (!empty($id)) {
    $commentCtrl = new CommentC();
    $commentCtrl->deleteComment($id);
}

// Logic for redirection based on origin
if (isset($_GET['from']) && $_GET['from'] === 'admin') {
    header("Location: /projetweb/view/back-office/index.php?action=comments");
} else {
    header("Location: /projetweb/index1.php?action=show&id=$pub_id");
}
exit();
?>
