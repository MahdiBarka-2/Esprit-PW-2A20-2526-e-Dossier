<?php
// ── Feature 4 : Suivi de demande côté citoyen ──
// Le citoyen entre son numéro de demande + son email
// On lui montre le statut, la priorité et une timeline
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../CONTROLLER/demandeC.php';

$dc       = new demandeC();
$demande  = null;
$searched = false;
$error    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searched = true;
    $id       = (int)($_POST['demande_id'] ?? 0);
    $email    = trim($_POST['email'] ?? '');

    if ($id > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $demande = $dc->getDemandeByIdAndEmail($id, $email);
        if (!$demande) {
            $error = "Aucune demande trouvée avec ce numéro et cet email. Vérifiez vos informations.";
        }
    } else {
        $error = "Veuillez entrer un numéro de demande valide et une adresse email valide.";
    }
}

// Calculs pour l'affichage (si demande trouvée)
if ($demande) {
    $joursEcoules = (int)((time() - strtotime($demande['created_at'])) / 86400);
    $priorite     = $demande['priorite'] ?? 'normale';
    $statut       = $demande['statut'];

    $statusLabel = match($statut) { 'approuvee'=>'Approuvée', 'rejetee'=>'Rejetée', default=>'En attente' };
    $statusBadge = match($statut) { 'approuvee'=>'bg-success bg-opacity-10 text-success', 'rejetee'=>'bg-danger bg-opacity-10 text-danger', default=>'bg-warning bg-opacity-10 text-warning' };
    $statusIcon  = match($statut) { 'approuvee'=>'bi-check-circle-fill text-success', 'rejetee'=>'bi-x-circle-fill text-danger', default=>'bi-clock-fill text-warning' };

    $prioBadge   = match($priorite) { 'critique'=>'bg-danger bg-opacity-10 text-danger', 'urgente'=>'bg-warning bg-opacity-10 text-warning', default=>'bg-success bg-opacity-10 text-success' };
    $prioEmoji   = match($priorite) { 'critique'=>'🔴', 'urgente'=>'🟠', default=>'🟢' };

    // Timeline : step 1 = toujours fait, step 2 = toujours en cours, step 3 = dépend du statut
    $step3Done = ($statut === 'approuvee' || $statut === 'rejetee');
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title>Suivi de Demande – E-Dossier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Suivez l'état de votre demande municipale en temps réel.">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <script>
        const getStoredTheme = () => localStorage.getItem('theme');
        const setTheme = theme => {
            document.documentElement.setAttribute('data-bs-theme',
                theme === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : theme);
        };
        setTheme(getStoredTheme() || 'auto');
    </script>
    <style>
        /* Timeline CSS */
        .timeline-step { display: flex; align-items: flex-start; gap: 16px; position: relative; }
        .timeline-step:not(:last-child)::after {
            content: ''; position: absolute; left: 19px; top: 42px;
            width: 2px; height: calc(100% + 8px);
            background: #dee2e6;
        }
        .timeline-step.done::after { background: #198754; }
        .step-circle {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; border: 2px solid #dee2e6; background: #fff;
        }
        .step-circle.done   { background: #198754; border-color: #198754; color: #fff; }
        .step-circle.active { background: #ffc107; border-color: #ffc107; color: #fff; }
        .step-circle.pending{ background: #f8f9fa; border-color: #dee2e6; color: #adb5bd; }
        .step-circle.rejected{ background: #dc3545; border-color: #dc3545; color: #fff; }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header class="navbar-light py-3 border-bottom shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
            <span class="ms-2 fw-bold text-primary" style="font-size: 1.5rem;">E-Dossier</span>
        </a>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link fw-bold text-primary" href="demandes.php">Demandes</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container py-5" style="max-width: 720px;">

    <!-- Titre -->
    <div class="text-center mb-5">
        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px;height:70px;">
            <i class="bi bi-search fs-2 text-primary"></i>
        </div>
        <h1 class="h2 fw-bold">Suivi de Demande</h1>
        <p class="text-muted">Entrez votre numéro de demande et votre email pour connaître l'état de votre dossier.</p>
    </div>

    <!-- Formulaire de recherche -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="POST" action="suivi.php">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Numéro de demande <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-hash text-muted"></i></span>
                            <input type="number" name="demande_id" class="form-control border-start-0 ps-0"
                                   placeholder="Ex : 42" min="1"
                                   value="<?= htmlspecialchars($_POST['demande_id'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Email associé <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0"
                                   placeholder="votre@email.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Message d'erreur -->
    <?php if ($searched && $error): ?>
    <div class="alert alert-danger border-0 shadow-sm">
        <i class="bi bi-exclamation-circle-fill me-2"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- Résultat -->
    <?php if ($demande): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #1d3461, #376cbe);">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="text-white fw-bold mb-0"><i class="bi bi-file-earmark-text me-2"></i>Demande #<?= $demande['id'] ?></h5>
                <span class="badge <?= $statusBadge ?> px-3 py-2">
                    <i class="bi <?= $statusIcon ?> me-1"></i><?= $statusLabel ?>
                </span>
            </div>
        </div>
        <div class="card-body p-4">

            <!-- Infos de base -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <p class="text-muted small mb-1">Catégorie</p>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1"><?= htmlspecialchars($demande['categorie_nom']) ?></span>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small mb-1">Priorité</p>
                    <span class="badge <?= $prioBadge ?> px-2 py-1"><?= $prioEmoji ?> <?= ucfirst($priorite) ?></span>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small mb-1">Date de soumission</p>
                    <span class="small fw-semibold"><?= date('d/m/Y', strtotime($demande['created_at'])) ?></span>
                </div>
                <div class="col-6 col-md-3">
                    <p class="text-muted small mb-1">Jours écoulés</p>
                    <span class="small fw-semibold <?= ($joursEcoules >= 3 && $statut === 'en_attente') ? 'text-danger' : '' ?>">
                        <?= $joursEcoules ?> jour(s)
                    </span>
                </div>
            </div>

            <hr>

            <!-- Timeline -->
            <h6 class="fw-bold mb-4"><i class="bi bi-diagram-3 me-2 text-primary"></i>Progression de votre dossier</h6>
            <div class="d-flex flex-column gap-4">

                <!-- Étape 1 : Soumis -->
                <div class="timeline-step done">
                    <div class="step-circle done"><i class="bi bi-check-lg"></i></div>
                    <div>
                        <p class="fw-semibold mb-0">Demande soumise</p>
                        <small class="text-muted">Le <?= date('d/m/Y à H:i', strtotime($demande['created_at'])) ?></small>
                    </div>
                </div>

                <!-- Étape 2 : En traitement -->
                <div class="timeline-step <?= $step3Done ? 'done' : '' ?>">
                    <div class="step-circle <?= $step3Done ? 'done' : 'active' ?>">
                        <i class="bi <?= $step3Done ? 'bi-check-lg' : 'bi-hourglass-split' ?>"></i>
                    </div>
                    <div>
                        <p class="fw-semibold mb-0">En cours de traitement</p>
                        <small class="text-muted">Votre dossier est examiné par nos services.</small>
                    </div>
                </div>

                <!-- Étape 3 : Décision -->
                <div class="timeline-step">
                    <div class="step-circle <?= $statut === 'approuvee' ? 'done' : ($statut === 'rejetee' ? 'rejected' : 'pending') ?>">
                        <i class="bi <?= $statut === 'approuvee' ? 'bi-check-lg' : ($statut === 'rejetee' ? 'bi-x-lg' : 'bi-question') ?>"></i>
                    </div>
                    <div>
                        <p class="fw-semibold mb-0">Décision finale</p>
                        <?php if ($statut === 'approuvee'): ?>
                            <small class="text-success fw-semibold">✅ Votre demande a été approuvée.</small>
                        <?php elseif ($statut === 'rejetee'): ?>
                            <small class="text-danger fw-semibold">❌ Votre demande a été rejetée. Consultez votre email pour les détails.</small>
                        <?php else: ?>
                            <small class="text-muted">En attente de la décision de l'administration.</small>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="demandes.php" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-2"></i>Faire une nouvelle demande
        </a>
    </div>
    <?php endif; ?>

</div>

<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
