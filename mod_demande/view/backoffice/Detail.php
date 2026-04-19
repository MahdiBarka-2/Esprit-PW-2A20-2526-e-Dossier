<?php
session_start();
include_once __DIR__ . '/../../controller/demandeC.php';

$dc             = new demandeC();
$demande        = $dc->getDemande($_GET['id']);
$justifications = $dc->getJustifications($_GET['id']);

$badgeSoft = match($demande['statut']) {
    'approuvee' => 'bg-success bg-opacity-10 text-success',
    'rejetee'   => 'bg-danger bg-opacity-10 text-danger',
    default     => 'bg-warning bg-opacity-10 text-warning'
};
$statusIcon = match($demande['statut']) {
    'approuvee' => 'bi-check-circle-fill text-success',
    'rejetee'   => 'bi-x-circle-fill text-danger',
    default     => 'bi-clock-fill text-warning'
};
$label = match($demande['statut']) {
    'approuvee' => 'Approuvée',
    'rejetee'   => 'Rejetée',
    default     => 'En attente'
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail – Demande #<?= $demande['id'] ?></title>
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
				<a href="Liste.php" class="btn btn-sm btn-outline-secondary">
					<i class="bi bi-arrow-left me-1"></i>Retour
				</a>
			</div>
		</div>
	</nav>
</header>

<!-- Page header -->
<div style="background: linear-gradient(135deg, #0f2044 0%, #1d3461 60%, #2a4a8a 100%); padding: 2rem 0;">
	<div class="container-fluid px-3 px-xl-4">
		<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
			<div class="text-white">
				<h1 class="h3 fw-bold mb-0">
					<i class="bi bi-file-earmark-text me-2"></i>Demande #<?= $demande['id'] ?>
					<span class="badge <?= $badgeSoft ?> ms-2 fs-6 align-middle">
						<i class="bi <?= $statusIcon ?> me-1"></i><?= $label ?>
					</span>
				</h1>
			</div>
			<div class="d-flex gap-2 flex-wrap">
				<?php if ($demande['statut'] !== 'approuvee'): ?>
				<a href="../../controller/UpdateStatut.php?id=<?= $demande['id'] ?>&statut=approuvee"
				   class="btn btn-success"
				   onclick="return confirm('Approuver cette demande ?')">
					<i class="bi bi-check-circle me-1"></i>Approuver
				</a>
				<?php endif; ?>
				<?php if ($demande['statut'] !== 'rejetee'): ?>
				<a href="../../controller/UpdateStatut.php?id=<?= $demande['id'] ?>&statut=rejetee"
				   class="btn btn-danger"
				   onclick="return confirm('Rejeter cette demande ?')">
					<i class="bi bi-x-circle me-1"></i>Rejeter
				</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid px-3 px-xl-4 py-4">

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-4">

        <!-- Infos utilisateur -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 pb-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                            <span class="text-primary fw-bold fs-4"><?= strtoupper(substr($demande['utilisateur'], 0, 1)) ?></span>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($demande['utilisateur']) ?></h5>
                            <small class="text-muted"><?= htmlspecialchars($demande['email']) ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4">
                    <hr class="my-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <p class="text-muted small mb-1">Catégorie</p>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="bi bi-tag me-1"></i><?= htmlspecialchars($demande['categorie_nom']) ?>
                            </span>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Statut</p>
                            <span class="badge <?= $badgeSoft ?> px-3 py-2">
                                <i class="bi <?= $statusIcon ?> me-1"></i><?= $label ?>
                            </span>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Date de soumission</p>
                            <p class="fw-semibold mb-0"><i class="bi bi-calendar3 me-2 text-muted"></i><?= date('d/m/Y à H:i', strtotime($demande['created_at'])) ?></p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="fw-semibold mb-0"><i class="bi bi-envelope me-2 text-muted"></i><?= htmlspecialchars($demande['email']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 px-4 pb-4">
                    <a href="../../controller/SupprimerDemande.php?id=<?= $demande['id'] ?>"
                       class="btn btn-outline-danger w-100"
                       onclick="return confirm('Supprimer définitivement cette demande ?')">
                        <i class="bi bi-trash me-2"></i>Supprimer la demande
                    </a>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-paperclip me-2 text-primary"></i>Documents justificatifs</h5>
                </div>
                <div class="card-body px-4">
                    <?php if (empty($justifications)): ?>
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:64px;height:64px;">
                                <i class="bi bi-file-earmark-x fs-3 text-muted"></i>
                            </div>
                            <p class="text-muted mb-0">Aucun document joint à cette demande.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                        <?php foreach ($justifications as $j): ?>
                        <?php
                        $ext  = strtolower(pathinfo($j['document'], PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                        $isPdf  = $ext === 'pdf';
                        $iconClass = $isPdf ? 'bi-file-earmark-pdf text-danger' : ($isImage ? 'bi-file-earmark-image text-success' : 'bi-file-earmark text-secondary');
                        $bgClass   = $isPdf ? 'bg-danger' : ($isImage ? 'bg-success' : 'bg-secondary');
                        ?>
                        <div class="col-12">
                            <div class="card border shadow-none">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="<?= $bgClass ?> bg-opacity-10 rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                                            <i class="bi <?= $iconClass ?> fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <p class="fw-semibold small mb-0 text-truncate"><?= htmlspecialchars($j['document']) ?></p>
                                            <?php if (!empty($j['description'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($j['description']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <a href="../../../mod_demande/uploads/<?= $j['document'] ?>" target="_blank" class="btn btn-primary btn-sm flex-shrink-0">
                                            <i class="bi bi-eye me-1"></i>Voir
                                        </a>
                                    </div>
                                    <?php if ($isImage): ?>
                                    <div class="mt-3">
                                        <img src="../../../mod_demande/uploads/<?= $j['document'] ?>" class="img-fluid rounded" style="max-height:200px;object-fit:cover;width:100%;" alt="document">
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="../../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../../assets/js/functions.js"></script>
</body>
</html>
