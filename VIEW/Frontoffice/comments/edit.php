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
        header("Location: /integration/index1.php?action=show&id=$publication_id");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
    }
}

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$comment = $commentCtrl->getOneComment($id);

if (!$comment) {
    header("Location: /integration/index1.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?? 'en'; ?>">
<head>
    <title>e_dossier - Edit Comment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <script>
        const storedTheme = localStorage.getItem('theme')
        const getPreferredTheme = () => {
            if (storedTheme) return storedTheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }
        const setTheme = function (theme) {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }
        setTheme(getPreferredTheme())
    </script>
</head>
<body>
    <header class="navbar-light py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="../index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../../Boffice/index.php">Dashboard</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header> 
?>

<main>
<section class="pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mt-sm-8 mb-4">
                <div class="d-flex align-items-center">
                    <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="fas fa-edit"></i></div>
                    <div>
                        <h1 class="h3 mb-1">Edit Comment</h1>
                        <p class="mb-0 text-muted">Update your comment.</p>
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
                                <label class="form-label h6 fw-bold">Author (Your Name)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-circle"></i></span>
                                    <input type="text" name="auteur" class="form-control bg-light border-0" value="<?= htmlspecialchars($comment['auteur']) ?>" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Comment Text</label>
                                <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="8"><?= htmlspecialchars($comment['contenu']) ?></textarea>
                            </div>

                            <div class="col-12 d-sm-flex justify-content-between align-items-center mt-4">
                                <a href="/integration/index1.php?action=show&id=<?= $comment['publication_id'] ?>" class="btn btn-link text-muted px-0 fw-bold">
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

    <footer class="bg-dark py-4 mt-5">
        <div class="container text-center">
            <p class="text-white mb-0 small">&copy; 2026 Edossier Official Repository.</p>
        </div>
    </footer>
    <script src="../../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
