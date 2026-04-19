<?php include __DIR__ . '/../view/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-4">Add Publication</h4>

                    <?php if(isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach($_SESSION['errors'] as $e): ?>
                                <p class="mb-0"><?= htmlspecialchars($e) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/projetweb/back-office/index.php?action=store" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="titre" class="form-control <?= isset($_SESSION['old']) && empty($_SESSION['old']['titre']) ? 'is-invalid' : '' ?>" 
                                   value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? '') ?>">
                            <div class="invalid-feedback">Title is required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea name="contenu" class="form-control <?= isset($_SESSION['old']) && empty($_SESSION['old']['contenu']) ? 'is-invalid' : '' ?>" 
                                      rows="5"><?= htmlspecialchars($_SESSION['old']['contenu'] ?? '') ?></textarea>
                            <div class="invalid-feedback">Content is required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author <span class="text-danger">*</span></label>
                            <input type="text" name="auteur" class="form-control <?= isset($_SESSION['old']) && empty($_SESSION['old']['auteur']) ? 'is-invalid' : '' ?>"
                                   value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? '') ?>">
                            <div class="invalid-feedback">Author is required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control <?= isset($_SESSION['old']) && empty($_SESSION['old']['date']) ? 'is-invalid' : '' ?>"
                                   value="<?= htmlspecialchars($_SESSION['old']['date'] ?? date('Y-m-d')) ?>">
                            <div class="invalid-feedback">Date is required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="categorie" class="form-control <?= isset($_SESSION['old']) && empty($_SESSION['old']['categorie']) ? 'is-invalid' : '' ?>">
                                <option value="">-- Select Category --</option>
                                <option value="Announcement" <?= ($_SESSION['old']['categorie'] ?? '') == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                                <option value="Law" <?= ($_SESSION['old']['categorie'] ?? '') == 'Law' ? 'selected' : '' ?>>Law</option>
                                <option value="Report" <?= ($_SESSION['old']['categorie'] ?? '') == 'Report' ? 'selected' : '' ?>>Report</option>
                            </select>
                            <div class="invalid-feedback">Category is required.</div>
                        </div>
                        <?php unset($_SESSION['old']); ?>
                        <button type="submit" class="btn btn-primary w-100">Save Publication</button>
                        <a href="/projetweb/back-office/index.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../view/layouts/footer.php'; ?>