<?php 
// view/design/comments/edit.php - Institutional Design Layer
include __DIR__ . '/../../layouts/header.php'; 
?>

<!-- **************** MAIN CONTENT START **************** -->
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
                <!-- Card START -->
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
                            <!-- Author -->
                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Author (Your Name)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-circle"></i></span>
                                    <input type="text" name="auteur" class="form-control bg-light border-0" value="<?= htmlspecialchars($comment['auteur']) ?>" readonly>
                                </div>
                                <small class="text-muted tiny">Names are permanently linked to the comment.</small>
                            </div>

                            <!-- Content -->
                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Comment Text</label>
                                <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="8"><?= htmlspecialchars($comment['contenu']) ?></textarea>
                            </div>

                            <!-- Controls -->
                            <div class="col-12 d-sm-flex justify-content-between align-items-center mt-4">
                                <a href="/projetweb/index1.php?action=show&id=<?= $comment['publication_id'] ?>" class="btn btn-link text-muted px-0 fw-bold">
                                    <i class="bi bi-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Update Comment</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Card END -->
            </div>
        </div>
    </div>
</section>

</main>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
