<?php
// VIEW/Frontoffice/comments/edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once dirname(dirname(dirname(__DIR__))) . '/CONTROLLER/CommentC.php';
$commentCtrl = new CommentC();

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
        header("Location: /integration/VIEW/index1.php?action=show&id=$publication_id");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
    }
}

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$comment = $commentCtrl->getOneComment($id);

if (!$comment) {
    header("Location: /integration/VIEW/index1.php");
    exit();
}

require_once dirname(dirname(dirname(__DIR__))) . '/CONTROLLER/LanguageController.php';
$id = $_GET['id'] ?? $_POST['id'] ?? '';
$comment = $commentCtrl->getOneComment($id);

if (!$comment) {
    header("Location: /integration/VIEW/index1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?? 'en'; ?>" <?php echo (($lang ?? '') === 'ar' ? 'dir="rtl"' : ''); ?>>
<head>
    <title>e_dossier - Edit Comment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="/integration/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/integration/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/integration/assets/vendor/bootstrap-icons/bootstrap-icons.css">
</head>
<body>
    <header class="py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="/integration/VIEW/Frontoffice/index.php">
                <img src="/integration/assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
        </div>
    </header>

<main>
<section class="pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mt-sm-8 mb-4 text-center">
                <div class="d-inline-flex align-items-center">
                    <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="bi bi-pencil-square"></i></div>
                    <div class="text-start">
                        <h1 class="h3 mb-1">Edit Comment</h1>
                        <p class="mb-0 text-muted">Refine your feedback.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
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
                        <input type="hidden" name="publication_id" value="<?= $comment['publication_id'] ?>">

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Author</label>
                                <div class="alert alert-light border p-3 rounded-3 d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-4 text-primary me-3"></i>
                                    <div>
                                        <span class="fw-bold"><?= htmlspecialchars($comment['utilisateur'] ?? 'User') ?></span>
                                    </div>
                                </div>
                                <input type="hidden" name="utilisateur" value="<?= htmlspecialchars($comment['utilisateur']) ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Comment Text</label>
                                <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="8"><?= htmlspecialchars($comment['contenu']) ?></textarea>
                            </div>

                            <div class="col-12 d-sm-flex justify-content-between align-items-center mt-4">
                                <a href="/integration/VIEW/index1.php?action=show&id=<?= $comment['publication_id'] ?>" class="btn btn-link text-muted px-0 fw-bold">
                                    <i class="bi bi-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Update Comment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</main>

<footer class="py-5 mt-5" style="background-color: #1d3b53; color: white;">
    <div class="container text-center">
        <p class="mb-0 small opacity-50">&copy; 2026 e_dossier. All rights reserved.</p>
    </div>
</footer>

<script src="/integration/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
