<?php 
// view/design/publications/add.php - Institutional Design Layer
include __DIR__ . '/../../layouts/admin_header.php'; 
?>

<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="bi bi-file-earmark-plus"></i></div>
            <div>
                <h1 class="h3 mb-0">Add Publication</h1>
                <p class="text-muted mb-0">Create a new publication in the system.</p>
            </div>
        </div>
        
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4">
                <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Validation Protocol Failed</h6>
                <ul class="mb-0 small">
                    <?php foreach($_SESSION['errors'] as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
            <form method="POST" action="">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label h6">Title *</label>
                        <input type="text" name="titre" class="form-control form-control-lg bg-light border-0" placeholder="Enter the title of the publication" value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Category *</label>
                        <select name="categorie" class="form-select form-select-lg bg-light border-0">
                            <option value="">Select Category</option>
                            <option value="Announcement" <?= ($_SESSION['old']['categorie'] ?? '') == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                            <option value="Law" <?= ($_SESSION['old']['categorie'] ?? '') == 'Law' ? 'selected' : '' ?>>Law</option>
                            <option value="Report" <?= ($_SESSION['old']['categorie'] ?? '') == 'Report' ? 'selected' : '' ?>>Report</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Author *</label>
                        <input type="text" name="auteur" class="form-control form-control-lg bg-light border-0" placeholder="Enter the name of the author" value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Date *</label>
                        <input type="date" name="date" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($_SESSION['old']['date'] ?? date('Y-m-d')) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label h6">Content *</label>
                        <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="10" placeholder="Write the content of the publication here..."><?= htmlspecialchars($_SESSION['old']['contenu'] ?? '') ?></textarea>
                    </div>
                    <?php unset($_SESSION['old']); ?>
                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                        <a href="/projetweb/view/back-office/index.php" class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Add Publication</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
