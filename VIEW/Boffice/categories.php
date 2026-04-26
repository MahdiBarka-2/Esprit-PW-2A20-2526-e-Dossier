<?php
session_start();
$_SESSION["role"] = "administrator";
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/categorieC.php';

$cc         = new categorieC();
$categories = $cc->listeCategories()->fetchAll();

require_once "header.php";
?>

<div class="page-content-wrapper p-xxl-4">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-tags me-2 text-primary"></i><?php echo __('categories_management'); ?></h1>
        <p class="text-muted small mb-0"><?php echo __('categories_subtitle'); ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="demands.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i><?php echo __('demands'); ?></a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-lg me-1"></i><?php echo __('new_category'); ?>
        </button>
    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
        <ul class="mb-0"><?php foreach ($_SESSION['errors'] as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; unset($_SESSION['errors']); ?></ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($categories)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-tags fs-1 text-muted"></i>
            <h5 class="fw-bold mt-3 mb-2"><?php echo __('categories'); ?></h5>
            <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                <i class="bi bi-plus-circle me-2"></i><?php echo __('new_category'); ?>
            </button>
        </div>
    </div>
<?php else: ?>
<div class="row g-4">
    <?php
    $colors = ['#376cbe','#198754','#ffc107','#dc3545','#0dcaf0','#6f42c1','#fd7e14','#20c997'];
    foreach ($categories as $i => $cat):
        $color = $colors[$i % count($colors)];
    ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-top:3px solid <?= $color ?>!important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="rounded d-flex align-items-center justify-content-center" style="width:44px;height:44px;background:<?= $color ?>20;">
                        <i class="bi bi-tag-fill" style="color:<?= $color ?>;font-size:1.2rem;"></i>
                    </div>
                    <span class="badge rounded-pill text-white" style="background:<?= $color ?>;">#<?= $cat['id'] ?></span>
                </div>
                <h6 class="fw-bold mb-1"><?= htmlspecialchars($cat['nom']) ?></h6>
                <p class="text-muted small mb-0"><?= htmlspecialchars($cat['description'] ?? __('description')) ?></p>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-3 d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm flex-grow-1"
                    onclick="openEditCat(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['nom'])) ?>', '<?= htmlspecialchars(addslashes($cat['description'] ?? '')) ?>')">
                    <i class="bi bi-pencil me-1"></i><?php echo __('edit'); ?>
                </button>
                <a href="../../CONTROLLER/SupprimerCategorie.php?id=<?= $cat['id'] ?>&redirect=backoffice_new"
                   class="btn btn-outline-danger btn-sm flex-grow-1"
                   onclick="return confirm('<?php echo __('delete'); ?>?')">
                    <i class="bi bi-trash me-1"></i><?php echo __('delete'); ?>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card border-2 border h-100 text-center d-flex align-items-center justify-content-center"
             style="min-height:160px;cursor:pointer;border-style:dashed!important;"
             data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <div class="card-body py-4">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:52px;height:52px;">
                    <i class="bi bi-plus-lg fs-4 text-primary"></i>
                </div>
                <p class="fw-semibold text-primary mb-0"><?php echo __('new_category'); ?></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#0f2044,#1d3461);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-tag me-2"></i><?php echo __('new_category'); ?></h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../CONTROLLER/AjouterCategorie.php" method="POST">
                <input type="hidden" name="redirect" value="backoffice_new">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo __('name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex : Logement, Bourse…" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo __('description'); ?></label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i><?php echo __('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#1d5461,#37a0be);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i><?php echo __('edit'); ?></h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../CONTROLLER/ModifierCategorie.php" method="POST">
                <input type="hidden" name="id" id="edit_cat_id">
                <input type="hidden" name="redirect" value="backoffice_new">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo __('name'); ?> <span class="text-danger">*</span></label>
                        <input type="text" name="nom" id="edit_cat_nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><?php echo __('description'); ?></label>
                        <textarea name="description" id="edit_cat_desc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i><?php echo __('submit'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditCat(id, nom, description) {
    document.getElementById('edit_cat_id').value = id;
    document.getElementById('edit_cat_nom').value = nom;
    document.getElementById('edit_cat_desc').value = description;
    new bootstrap.Modal(document.getElementById('modalModifier')).show();
}
</script>


<?php require_once "footer.php"; ?>
