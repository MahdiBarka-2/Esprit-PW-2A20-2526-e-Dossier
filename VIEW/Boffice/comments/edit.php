<?php
// VIEW/Boffice/comments/edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../CONTROLLER/CommentC.php';
$commentCtrl = new CommentC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = $_POST['id'] ?? '';
    $contenu        = trim($_POST['contenu'] ?? '');
    $auteur         = trim($_POST['auteur'] ?? '');

    $errors = [];
    if (empty($contenu)) $errors[] = "Comment content is required.";
    elseif (strlen($contenu) < 5) $errors[] = "Comment must be at least 5 characters.";

    if (empty($errors)) {
        $commentCtrl->updateComment($id, $contenu, $auteur);
        header("Location: /integration/VIEW/Boffice/posts.php?action=comments");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
    }
}

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$comment = $commentCtrl->getOneComment($id);

if (!$comment) {
    header("Location: /integration/VIEW/Boffice/posts.php?action=comments");
    exit();
}

require_once __DIR__ . "/../header.php"; 
?>

<div class="page-content-wrapper p-xxl-4">
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="h3 mb-0">Edit Citizen Comment</h1>
                <p class="text-muted mb-0">Moderating feedback for publication #<?= $comment['publication_id'] ?></p>
            </div>
        </div>

        <div class="card shadow-lg rounded-4 border-0 p-4 p-md-5">
            <?php if(isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger p-3 rounded-3 mb-4">
                    <ul class="mb-0 small">
                        <?php foreach($_SESSION['errors'] as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold">Author</label>
                        <input type="text" name="auteur" class="form-control bg-light border-0" value="<?= htmlspecialchars($comment['auteur']) ?>" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label h6 fw-bold">Comment Content</label>
                        <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="8"><?= htmlspecialchars($comment['contenu']) ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                        <a href="/integration/VIEW/Boffice/posts.php?action=comments" class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Update Comment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php require_once __DIR__ . "/../footer.php"; ?>
