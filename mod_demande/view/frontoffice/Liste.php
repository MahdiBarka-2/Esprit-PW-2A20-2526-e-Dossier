<?php
session_start();
include_once __DIR__ . '/../../controller/demandeC.php';
include_once __DIR__ . '/../../controller/categorieC.php';

$dc         = new demandeC();
$cc         = new categorieC();
$demandes   = $dc->listeDemandes()->fetchAll();
$categories = $cc->listeCategories()->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail des Demandes</title>
    <link rel="shortcut icon" href="../../../assets/images/favicon.ico">
    <link rel="stylesheet" href="../../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body>

<header class="navbar-light header-sticky">
    <nav class="navbar navbar-expand-xl">
        <div class="container">
            <a class="navbar-brand" href="../../../index.html">
                <img class="light-mode-item navbar-brand-item" src="../../../assets/images/logo.svg" alt="logo">
                <img class="dark-mode-item navbar-brand-item" src="../../../assets/images/logo-light.svg" alt="logo">
            </a>
            <button class="navbar-toggler ms-auto ms-sm-0 p-0 p-sm-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-animation"><span></span><span></span><span></span></span>
                <span class="d-none d-sm-inline-block small">Menu</span>
            </button>
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav navbar-nav-scroll me-auto">
                    <li class="nav-item"><a class="nav-link" href="../../../index.html"><i class="bi bi-house me-1"></i>Accueil</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="demandesMenu" data-bs-toggle="dropdown"><i class="bi bi-file-earmark-text me-1"></i>Demandes</a>
                        <ul class="dropdown-menu" aria-labelledby="demandesMenu">
                            <li><a class="dropdown-item" href="../frontoffice/Liste.php"><i class="bi bi-person me-2"></i>Portail des Demandes</a></li>
                            <li><a class="dropdown-item" href="../backoffice/Liste.php"><i class="bi bi-shield-lock me-2"></i>Administration</a></li>
                            <li><a class="dropdown-item" href="../backoffice/Categories.php"><i class="bi bi-tags me-2"></i>Catégories</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Hero Banner -->
<section class="py-0 py-xl-0">
    <div class="container-fluid px-0">
        <div style="background: linear-gradient(135deg, #1d3461 0%, #376cbe 60%, #4a90d9 100%); min-height: 260px; position:relative; overflow:hidden;">
            <div style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
            <div style="position:absolute;bottom:-80px;left:-40px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,0.04);"></div>
            <div class="container h-100 position-relative d-flex align-items-center justify-content-center text-center" style="min-height:260px;">
                <div class="text-white">
                    <h1 class="display-5 fw-bold mb-3">Portail des Demandes</h1>
                    <p class="mb-4 opacity-75 fs-6">Soumettez et suivez vos demandes administratives en ligne facilement.</p>
                    <button class="btn btn-white btn-lg px-5" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                        <i class="bi bi-plus-circle me-2"></i>Faire une demande
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
<div class="container py-5">

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; unset($_SESSION['errors']); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Step guide -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h5 class="fw-bold mb-4"><i class="bi bi-signpost-2 me-2 text-primary"></i>Comment soumettre votre demande ?</h5>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-top: 3px solid #376cbe !important;">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-5 mx-auto mb-3" style="width:52px;height:52px;">1</div>
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;">
                    <i class="bi bi-tags fs-3 text-primary"></i>
                </div>
                <h6 class="fw-bold mb-1">Choisir une catégorie</h6>
                <p class="text-muted small mb-0">Logement, Bourse, Carte, Certificat…</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-top: 3px solid #ffc107 !important;">
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center fw-bold fs-5 mx-auto mb-3" style="width:52px;height:52px;">2</div>
                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;">
                    <i class="bi bi-paperclip fs-3 text-warning"></i>
                </div>
                <h6 class="fw-bold mb-1">Joindre vos documents</h6>
                <p class="text-muted small mb-0">CIN, certificat d'inscription, fiche…</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4" style="border-top: 3px solid #198754 !important;">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold fs-5 mx-auto mb-3" style="width:52px;height:52px;">3</div>
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;">
                    <i class="bi bi-send-check fs-3 text-success"></i>
                </div>
                <h6 class="fw-bold mb-1">Soumettre la demande</h6>
                <p class="text-muted small mb-0">Suivez le statut en temps réel ci-dessous.</p>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Mes demandes</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-pill px-3 py-2"><?= count($demandes) ?> demande(s)</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                <i class="bi bi-plus-lg me-1"></i>Nouvelle
            </button>
        </div>
    </div>

    <?php if (empty($demandes)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width:80px;height:80px;">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h5 class="fw-bold mb-2">Aucune demande pour le moment</h5>
                <p class="text-muted mb-4">Commencez par soumettre votre première demande administrative.</p>
                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                    <i class="bi bi-plus-circle me-2"></i>Soumettre ma première demande
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($demandes as $d): ?>
            <?php
            $badgeSoft = match($d['statut']) {
                'approuvee' => 'bg-success bg-opacity-10 text-success',
                'rejetee'   => 'bg-danger bg-opacity-10 text-danger',
                default     => 'bg-warning bg-opacity-10 text-warning'
            };
            $icon = match($d['statut']) {
                'approuvee' => 'bi-check-circle-fill text-success',
                'rejetee'   => 'bi-x-circle-fill text-danger',
                default     => 'bi-clock-fill text-warning'
            };
            $label = match($d['statut']) {
                'approuvee' => 'Approuvée',
                'rejetee'   => 'Rejetée',
                default     => 'En attente'
            };
            $pct    = match($d['statut']) { 'approuvee' => 100, 'rejetee' => 100, default => 50 };
            $pcolor = match($d['statut']) { 'approuvee' => 'bg-success', 'rejetee' => 'bg-danger', default => 'bg-warning' };
            $canEdit = $d['statut'] === 'en_attente';
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="bi bi-tag me-1"></i><?= htmlspecialchars($d['categorie_nom']) ?>
                            </span>
                            <span class="badge <?= $badgeSoft ?> px-3 py-2 rounded-pill">
                                <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
                            </span>
                        </div>
                        <h6 class="fw-bold mb-1"><i class="bi bi-person-circle me-2 text-muted"></i><?= htmlspecialchars($d['utilisateur']) ?></h6>
                        <p class="text-muted small mb-1"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($d['email']) ?></p>
                        <p class="text-muted small mb-0"><i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($d['created_at'])) ?></p>
                    </div>
                    <div class="card-footer bg-transparent px-4 pb-3 pt-2">
                        <div class="progress mb-3" style="height:4px;">
                            <div class="progress-bar <?= $pcolor ?>" style="width:<?= $pct ?>%"></div>
                        </div>
                        <?php if ($canEdit): ?>
                        <button class="btn btn-outline-primary btn-sm w-100"
                            onclick="openEditModal(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['utilisateur'])) ?>', '<?= htmlspecialchars(addslashes($d['email'])) ?>', <?= $d['categorie_id'] ?>)">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </button>
                        <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm w-100" disabled title="Impossible de modifier une demande déjà traitée">
                            <i class="bi bi-lock me-1"></i>Non modifiable
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
</main>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #1d3461, #376cbe);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-file-earmark-plus me-2"></i>Nouvelle Demande</h5>
                    <p class="small opacity-75 mb-0">Remplissez le formulaire ci-dessous</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controller/AjouterDemande.php" method="POST" enctype="multipart/form-data" id="formAjouter" novalidate>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="utilisateur" id="utilisateur" class="form-control border-start-0 ps-0"
                                       value="<?= htmlspecialchars($_SESSION['old']['utilisateur'] ?? '') ?>"
                                       placeholder="Ex : Ahmed Ben Ali">
                            </div>
                            <div class="invalid-feedback" id="err_utilisateur"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="text" name="email" id="email" class="form-control border-start-0 ps-0"
                                       value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
                                       placeholder="exemple@email.com">
                            </div>
                            <div class="invalid-feedback" id="err_email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags text-muted"></i></span>
                                <select name="categorie_id" id="categorie_id" class="form-select border-start-0">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="invalid-feedback" id="err_categorie"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Document justificatif <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>PDF, JPG ou PNG – max 5 Mo</small>
                            <div class="invalid-feedback" id="err_document"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description du document</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Ex : CIN, certificat d'inscription…"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i>Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #1d5461, #37a0be);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Modifier la Demande</h5>
                    <p class="small opacity-75 mb-0">Seules les demandes en attente peuvent être modifiées</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../controller/ModifierDemande.php" method="POST" id="formModifier" novalidate>
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="utilisateur" id="edit_utilisateur" class="form-control border-start-0 ps-0" placeholder="Ex : Ahmed Ben Ali">
                            </div>
                            <div class="invalid-feedback" id="edit_err_utilisateur"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="text" name="email" id="edit_email" class="form-control border-start-0 ps-0" placeholder="exemple@email.com">
                            </div>
                            <div class="invalid-feedback" id="edit_err_email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tags text-muted"></i></span>
                                <select name="categorie_id" id="edit_categorie_id" class="form-select border-start-0">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="invalid-feedback" id="edit_err_categorie"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info border-0 mb-0 py-2 px-3 small">
                                <i class="bi bi-info-circle me-1"></i>Le document ne peut pas être modifié. Supprimez et recréez la demande si nécessaire.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php unset($_SESSION['old']); ?>

<!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <img src="../../../assets/images/logo-light.svg" alt="logo" class="mb-3" style="height:30px;">
                <p class="text-white-50 small mb-0">Système de Gestion des Demandes Administratives.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="../backoffice/Liste.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-shield-lock me-1"></i>Accès Administration
                </a>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-white-50 small text-center mb-0">© <?= date('Y') ?> – Portail des Demandes</p>
    </div>
</footer>

<script>
function openEditModal(id, utilisateur, email, categorieId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_utilisateur').value = utilisateur;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_categorie_id').value = categorieId;
    const modal = new bootstrap.Modal(document.getElementById('modalModifier'));
    modal.show();
}

document.getElementById('formAjouter').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, errId, msg) { document.getElementById(id).classList.add('is-invalid'); document.getElementById(errId).innerHTML = msg; valid = false; }
    function clearErr(id, errId) { document.getElementById(id).classList.remove('is-invalid'); document.getElementById(errId).innerHTML = ''; }
    clearErr('utilisateur','err_utilisateur'); clearErr('email','err_email'); clearErr('categorie_id','err_categorie'); clearErr('document','err_document');
    if (document.getElementById('utilisateur').value.trim().length < 3) showErr('utilisateur','err_utilisateur','Le nom doit contenir au moins 3 caractères.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(document.getElementById('email').value.trim())) showErr('email','err_email','Adresse e-mail invalide.');
    if (!document.getElementById('categorie_id').value) showErr('categorie_id','err_categorie','Veuillez sélectionner une catégorie.');
    const doc = document.getElementById('document');
    if (!doc.files || doc.files.length === 0) showErr('document','err_document','Un document justificatif est obligatoire.');
    if (!valid) e.preventDefault();
});

document.getElementById('formModifier').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, errId, msg) { document.getElementById(id).classList.add('is-invalid'); document.getElementById(errId).innerHTML = msg; valid = false; }
    function clearErr(id, errId) { document.getElementById(id).classList.remove('is-invalid'); document.getElementById(errId).innerHTML = ''; }
    clearErr('edit_utilisateur','edit_err_utilisateur'); clearErr('edit_email','edit_err_email'); clearErr('edit_categorie_id','edit_err_categorie');
    if (document.getElementById('edit_utilisateur').value.trim().length < 3) showErr('edit_utilisateur','edit_err_utilisateur','Le nom doit contenir au moins 3 caractères.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(document.getElementById('edit_email').value.trim())) showErr('edit_email','edit_err_email','Adresse e-mail invalide.');
    if (!document.getElementById('edit_categorie_id').value) showErr('edit_categorie_id','edit_err_categorie','Veuillez sélectionner une catégorie.');
    if (!valid) e.preventDefault();
});
</script>
<script src="../../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../../assets/js/functions.js"></script>
</body>
</html>
