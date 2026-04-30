<?php
session_start();
$_SESSION["role"] = "administrator";
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/demandeC.php';

$dc             = new demandeC();
$demande        = $dc->getDemande($_GET['id']);
$justifications = $dc->getJustifications($_GET['id']);



$badgeSoft = 'bg-warning bg-opacity-10 text-warning';
$statusIcon = 'bi-clock-fill text-warning';
$label = 'En attente';

if ($demande['statut'] === 'approuvee') {
    $badgeSoft = 'bg-success bg-opacity-10 text-success';
    $statusIcon = 'bi-check-circle-fill text-success';
    $label = 'Approuvée';
} elseif ($demande['statut'] === 'rejetee') {
    $badgeSoft = 'bg-danger bg-opacity-10 text-danger';
    $statusIcon = 'bi-x-circle-fill text-danger';
    $label = 'Rejetée';
}

require_once "header.php";
?>

<div class="page-content-wrapper p-xxl-4">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>Demande #<?= $demande['id'] ?>
            <span class="badge <?= $badgeSoft ?> ms-2 fs-6 align-middle">
                <i class="bi <?= $statusIcon ?> me-1"></i><?= $label ?>
            </span>
        </h1>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php if ($demande['statut'] !== 'approuvee'): ?>
        <a href="../../CONTROLLER/UpdateStatut.php?id=<?= $demande['id'] ?>&statut=approuvee&redirect=backoffice_new"
           class="btn btn-success" onclick="return confirm('Approuver cette demande ?')">
            <i class="bi bi-check-circle me-1"></i>Approuver
        </a>
        <?php endif; ?>
        <?php if ($demande['statut'] !== 'rejetee'): ?>
        <a href="../../CONTROLLER/UpdateStatut.php?id=<?= $demande['id'] ?>&statut=rejetee&redirect=backoffice_new"
           class="btn btn-danger" onclick="return confirm('Rejeter cette demande ?')">
            <i class="bi bi-x-circle me-1"></i>Rejeter
        </a>
        <?php endif; ?>
        

        <!-- Bouton IA (Temps réel) -->
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAI">
            <i class="bi bi-robot me-1"></i> Conseil IA
        </button>

        <a href="demands.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>

    </div>
</div>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:52px;height:52px;">
                        <span class="text-primary fw-bold fs-4"><?= strtoupper(substr($demande['utilisateur'], 0, 1)) ?></span>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($demande['utilisateur']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($demande['email']) ?></small>
                    </div>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <p class="text-muted small mb-1">Catégorie</p>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2"><?= htmlspecialchars($demande['categorie_nom']) ?></span>
                    </div>
                    <div class="col-6">
                        <p class="text-muted small mb-1">Statut</p>
                        <span class="badge <?= $badgeSoft ?> px-3 py-2"><i class="bi <?= $statusIcon ?> me-1"></i><?= $label ?></span>
                    </div>
                    <div class="col-12">
                        <p class="text-muted small mb-1">Date</p>
                        <p class="fw-semibold mb-0"><i class="bi bi-calendar3 me-2 text-muted"></i><?= date('d/m/Y à H:i', strtotime($demande['created_at'])) ?></p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 px-4 pb-4">
                <a href="../../CONTROLLER/SupprimerDemande.php?id=<?= $demande['id'] ?>&redirect=backoffice_new"
                   class="btn btn-outline-danger w-100"
                   onclick="return confirm('Supprimer définitivement cette demande ?')">
                    <i class="bi bi-trash me-2"></i>Supprimer
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-chat-left-text me-2 text-primary"></i>Description de la demande</h5>
            </div>
            <div class="card-body px-4 pb-2">
                <div class="p-3 bg-light rounded-3">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($demande['description'] ?? 'Aucune description fournie.')) ?></p>
                </div>
            </div>

            <div class="card-header border-0 bg-transparent pt-3 px-4 pb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-paperclip me-2 text-primary"></i>Documents justificatifs</h5>
            </div>
            <div class="card-body px-4">
                <?php if (empty($justifications)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-x fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Aucun document joint.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                    <?php foreach ($justifications as $j): ?>
                    <?php
                    $ext     = strtolower(pathinfo($j['document'], PATHINFO_EXTENSION));
                    $isImage = in_array($ext, ['jpg','jpeg','png']);
                    $isPdf   = $ext === 'pdf';
                    $iconClass = $isPdf ? 'bi-file-earmark-pdf text-danger' : ($isImage ? 'bi-file-earmark-image text-success' : 'bi-file-earmark text-secondary');
                    $bgClass   = $isPdf ? 'bg-danger' : ($isImage ? 'bg-success' : 'bg-secondary');
                    ?>
                    <div class="col-12">
                        <div class="card border shadow-none">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="<?= $bgClass ?> bg-opacity-10 rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                                        <i class="bi <?= $iconClass ?> fs-3"></i>
                                    </div>
                                    <div>
                                        <p class="fw-semibold small mb-0"><?= htmlspecialchars($j['document']) ?></p>
                                    </div>
                                </div>
                                <?php if ($isImage): ?>
                                    <img src="../../assets/uploads/demandes/<?= $j['document'] ?>" class="img-fluid rounded w-100" style="max-height:400px;object-fit:contain;background:#f8f9fa;" alt="document">
                                <?php elseif ($isPdf): ?>
                                    <embed src="../../assets/uploads/demandes/<?= $j['document'] ?>" type="application/pdf" width="100%" height="500px" class="rounded border">
                                <?php else: ?>
                                    <a href="../../assets/uploads/demandes/<?= $j['document'] ?>" download class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-download me-1"></i>Télécharger
                                    </a>
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


<!-- Modal Assistant IA (Généré à la volée) -->
<?php $aiSupport = $dc->getAiSupportOnTheFly($demande['id']); ?>
<div class="modal fade" id="modalAI" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
                <h5 class="modal-title fw-bold"><i class="bi bi-robot me-2"></i>Assistant IA (Analyse en direct)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <?php if (!$aiSupport): ?>
                    <p class="text-muted">Analyse impossible pour le moment.</p>
                <?php else: ?>
                    <div class="mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:70px;height:70px;">
                            <i class="bi <?= $aiSupport['suggestion'] == 'approve' ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?> fs-1"></i>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-1">Recommandation :</h4>
                    <div class="badge <?= $aiSupport['suggestion'] == 'approve' ? 'bg-success' : 'bg-danger' ?> fs-5 mb-4 px-4 py-2 rounded-pill">
                        <?= $aiSupport['suggestion'] == 'approve' ? 'APPROUVER' : 'REJETER' ?>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-4 text-start">
                        <p class="small fw-bold text-muted text-uppercase mb-2">Analyse en temps réel :</p>
                        <p class="mb-0 italic text-dark">"<?= htmlspecialchars($aiSupport['reason'] ?? 'Aucun motif trouvé.') ?>"</p>
                    </div>

                    <div class="alert alert-info border-0 text-start small">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Conseil de réponse :</strong> <br>
                        <span class="text-muted">"<?= htmlspecialchars($aiSupport['official_message'] ?? '') ?>"</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Bootstrap JS -->
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Vendor Scripts -->
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<!-- Theme Functions -->
<script src="../../assets/js/functions.js"></script>

<?php require_once "footer.php"; ?>

