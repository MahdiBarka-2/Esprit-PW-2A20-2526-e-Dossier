<?php include __DIR__ . '/../view/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">Edit Publication</h4>
                </div>
                <div class="card-body">

                    <?php if(isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach($_SESSION['errors'] as $e): ?>
                                <p class="mb-0"><?= htmlspecialchars($e) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/projetweb/back-office/index.php?action=update" novalidate>
                        <input type="hidden" name="id" value="<?= $publication->getId() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="titre" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? $publication->getTitre()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea name="contenu" class="form-control" rows="4"><?= htmlspecialchars($_SESSION['old']['contenu'] ?? $publication->getContenu()) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author <span class="text-danger">*</span></label>
                            <input type="text" name="auteur" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? $publication->getAuteur()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['old']['date'] ?? $publication->getDate()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="categorie" class="form-control">
                                <option value="">-- Select Category --</option>
                                <option value="Announcement" <?= ($_SESSION['old']['categorie'] ?? $publication->getCategorie()) == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                                <option value="Law" <?= ($_SESSION['old']['categorie'] ?? $publication->getCategorie()) == 'Law' ? 'selected' : '' ?>>Law</option>
                                <option value="Report" <?= ($_SESSION['old']['categorie'] ?? $publication->getCategorie()) == 'Report' ? 'selected' : '' ?>>Report</option>
                            </select>
                        </div>

                        <?php unset($_SESSION['old']); ?>
                        
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="/projetweb/back-office/index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../view/layouts/footer.php'; ?>