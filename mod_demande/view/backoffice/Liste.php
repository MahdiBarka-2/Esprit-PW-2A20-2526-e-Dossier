<?php
session_start();
include_once __DIR__ . '/../../controller/demandeC.php';
include_once __DIR__ . '/../../controller/categorieC.php';

$dc         = new demandeC();
$cc         = new categorieC();
$demandes   = $dc->listeDemandes()->fetchAll();
$categories = $cc->listeCategories()->fetchAll();

$total     = count($demandes);
$attente   = count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente'));
$approuvee = count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee'));
$rejetee   = count(array_filter($demandes, fn($d) => $d['statut'] === 'rejetee'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Liste des Demandes</title>
    <link rel="shortcut icon" href="../../../assets/images/favicon.ico">
    <link rel="stylesheet" href="../../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body>

<header class="navbar-light header-sticky">
	<nav class="navbar navbar-expand-xl">
		<div class="container-fluid px-3 px-xl-4">
			<a class="navbar-brand" href="../../../index.html">
				<img class="light-mode-item navbar-brand-item" src="../../../assets/images/logo.svg" alt="logo">
				<img class="dark-mode-item navbar-brand-item" src="../../../assets/images/logo-light.svg" alt="logo">
			</a>
			<div class="ms-auto d-flex align-items-center gap-3">
				<span class="badge bg-danger-soft text-danger d-none d-md-inline"><i class="bi bi-shield-lock me-1"></i>Back-Office</span>
				<a href="../frontoffice/Liste.php" class="btn btn-sm btn-outline-primary d-none d-md-inline-flex align-items-center gap-1">
					<i class="bi bi-globe"></i> Front-Office
				</a>
				<div class="vr d-none d-md-block"></div>
				<div class="dropdown">
					<a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
						<div class="avatar avatar-sm">
							<img class="avatar-img rounded-circle" src="../../../assets/images/avatar/01.jpg" alt="admin">
						</div>
						<span class="d-none d-md-block small fw-semibold">Admin</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-end shadow">
						<li><a class="dropdown-item" href="Categories.php"><i class="bi bi-tags me-2"></i>Catégories</a></li>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="../frontoffice/Liste.php"><i class="bi bi-globe me-2"></i>Front-Office</a></li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
</header>

<!-- Page header -->
<div style="background: linear-gradient(135deg, #0f2044 0%, #1d3461 60%, #2a4a8a 100%); padding: 2rem 0;">
	<div class="container-fluid px-3 px-xl-4">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
			<div class="text-white">
				<h1 class="h3 fw-bold mb-0"><i class="bi bi-card-list me-2"></i>Liste des Demandes</h1>
			</div>
			<div class="d-flex gap-2">
				<a href="Categories.php" class="btn btn-outline-light btn-sm">
					<i class="bi bi-tags me-1"></i>Catégories
				</a>
				<button class="btn btn-white btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
					<i class="bi bi-plus-lg me-1"></i>Nouvelle demande
				</button>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid px-3 px-xl-4 py-4">

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Stats cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #376cbe !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i class="bi bi-card-list fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $total ?></h3>
                        <p class="text-muted small mb-0">Total demandes</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i class="bi bi-clock-history fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $attente ?></h3>
                        <p class="text-muted small mb-0">En attente</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $approuvee ?></h3>
                        <p class="text-muted small mb-0">Approuvées</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <i class="bi bi-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= $rejetee ?></h3>
                        <p class="text-muted small mb-0">Rejetées</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i>Toutes les demandes</h6>
            <span class="badge bg-primary rounded-pill"><?= $total ?></span>
        </div>
        <div class="card-body p-0">
            <?php if (empty($demandes)): ?>
                <div class="text-center py-6 py-5">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px;height:70px;">
                        <i class="bi bi-inbox fs-2 text-muted"></i>
                    </div>
                    <p class="text-muted mb-0">Aucune demande pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-4 fw-semibold text-muted small">#</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Utilisateur</th>
                                <th class="border-0 py-3 fw-semibold text-muted small d-none d-md-table-cell">Email</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Catégorie</th>
                                <th class="border-0 py-3 fw-semibold text-muted small d-none d-lg-table-cell">Date</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Statut</th>
                                <th class="border-0 py-3 fw-semibold text-muted small text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($demandes as $d): ?>
                        <?php
                        $badgeSoft = match($d['statut']) {
                            'approuvee' => 'bg-success bg-opacity-10 text-success',
                            'rejetee'   => 'bg-danger bg-opacity-10 text-danger',
                            default     => 'bg-warning bg-opacity-10 text-warning'
                        };
                        $icon = match($d['statut']) {
                            'approuvee' => 'bi-check-circle-fill',
                            'rejetee'   => 'bi-x-circle-fill',
                            default     => 'bi-clock-fill'
                        };
                        $label = match($d['statut']) {
                            'approuvee' => 'Approuvée',
                            'rejetee'   => 'Rejetée',
                            default     => 'En attente'
                        };
                        ?>
                        <tr>
                            <td class="ps-4"><span class="text-muted small">#<?= $d['id'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-xs bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
                                        <span class="text-primary fw-bold small"><?= strtoupper(substr($d['utilisateur'], 0, 1)) ?></span>
                                    </div>
                                    <span class="fw-semibold small"><?= htmlspecialchars($d['utilisateur']) ?></span>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell"><span class="text-muted small"><?= htmlspecialchars($d['email']) ?></span></td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= htmlspecialchars($d['categorie_nom']) ?></span></td>
                            <td class="d-none d-lg-table-cell"><span class="text-muted small"><?= date('d/m/Y', strtotime($d['created_at'])) ?></span></td>
                            <td><span class="badge <?= $badgeSoft ?> px-2 py-1"><i class="bi <?= $icon ?> me-1"></i><?= $label ?></span></td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="Detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-primary-soft" title="Voir détail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="../../controller/SupprimerDemande.php?id=<?= $d['id'] ?>"
                                       class="btn btn-sm btn-danger-soft"
                                       onclick="return confirm('Supprimer cette demande ?')" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #0f2044, #1d3461);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-file-earmark-plus me-2"></i>Nouvelle Demande</h5>
                    <p class="small opacity-75 mb-0">Ajout depuis l'administration</p>
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
                                <input type="text" name="utilisateur" id="utilisateur" class="form-control border-start-0 ps-0" placeholder="Ex : Ahmed Ben Ali">
                            </div>
                            <div class="invalid-feedback" id="err_utilisateur"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="text" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="exemple@email.com">
                            </div>
                            <div class="invalid-feedback" id="err_email"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" id="categorie_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback" id="err_categorie"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="invalid-feedback" id="err_document"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Ex : CIN, certificat…"></textarea>
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

<script>
document.getElementById('formAjouter').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(inputId, errId, msg) {
        document.getElementById(inputId).classList.add('is-invalid');
        document.getElementById(errId).innerHTML = msg;
        valid = false;
    }
    function clearErr(inputId, errId) {
        document.getElementById(inputId).classList.remove('is-invalid');
        document.getElementById(errId).innerHTML = '';
    }
    clearErr('utilisateur','err_utilisateur');
    clearErr('email','err_email');
    clearErr('categorie_id','err_categorie');
    clearErr('document','err_document');
    if (document.getElementById('utilisateur').value.trim().length < 3)
        showErr('utilisateur','err_utilisateur','Le nom doit contenir au moins 3 caractères.');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(document.getElementById('email').value.trim()))
        showErr('email','err_email','Adresse e-mail invalide.');
    if (document.getElementById('categorie_id').value === '')
        showErr('categorie_id','err_categorie','Veuillez sélectionner une catégorie.');
    const doc = document.getElementById('document');
    if (!doc.files || doc.files.length === 0)
        showErr('document','err_document','Un document est obligatoire.');
    if (!valid) e.preventDefault();
});
</script>
<script src="../../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../../assets/js/functions.js"></script>
</body>
</html>
