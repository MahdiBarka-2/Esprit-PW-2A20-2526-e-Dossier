<?php 
// view/design/publications/edit.php - Institutional Design Layer
include __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="h3 mb-0">Edit Publication</h1>
                <p class="text-muted mb-0">Modifying publication #<?= str_pad($publication['id'], 5, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger rounded-4 border-0 shadow p-4 mb-4">
                <h6 class="alert-heading fw-bold">Validation Error</h6>
                <ul class="mb-0">
                    <?php foreach($_SESSION['errors'] as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= $publication['id'] ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Title</label>
                        <input type="text" name="titre" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($publication['titre']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Category</label>
                        <select name="categorie" class="form-select form-select-lg bg-light border-0">
                            <option value="Announcement" <?= $publication['categorie'] == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                            <option value="Law" <?= $publication['categorie'] == 'Law' ? 'selected' : '' ?>>Law</option>
                            <option value="Report" <?= $publication['categorie'] == 'Report' ? 'selected' : '' ?>>Report</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Author</label>
                        <input type="text" name="auteur" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($publication['auteur']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Date</label>
                        <input type="date" name="date" class="form-control form-control-lg bg-light border-0" value="<?= $publication['date'] ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Content</label>
                        <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="12"><?= htmlspecialchars($publication['contenu']) ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                        <a href="/projetweb/view/back-office/index.php" class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Update Publication</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
