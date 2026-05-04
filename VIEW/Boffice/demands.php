<?php
session_start();
$_SESSION["role"] = "administrator";
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/demandeC.php';
require_once '../../CONTROLLER/categorieC.php';

$dc         = new demandeC();
$cc         = new categorieC();
$demandes   = $dc->listeDemandes()->fetchAll();
$categories = $cc->listeCategories()->fetchAll();

$total     = count($demandes);
$attente   = count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente'));
$approuvee = count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee'));
$rejetee   = count(array_filter($demandes, fn($d) => $d['statut'] === 'rejetee'));

require_once "header.php";
?>

<div class="page-content-wrapper p-xxl-4">

<!-- Page header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-clipboard-check me-2 text-primary"></i>Gestion des Demandes</h1>
        <p class="text-muted small mb-0">Administration des demandes soumises par les utilisateurs</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalHistorique">
            <i class="bi bi-clock-history me-1"></i>Historique
        </button>
        <a href="categories.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-tags me-1"></i>Catégories
        </a>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle demande
        </button>
    </div>
</div>

<!-- Flash messages -->
<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #376cbe!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-card-list fs-5 text-primary"></i>
                </div>
                <div><h3 class="fw-bold mb-0"><?= $total ?></h3><p class="text-muted small mb-0">Total</p></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #ffc107!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-clock-history fs-5 text-warning"></i>
                </div>
                <div><h3 class="fw-bold mb-0"><?= $attente ?></h3><p class="text-muted small mb-0">En attente</p></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-check-circle fs-5 text-success"></i>
                </div>
                <div><h3 class="fw-bold mb-0"><?= $approuvee ?></h3><p class="text-muted small mb-0">Approuvées</p></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm" style="border-left:4px solid #dc3545!important;">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                    <i class="bi bi-x-circle fs-5 text-danger"></i>
                </div>
                <div><h3 class="fw-bold mb-0"><?= $rejetee ?></h3><p class="text-muted small mb-0">Rejetées</p></div>
            </div>
        </div>
    </div>
</div>

<!-- Search bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted mb-1">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Nom ou email…">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Catégorie</label>
                <select id="filterCategorie" class="form-select">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted mb-1">Statut</label>
                <select id="filterStatut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="En attente">En attente</option>
                    <option value="Approuvée">Approuvée</option>
                    <option value="Rejetée">Rejetée</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" id="btnReset">
                    <i class="bi bi-x-circle me-1"></i>Réinitialiser
                </button>
                <div class="mt-1 text-center">
                    <small class="text-muted"><span id="resultCount"><?= count($demandes) ?></span> résultats</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2 text-primary"></i>Toutes les demandes</h6>
        <span class="badge bg-primary rounded-pill" id="mainTotalCount"><?= count($demandes) ?></span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($demandes)): ?>
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px;height:70px;">
                    <i class="bi bi-inbox fs-2 text-muted"></i>
                </div>
                <p class="text-muted mb-0">Aucune demande pour le moment.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="demandesTable">
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
                $badgeSoft = 'bg-warning bg-opacity-10 text-warning';
                $icon = 'bi-clock-fill';
                $label = 'En attente';

                if ($d['statut'] === 'approuvee') {
                    $badgeSoft = 'bg-success bg-opacity-10 text-success';
                    $icon = 'bi-check-circle-fill';
                    $label = 'Approuvée';
                } elseif ($d['statut'] === 'rejetee') {
                    $badgeSoft = 'bg-danger bg-opacity-10 text-danger';
                    $icon = 'bi-x-circle-fill';
                    $label = 'Rejetée';
                }
                ?>
                <tr data-nom="<?= htmlspecialchars(strtolower($d['utilisateur'])) ?>"
                    data-email="<?= htmlspecialchars(strtolower($d['email'])) ?>"
                    data-cat="<?= htmlspecialchars(strtolower($d['categorie_nom'])) ?>"
                    data-statut="<?= $label ?>">
                    <td class="ps-4"><span class="text-muted small">#<?= $d['id'] ?></span></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
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
                            <a href="demand-detail.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir détail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="../../CONTROLLER/SupprimerDemande.php?id=<?= $d['id'] ?>&redirect=backoffice_new"
                               class="btn btn-sm btn-outline-danger"
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

</div><!-- end page-content-wrapper -->

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
            <form id="formAjouterAdmin" action="../../CONTROLLER/AjouterDemande.php" method="POST" enctype="multipart/form-data" novalidate onsubmit="return validateFormAdmin(event)">

                <input type="hidden" name="source" value="admin">
                <input type="hidden" name="redirect" value="backoffice_new">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="utilisateur" id="admin-nom" class="form-control border-start-0 ps-0" placeholder="Ex : Ahmed Ben Ali" required>
                            </div>
                            <small class="text-danger d-none" id="error-nom">Veuillez entrer votre nom complet (min 3 carac.).</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" id="admin-email" class="form-control border-start-0 ps-0" placeholder="exemple@email.com" required>
                            </div>
                            <small class="text-danger d-none" id="error-email">Veuillez entrer une adresse email valide.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" id="admin-cat" class="form-select" required onchange="updateTemplates()">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger d-none" id="error-cat">Veuillez choisir une catégorie.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="admin-doc" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-danger d-none" id="error-doc">Veuillez joindre un document justificatif.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <div id="templateContainer" class="mb-2 d-none">
                                <small class="text-muted d-block mb-1">Modèle suggéré par l'IA :</small>
                                <div id="templateButtons"></div>
                            </div>
                            <textarea id="admin-desc" name="description" class="form-control" rows="2" placeholder="Ex : CIN, certificat…"></textarea>
                            <small class="text-danger d-none" id="error-desc">Veuillez donner un peu plus de détails (min 10 carac.).</small>
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

<!-- Modal Historique -->
<div class="modal fade" id="modalHistorique" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #0f2044, #1d3461);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-clock-history me-2"></i>Historique des Actions</h5>
                    <p class="small opacity-75 mb-0">Journal complet des activités</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php
                $historique  = $dc->getHistorique();
                $h_total  = count($historique);
                $h_create = count(array_filter($historique, fn($h) => $h['action'] === 'Création'));
                $h_modif  = count(array_filter($historique, fn($h) => $h['action'] === 'Modification'));
                $h_rejet  = count(array_filter($historique, fn($h) => $h['action'] === 'Rejet'));
                ?>
                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 bg-primary bg-opacity-10 text-center py-3">
                            <div class="fw-bold fs-4 text-primary"><?= $h_total ?></div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 bg-success bg-opacity-10 text-center py-3">
                            <div class="fw-bold fs-4 text-success"><?= $h_create ?></div>
                            <small class="text-muted">Créations</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 bg-warning bg-opacity-10 text-center py-3">
                            <div class="fw-bold fs-4 text-warning"><?= $h_modif ?></div>
                            <small class="text-muted">Modifications</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 bg-danger bg-opacity-10 text-center py-3">
                            <div class="fw-bold fs-4 text-danger"><?= $h_rejet ?></div>
                            <small class="text-muted">Rejets</small>
                        </div>
                    </div>
                </div>
                <!-- Filters -->
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="histSearch" class="form-control border-start-0 ps-0" placeholder="Nom ou email…">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="histAction" class="form-select">
                            <option value="">Toutes les actions</option>
                            <option value="Création">Création</option>
                            <option value="Modification">Modification</option>
                            <option value="Approbation">Approbation</option>
                            <option value="Rejet">Rejet</option>
                            <option value="__admin__">Actions Admin uniquement</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end align-self-end pb-1">
                        <button class="btn btn-outline-secondary btn-sm" id="btnResetHist">
                            <i class="bi bi-x-circle me-1"></i>Réinitialiser
                        </button>
                        <div class="mt-1">
                            <small class="text-muted"><span id="histResultCount"><?= count($historique) ?></span> logs</small>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <?php if (empty($historique)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Aucune activité enregistrée.</p>
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="histTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-3 fw-semibold text-muted small">Date</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Qui</th>
                                <th class="border-0 py-3 fw-semibold text-muted small d-none d-md-table-cell">Email</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Action</th>
                                <th class="border-0 py-3 fw-semibold text-muted small d-none d-lg-table-cell">Détails</th>
                                <th class="border-0 py-3 fw-semibold text-muted small">Demande</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($historique as $h): ?>
                        <?php
                        $isAdmin = ($h['source'] ?? 'utilisateur') === 'admin';
                        $hBadge = 'bg-secondary bg-opacity-10 text-secondary';
                        $hIcon  = 'bi-clock-fill';

                        if ($h['action'] === 'Création') {
                            $hBadge = 'bg-success bg-opacity-10 text-success';
                            $hIcon  = 'bi-plus-circle-fill';
                        } elseif ($h['action'] === 'Modification') {
                            $hBadge = 'bg-warning bg-opacity-10 text-warning';
                            $hIcon  = 'bi-pencil-fill';
                        } elseif ($h['action'] === 'Approbation') {
                            $hBadge = 'bg-primary bg-opacity-10 text-primary';
                            $hIcon  = 'bi-check-circle-fill';
                        } elseif ($h['action'] === 'Rejet') {
                            $hBadge = 'bg-danger bg-opacity-10 text-danger';
                            $hIcon  = 'bi-x-circle-fill';
                        }
                        ?>
                        <tr data-hnom="<?= htmlspecialchars(strtolower($h['utilisateur'])) ?>"
                            data-hemail="<?= htmlspecialchars(strtolower($h['email'])) ?>"
                            data-haction="<?= htmlspecialchars($h['action']) ?>"
                            data-hsource="<?= $isAdmin ? 'admin' : 'user' ?>"
                            style="<?= $isAdmin ? 'background:linear-gradient(90deg,rgba(13,110,253,0.07) 0%,transparent 100%);border-left:3px solid #0d6efd;' : '' ?>">
                            <td class="ps-3">
                                <span class="small fw-semibold"><?= date('d/m/Y', strtotime($h['created_at'])) ?></span><br>
                                <span class="text-muted" style="font-size:0.75rem;"><?= date('H:i', strtotime($h['created_at'])) ?></span>
                            </td>
                            <td>
                                <?php if ($isAdmin): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:30px;height:30px;background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                                        <i class="bi bi-shield-fill text-white" style="font-size:0.7rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold small text-primary">Administrateur</span><br>
                                        <span class="badge bg-primary rounded-pill" style="font-size:0.6rem;">ADMIN</span>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:30px;height:30px;">
                                        <span class="text-secondary fw-bold small"><?= strtoupper(substr($h['utilisateur'], 0, 1)) ?></span>
                                    </div>
                                    <span class="fw-semibold small"><?= htmlspecialchars($h['utilisateur']) ?></span>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <span class="text-muted small"><?= $isAdmin ? '—' : htmlspecialchars($h['email']) ?></span>
                            </td>
                            <td>
                                <span class="badge <?= $hBadge ?> px-2 py-1">
                                    <i class="bi <?= $hIcon ?> me-1"></i><?= htmlspecialchars($h['action']) ?>
                                </span>
                                <?php if ($isAdmin): ?>
                                    <span class="badge bg-primary ms-1 px-2 py-1" style="font-size:0.6rem;"><i class="bi bi-shield me-1"></i>Admin</span>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="<?= $isAdmin ? 'fw-semibold text-primary' : 'text-muted' ?> small">
                                    <?= htmlspecialchars($h['details'] ?? '—') ?>
                                </span>
                                <?php if (!$isAdmin): ?>
                                <br><span class="text-muted" style="font-size:0.72rem;"><i class="bi bi-person me-1"></i><?= htmlspecialchars($h['utilisateur']) ?> &lt;<?= htmlspecialchars($h['email']) ?>&gt;</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($h['demande_id']): ?>
                                    <a href="demand-detail.php?id=<?= $h['demande_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>#<?= $h['demande_id'] ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Demandes table search ─────────────────────────────────────────────────────
function filterTable() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const cat    = document.getElementById('filterCategorie').value.toLowerCase();
    const statut = document.getElementById('filterStatut').value.toLowerCase();
    const rows   = document.querySelectorAll('#demandesTable tbody tr');
    let visible  = 0;
    rows.forEach(function(row) {
        const ms = !search || (row.dataset.nom||'').includes(search) || (row.dataset.email||'').includes(search);
        const mc = !cat    || (row.dataset.cat||'').includes(cat);
        const mv = !statut || (row.dataset.statut||'').toLowerCase().includes(statut);
        row.style.display = (ms && mc && mv) ? '' : 'none';
        if (ms && mc && mv) visible++;
    });
    document.getElementById('resultCount').textContent = visible;
    const badge = document.getElementById('mainTotalCount');
    if (badge) badge.textContent = visible;
}
filterTable(); // Initial count

document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('filterCategorie').addEventListener('change', filterTable);
document.getElementById('filterStatut').addEventListener('change', filterTable);
document.getElementById('btnReset').addEventListener('click', function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterCategorie').value = '';
    document.getElementById('filterStatut').value = '';
    filterTable();
});

// ── Historique search ─────────────────────────────────────────────────────────
function filterHist() {
    const searchVal = document.getElementById('histSearch').value.toLowerCase();
    const actionVal = document.getElementById('histAction').value;
    const rows      = document.querySelectorAll('#histTable tbody tr');
    
    rows.forEach(function(row) {
        const nom    = (row.dataset.hnom   || '').toLowerCase();
        const email  = (row.dataset.hemail || '').toLowerCase();
        const act    = (row.dataset.haction|| '');
        const source = (row.dataset.hsource|| '');
        
        const matchesSearch = !searchVal || nom.includes(searchVal) || email.includes(searchVal);
        const matchesAction = !actionVal || (actionVal === '__admin__' ? source === 'admin' : act === actionVal);
        
        row.style.display = (matchesSearch && matchesAction) ? '' : 'none';
        if (matchesSearch && matchesAction) visible++;
    });
    document.getElementById('histResultCount').textContent = visible;
}

filterHist(); // Initial count


document.getElementById('histSearch').addEventListener('input', filterHist);
document.getElementById('histAction').addEventListener('change', filterHist);
document.getElementById('btnResetHist').addEventListener('click', function() {
    document.getElementById('histSearch').value = '';
    document.getElementById('histAction').value = '';
    filterHist();
});

// ── AI Templates Logic ───────────────────────────────────────────────────────
function insertTemplate(text) {
    document.getElementById('admin-desc').value = text;
}

function updateTemplates() {
    const select = document.getElementById('admin-cat');
    const selectedOption = select.options[select.selectedIndex];
    const catName = selectedOption.getAttribute('data-name') || '';
    const container = document.getElementById('templateContainer');
    const buttonsDiv = document.getElementById('templateButtons');
    
    buttonsDiv.innerHTML = '';
    
    const templates = {
        'Logement': 'Je sollicite l\'attribution d\'un logement social. Ma situation actuelle est : [DÉTAILS]. Revenu mensuel : [MONTANT].',
        'Bourse d\'Études': 'Demande de bourse d\'études pour l\'année universitaire. Inscrit en [FILIÈRE] à [ÉTABLISSEMENT].',
        'Carte Municipale': 'Je souhaite obtenir une carte municipale pour accéder aux services de la ville. Photo jointe.',
        'Certificat Administratif': 'Demande de certificat administratif concernant [OBJET]. J\'en ai besoin pour [RAISON].',
        'Extrait de Naissance': 'Je souhaite obtenir un extrait de naissance. Né le [DATE] à [LIEU]. Père: [NOM], Mère: [NOM].',
        'Certificat de Résidence': 'Demande de certificat de résidence. J\'habite au [ADRESSE] à [VILLE] depuis le [DATE].',
        'Extrait d\'acte de Marriage': 'Je demande un extrait d\'acte de mariage. Mariage célébré le [DATE] à [LIEU] entre [NOM1] et [NOM2].',
        'Acte de Décès': 'Demande d\'acte de décès pour [NOM_DEFUNT], décédé le [DATE] à [LIEU].',
        'Passeport / CIN': 'Demande de renouvellement de [DOCUMENT]. Mon ancien numéro est [NUMERO].'
    };

    if (templates[catName]) {
        container.classList.remove('d-none');
        const safeText = templates[catName].replace(/'/g, "\\'");
        buttonsDiv.innerHTML = `<button type="button" class="btn btn-sm btn-primary py-1 px-3" onclick="insertTemplate('${safeText}')"><i class="bi bi-magic me-1"></i> Utiliser le modèle ${catName}</button>`;
    } else {
        container.classList.add('d-none');
    }
}

// ── Backoffice Validation Logic (Méthode Directe) ──────────────────────────
function validateFormAdmin(event) {
    let isValid = true;
    const form = document.getElementById('formAjouterAdmin');
    const errors = form.querySelectorAll('[id^="error-"]');
    const inputs = form.querySelectorAll('.form-control, .form-select');
    
    // Reset
    errors.forEach(el => el.classList.add('d-none'));
    inputs.forEach(el => el.classList.remove('is-invalid'));

    const nom = document.getElementById('admin-nom').value.trim();
    const email = document.getElementById('admin-email').value.trim();
    const cat = document.getElementById('admin-cat').value;
    const doc = document.getElementById('admin-doc').files.length;
    const desc = document.getElementById('admin-desc').value.trim();

    if (nom === "" || nom.length < 3) {
        document.getElementById('error-nom').classList.remove('d-none');
        document.getElementById('admin-nom').classList.add('is-invalid');
        isValid = false;
    }

    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        document.getElementById('error-email').classList.remove('d-none');
        document.getElementById('admin-email').classList.add('is-invalid');
        isValid = false;
    }

    if (cat === "") {
        document.getElementById('error-cat').classList.remove('d-none');
        document.getElementById('admin-cat').classList.add('is-invalid');
        isValid = false;
    }

    if (doc === 0) {
        document.getElementById('error-doc').classList.remove('d-none');
        document.getElementById('admin-doc').classList.add('is-invalid');
        isValid = false;
    }

    if (desc.length < 10) {
        document.getElementById('error-desc').classList.remove('d-none');
        document.getElementById('admin-desc').classList.add('is-invalid');
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>




<!-- Bootstrap JS -->
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Vendor Scripts -->
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<!-- Theme Functions -->
<script src="../../assets/js/functions.js"></script>

<?php require_once "footer.php"; ?>
