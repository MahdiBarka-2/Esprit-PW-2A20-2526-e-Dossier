<?php include __DIR__ . '/../../view/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body p-5">
                    <h4 class="mb-4 text-primary">
                        <i class="fas fa-comment me-2"></i>Add a Comment
                    </h4>

                    <?php if(isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach($_SESSION['errors'] as $e): ?>
                                <p class="mb-0"><?= htmlspecialchars($e) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/projetweb/index1.php?action=storeComment" novalidate>
                        <input type="hidden" name="publication_id" value="<?= htmlspecialchars($publication_id) ?>">

                        <div class="mb-3">
                            <label class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="auteur" class="form-control bg-secondary text-white border-0"
                                   value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? '') ?>"
                                   placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea name="contenu" class="form-control bg-secondary text-white border-0" rows="5"
                                      placeholder="Write your comment here..."><?= htmlspecialchars($_SESSION['old']['contenu'] ?? '') ?></textarea>
                        </div>

                        <?php unset($_SESSION['old']); ?>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Submit Comment
                        </button>
                        <a href="/projetweb/index1.php?action=show&id=<?= htmlspecialchars($publication_id) ?>" class="btn btn-secondary w-100 mt-2">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../view/layouts/footer.php'; ?>