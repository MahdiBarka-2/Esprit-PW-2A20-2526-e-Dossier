<?php
require_once '../../CONTROLLER/EvenementController.php';
require_once '../../CONTROLLER/ParticipantController.php';

$controller = new EvenementC();
$partCtrl   = new ParticipantC();
$counts_map = $partCtrl->getAllCounts();

$all_events  = $controller->findActive();
$search_lieu = $_GET['lieu']       ?? '';
$date_debut  = $_GET['date_debut'] ?? '';
$date_fin    = $_GET['date_fin']   ?? '';

// Filter
$results = [];
foreach ($all_events as $e) {
    if (!empty($search_lieu) && $e['lieu'] !== $search_lieu) continue;
    if (!empty($date_debut) && !empty($date_fin)) {
        $ev_end = !empty($e['date_fin']) ? $e['date_fin'] : $e['date_debut'];
        if ($ev_end < $date_debut || $e['date_debut'] > $date_fin) continue;
    } elseif (!empty($date_debut)) {
        if ($e['date_debut'] < $date_debut) continue;
    }
    $results[] = $e;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Booking – Résultats</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">
   <link rel="stylesheet" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
<link rel="stylesheet" href="../../assets/vendor/font-awesome/css/all.min.css">
<link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="container py-5">

    <!-- Back link -->
    <a href="index.php" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <?= !empty($search_lieu)
                ? 'Événements à <em>' . htmlspecialchars($search_lieu) . '</em>'
                : 'Tous les événements actifs' ?>
            <?php if (!empty($date_debut)): ?>
                <small class="text-muted fs-6 fw-normal ms-2">
                    · <?= htmlspecialchars($date_debut) ?>
                    <?= !empty($date_fin) ? ' → ' . htmlspecialchars($date_fin) : '' ?>
                </small>
            <?php endif; ?>
        </h4>
        <span class="badge bg-primary rounded-pill">
            <?= count($results) ?> résultat<?= count($results) !== 1 ? 's' : '' ?>
        </span>
    </div>

    <!-- No results -->
    <?php if (empty($results)): ?>
    <div class="text-center py-5">
        <i class="bi bi-calendar-x display-4 text-muted"></i>
        <h5 class="mt-3 text-muted">Aucun événement trouvé</h5>
        <p class="text-muted small">Essayez de modifier vos critères de recherche.</p>
        <a href="index.php" class="btn btn-outline-primary mt-2">Nouvelle recherche</a>
    </div>

    <!-- Results grid -->
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($results as $e):
            $cnt     = $counts_map[$e['id']] ?? 0;
            $cap     = $e['capacite_max'];
            $is_full = $cap !== null && $cnt >= (int)$cap;
        ?>
        <div class="col-md-6 col-xl-4">
            <div class="card shadow h-100 border-0 rounded-3 overflow-hidden">

                <!-- accent bar -->
                <div style="height:4px;background:linear-gradient(90deg,#6C5CE7,#a29bfe);"></div>

                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><?= htmlspecialchars($e['titre']) ?></h5>

                    <ul class="list-unstyled small text-muted mb-3">
                        <?php if ($e['lieu']): ?>
                        <li class="mb-1">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                            <?= htmlspecialchars($e['lieu']) ?>
                        </li>
                        <?php endif; ?>
                        <li class="mb-1">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            <?= htmlspecialchars($e['date_debut']) ?>
                            <?= !empty($e['date_fin']) ? ' → ' . htmlspecialchars($e['date_fin']) : '' ?>
                        </li>
                        <li>
                            <i class="bi bi-people-fill text-primary me-2"></i>
                            <?php if ($cap): ?>
                                <strong <?= $is_full ? 'class="text-danger"' : '' ?>>
                                    <?= $cnt ?> / <?= $cap ?>
                                </strong> participants
                                <?php if ($is_full): ?>
                                    <span class="badge bg-danger ms-1">Complet</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= $cnt ?> participant(s)
                            <?php endif; ?>
                        </li>
                    </ul>

                    <?php if (!empty($e['description'])): ?>
                    <p class="small text-muted mb-0"
                       style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        <?= htmlspecialchars($e['description']) ?>
                    </p>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0">
                    <?php if ($is_full): ?>
                        <button class="btn btn-danger btn-sm w-100 disabled">
                            <i class="bi bi-lock-fill me-1"></i>Complet
                        </button>
                    <?php else: ?>
                        <a href="join_event.php?event_id=<?= $e['id'] ?>&action=join"
                           class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-plus-circle me-1"></i>Rejoindre
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
