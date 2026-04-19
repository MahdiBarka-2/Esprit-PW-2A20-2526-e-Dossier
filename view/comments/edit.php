<?php include __DIR__ . '/../../view/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0 bg-dark text-white">
                <div class="card-header bg-warning">
                    <h4 class="mb-0 text-dark">
                        <i class="fas fa-edit me-2"></i>Edit Comment
                    </h4>
                </div>
                <div class="card-body p-5">

                    <?php if(isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach($_SESSION['errors'] as $e): ?>
                                <p class="mb-0"><?= htmlspecialchars($e) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/projetweb/index1.php?action=updateComment" novalidate>
                        <input type="hidden" name="id" value="<?= $commentData['id'] ?>">
                        <input type="hidden" name="publication_id" value="<?= $commentData['publication_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="auteur" class="form-control bg-secondary text-white border-0"
                                   value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? $commentData['auteur']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea name="contenu" class="form-control bg-secondary text-white border-0" rows="5"><?= htmlspecialchars($_SESSION['old']['contenu'] ?? $commentData['contenu']) ?></textarea>
                        </div>

                        <?php unset($_SESSION['old']); ?>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Update Comment
                        </button>
                        <a href="/projetweb/index1.php?action=show&id=<?= $commentData['publication_id'] ?>" class="btn btn-secondary ms-2">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../view/layouts/footer.php'; ?>