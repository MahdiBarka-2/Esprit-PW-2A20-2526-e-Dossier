<?php
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../CONTROLLER/EvenementCONTROLLER.php';

$CONTROLLER = new EvenementC();
$events     = $CONTROLLER->listeEvenement()->fetchAll(PDO::FETCH_ASSOC);

$database = new Database();
$db       = $database->getConnection();
$rows     = $db->query('
    SELECT e.id, COUNT(p.id) AS cnt
    FROM evenements e
    LEFT JOIN participations p ON p.event_id = e.id
    GROUP BY e.id
')->fetchAll(PDO::FETCH_ASSOC);

$counts_map = [];
foreach ($rows as $r) {
    $counts_map[$r['id']] = (int) $r['cnt'];
}

foreach ($events as &$e) {
    $e['_cnt'] = $counts_map[$e['id']] ?? 0;
}
unset($e);

$totalEvents       = count($events);
$activeEvents      = count(array_filter($events, fn($e) => $e['statut'] === 'active'));
$totalParticipants = array_sum(array_column($rows, 'cnt'));

$show_modal = isset($_GET['modal']);
$edit_id    = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$edit_event = $edit_id > 0 ? $CONTROLLER->findById($edit_id) : null;

$f = [
    'titre'        => $_GET['titre']        ?? '',
    'description'  => $_GET['description']  ?? '',
    'date_debut'   => $_GET['date_debut']   ?? '',
    'date_fin'     => $_GET['date_fin']     ?? '',
    'lieu'         => $_GET['lieu']         ?? '',
    'capacite_max' => $_GET['capacite_max'] ?? '',
    'statut'       => $_GET['statut']       ?? 'active',
    'is_paid'      => $_GET['is_paid']      ?? '0',
    'prix'         => $_GET['prix']         ?? '',        // ← NEW
];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier – Événements</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script>
        const storedTheme = localStorage.getItem('theme')
        const getPreferredTheme = () => {
            if (storedTheme) return storedTheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }
        const setTheme = function (theme) {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }
        setTheme(getPreferredTheme())
        window.addEventListener('DOMContentLoaded', () => {
            const showActiveTheme = theme => {
                const activeThemeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active')
                })
                if (activeThemeBtn) activeThemeBtn.classList.add('active')
            }
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (storedTheme !== 'light' && storedTheme !== 'dark') setTheme(getPreferredTheme())
            })
            showActiveTheme(getPreferredTheme())
            document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    localStorage.setItem('theme', theme)
                    setTheme(theme)
                    showActiveTheme(theme)
                })
            })
        })
    </script>

    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">

    <style>
        /* ── Sort indicators ── */
        th.sortable { cursor: pointer; user-select: none; white-space: nowrap; }
        th.sortable .sort-icon { font-size: .75rem; opacity: .35; margin-left: .3rem; transition: opacity .15s; }
        th.sortable:hover .sort-icon { opacity: .7; }
        th.sortable.asc  .sort-icon::before { content: "\F235"; font-family: "bootstrap-icons"; opacity: 1; }
        th.sortable.desc .sort-icon::before { content: "\F229"; font-family: "bootstrap-icons"; opacity: 1; }
        th.sortable:not(.asc):not(.desc) .sort-icon::before { content: "\F127"; font-family: "bootstrap-icons"; }

        /* ── Search / filter bar ── */
        #tableSearch { max-width: 260px; }

        /* ── Export buttons ── */
        .btn-export { white-space: nowrap; }

        /* ── Prix field toggle ── */
        .prix-field { display: none; }
        .prix-field.show { display: block; }

        /* ── Print styles ── */
        @media print {
            .page-content > *:not(.page-content-wrapper),
            .no-print { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
            .btn, a.btn { display: none !important; }
            #tableControls { display: none !important; }
        }
    </style>
</head>

<body>
<main>
    <?php include 'sidebar.php'; ?>

    <div class="page-content">
        <?php include 'topbar.php'; ?>

        <div class="page-content-wrapper p-xxl-4">

            <!-- Title -->
            <div class="row">
                <div class="col-12 mb-4 mb-sm-5">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-2 mb-sm-0"><?php echo __('Evenements'); ?></h1>
                        <a href="?modal" class="btn btn-primary mb-0">
                            <i class="bi bi-plus-lg me-1"></i> Nouvel Événement
                        </a>
                    </div>
                </div>
            </div>

            <!-- Counter boxes -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-primary bg-opacity-10 border border-primary border-opacity-25 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $totalEvents; ?></h4>
                                <span class="h6 fw-light mb-0">Total Événements</span>
                            </div>
                            <div class="icon-lg rounded-circle bg-primary text-white mb-0">
                                <i class="bi bi-calendar-event fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-success bg-opacity-10 border border-success border-opacity-25 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $activeEvents; ?></h4>
                                <span class="h6 fw-light mb-0">Événements Actifs</span>
                            </div>
                            <div class="icon-lg rounded-circle bg-success text-white mb-0">
                                <i class="bi bi-calendar-check fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-warning bg-opacity-10 border border-warning border-opacity-25 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $totalEvents - $activeEvents; ?></h4>
                                <span class="h6 fw-light mb-0">Événements Inactifs</span>
                            </div>
                            <div class="icon-lg rounded-circle bg-warning text-white mb-0">
                                <i class="bi bi-calendar-x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-info bg-opacity-10 border border-info border-opacity-25 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $totalParticipants; ?></h4>
                                <span class="h6 fw-light mb-0">Total Participants</span>
                            </div>
                            <div class="icon-lg rounded-circle bg-info text-white mb-0">
                                <i class="fa-solid fa-users fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Table -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-header border-bottom">
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center" id="tableControls">
                                <h5 class="card-header-title mb-0">Liste des Événements</h5>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <div class="input-group input-group-sm" id="tableSearch">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher…">
                                    </div>
                                    <select id="statusFilter" class="form-select form-select-sm" style="width:auto;">
                                        <option value="">Tous les statuts</option>
                                        <option value="active">Actif</option>
                                        <option value="inactive">Inactif</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-success btn-export" onclick="exportCSV()">
                                        <i class="bi bi-filetype-csv me-1"></i>CSV
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary btn-export" onclick="exportPrint()">
                                        <i class="bi bi-printer me-1"></i>Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2" id="rowCount"></p>
                            <div class="table-responsive border-0">
                                <table class="table align-middle p-4 mb-0 table-hover" id="eventsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 rounded-start sortable" data-col="0">Titre <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="1">Date début <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="2">Date fin <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="3">Lieu <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="4">Capacité <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="5">Inscrits <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="6">Statut <span class="sort-icon"></span></th>
                                            <th class="border-0 sortable" data-col="7">Tarif <span class="sort-icon"></span></th>
                                            <th class="border-0 rounded-end text-center no-print">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($events)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                                <p class="mt-2 text-muted">Aucun événement pour le moment.</p>
                                                <a href="?modal" class="btn btn-sm btn-primary">Créer un événement</a>
                                            </td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach ($events as $e): ?>
                                            <?php
                                                $cnt_e  = $counts_map[$e['id']] ?? 0;
                                                $cap_e  = $e['capacite_max'];
                                                $full_e = $cap_e !== null && $cnt_e >= (int)$cap_e;
                                                $prix_e = $e['prix'] ?? null;
                                            ?>
                                            <tr data-statut="<?= htmlspecialchars($e['statut']) ?>">
                                                <td><h6 class="mb-0"><?= htmlspecialchars($e['titre']) ?></h6></td>
                                                <td data-sort="<?= htmlspecialchars($e['date_debut']) ?>"><?= htmlspecialchars($e['date_debut']) ?></td>
                                                <td data-sort="<?= htmlspecialchars($e['date_fin'] ?? '') ?>"><?= htmlspecialchars($e['date_fin'] ?? '—') ?></td>
                                                <td><?= htmlspecialchars($e['lieu'] ?? '—') ?></td>
                                                <td data-sort="<?= (int)($e['capacite_max'] ?? 0) ?>"><?= $e['capacite_max'] ?? '—' ?></td>
                                                <td data-sort="<?= $cnt_e ?>">
                                                    <span class="fw-semibold <?= $full_e ? 'text-danger' : '' ?>">
                                                        <?= $cnt_e ?><?= $cap_e ? ' / ' . $cap_e : '' ?>
                                                    </span>
                                                    <?php if ($full_e): ?>
                                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-1">Complet</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td data-sort="<?= htmlspecialchars($e['statut']) ?>">
                                                    <?php if ($e['statut'] === 'active'): ?>
                                                        <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- ── Tarif column: shows price if paid ── -->
                                                <td data-sort="<?= (int)($e['is_paid'] ?? 0) ?>">
                                                    <?php if (!empty($e['is_paid'])): ?>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                                            <i class="bi bi-currency-dollar me-1"></i>
                                                            <?= $prix_e ? number_format((float)$prix_e, 2) . ' TND' : 'Payant' ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Gratuit</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center no-print">
                                                    <button class="btn btn-sm btn-light mb-0" title="Participants"
                                                            onclick="openParticipants(<?= $e['id'] ?>, <?= htmlspecialchars(json_encode($e['titre'])) ?>)">
                                                        <i class="bi bi-people"></i>
                                                    </button>
                                                    <a href="?edit=<?= $e['id'] ?>" class="btn btn-sm btn-light mb-0" title="Modifier">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="delete_event.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-danger-soft mb-0" title="Supprimer"
                                                       onclick="return confirm('Supprimer cet événement ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- ── CREATE MODAL ───────────────────────────────────────────── -->
<?php if ($show_modal): ?>
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-plus me-2"></i>Créer un événement</h5>
                <a href="?" class="btn-close"></a>
            </div>
            <form action="add_event.php" method="POST" onsubmit="return validateForm()">
                <div class="modal-body">
                    <?php if (!empty($_GET['errors'])): ?>
                    <div class="alert alert-danger py-2">
                        <?php foreach (explode('|', $_GET['errors']) as $err): ?>
                            <div>• <?= htmlspecialchars($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($f['titre']) ?>" placeholder="Ex: Conférence annuelle 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Décrivez l'événement..."><?= htmlspecialchars($f['description']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="date_debut" value="<?= htmlspecialchars($f['date_debut']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="date_fin" value="<?= htmlspecialchars($f['date_fin']) ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu</label>
                            <input type="text" class="form-control" name="lieu" value="<?= htmlspecialchars($f['lieu']) ?>" placeholder="Ex: Tunis, Salle A">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacité max</label>
                            <input type="number" class="form-control" name="capacite_max" value="<?= htmlspecialchars($f['capacite_max']) ?>" placeholder="100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="active"   <?= $f['statut'] === 'active'   ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $f['statut'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>

                    <!-- ── is_paid toggle + prix ── -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="is_paid" id="f-ispaid-create" value="1"
                                   <?= !empty($f['is_paid']) ? 'checked' : '' ?>
                                   onchange="togglePrix('create', this.checked)">
                            <label class="form-check-label" for="f-ispaid-create">
                                <i class="bi bi-currency-dollar me-1"></i>Événement payant
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 prix-field <?= !empty($f['is_paid']) ? 'show' : '' ?>" id="prix-field-create">
                        <label class="form-label">Prix <span class="text-muted fw-normal">(TND)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" class="form-control" name="prix" id="f-prix-create"
                                   value="<?= htmlspecialchars($f['prix']) ?>"
                                   placeholder="0.00" min="0" step="0.01">
                            <span class="input-group-text">TND</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="?" class="btn btn-secondary mb-0">Annuler</a>
                    <button type="submit" class="btn btn-primary mb-0">Créer l'événement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── EDIT MODAL ─────────────────────────────────────────────── -->
<?php if ($edit_id > 0 && $edit_event): ?>
<?php
    $ef = !empty($_GET['errors']) ? $f : [
        'titre'        => $edit_event['titre'],
        'description'  => $edit_event['description'] ?? '',
        'date_debut'   => $edit_event['date_debut'],
        'date_fin'     => $edit_event['date_fin'] ?? '',
        'lieu'         => $edit_event['lieu'] ?? '',
        'capacite_max' => $edit_event['capacite_max'] ?? '',
        'statut'       => $edit_event['statut'],
        'is_paid'      => $edit_event['is_paid'] ?? 0,
        'prix'         => $edit_event['prix'] ?? '',     // ← NEW
    ];
?>
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Modifier l'événement</h5>
                <a href="?" class="btn-close"></a>
            </div>
            <form action="update_event.php" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="id" value="<?= $edit_id ?>">
                <div class="modal-body">
                    <?php if (!empty($_GET['errors'])): ?>
                    <div class="alert alert-danger py-2">
                        <?php foreach (explode('|', $_GET['errors']) as $err): ?>
                            <div>• <?= htmlspecialchars($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($ef['titre']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($ef['description']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="date_debut" value="<?= htmlspecialchars($ef['date_debut']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="date_fin" value="<?= htmlspecialchars($ef['date_fin']) ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu</label>
                            <input type="text" class="form-control" name="lieu" value="<?= htmlspecialchars($ef['lieu']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacité max</label>
                            <input type="number" class="form-control" name="capacite_max" value="<?= htmlspecialchars($ef['capacite_max']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="active"   <?= $ef['statut'] === 'active'   ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $ef['statut'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>

                    <!-- ── is_paid toggle + prix ── -->
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="is_paid" id="f-ispaid-edit" value="1"
                                   <?= !empty($ef['is_paid']) ? 'checked' : '' ?>
                                   onchange="togglePrix('edit', this.checked)">
                            <label class="form-check-label" for="f-ispaid-edit">
                                <i class="bi bi-currency-dollar me-1"></i>Événement payant
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 prix-field <?= !empty($ef['is_paid']) ? 'show' : '' ?>" id="prix-field-edit">
                        <label class="form-label">Prix <span class="text-muted fw-normal">(TND)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" class="form-control" name="prix" id="f-prix-edit"
                                   value="<?= htmlspecialchars($ef['prix']) ?>"
                                   placeholder="0.00" min="0" step="0.01">
                            <span class="input-group-text">TND</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="?" class="btn btn-secondary mb-0">Annuler</a>
                    <button type="submit" class="btn btn-primary mb-0">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── PARTICIPANTS MODAL ─────────────────────────────────────── -->
<div id="participantsModal" tabindex="-1"
     style="display:none; position:fixed; inset:0; z-index:1055; background:rgba(0,0,0,.35); overflow-y:auto; backdrop-filter:blur(2px);">
    <div style="max-width:780px; margin:2rem auto; padding:0 1rem;">
        <div style="background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 8px 40px rgba(0,0,0,.12);">

            <!-- Header -->
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f0ede8; display:flex; align-items:center; justify-content:space-between; background:#fff;">
                <div style="display:flex; align-items:center; gap:.75rem;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#e8f5f0; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-people-fill" style="color:#1a9e7a; font-size:1rem;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:1rem; color:#1a1a2e;">Participants</div>
                        <div style="font-size:.8rem; color:#6b7280;" id="participantsEventTitle"></div>
                    </div>
                </div>
                <button onclick="closeParticipants()" style="width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;color:#6b7280;line-height:1;">
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div id="participantsBody" style="padding:1.25rem 1.5rem; min-height:200px;">
                <div style="text-align:center; padding:3rem 0;">
                    <div class="spinner-border spinner-border-sm" style="color:#1a9e7a;" role="status"></div>
                    <p style="margin-top:.75rem; color:#9ca3af; font-size:.85rem;">Chargement…</p>
                </div>
            </div>

            <!-- Footer -->
            <div style="padding:1rem 1.5rem; border-top:1px solid #f0ede8; background:#faf9f7; display:flex; justify-content:space-between; align-items:center;">
                <a id="participantsFullLink" href="#" style="font-size:.82rem; color:#1a9e7a; text-decoration:none; display:flex; align-items:center; gap:.35rem;">
                    <i class="bi bi-box-arrow-up-right" style="font-size:.78rem;"></i> Ouvrir la page complète
                </a>
                <button onclick="closeParticipants()" style="padding:.45rem 1.1rem; border-radius:8px; border:1px solid #e5e7eb; background:#fff; font-size:.85rem; color:#374151; cursor:pointer; font-weight:500;">
                    Fermer
                </button>
            </div>

        </div>
    </div>
</div>
<!-- ── PARTICIPANTS MODAL END ─────────────────────────────────── -->

<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../assets/js/functions.js"></script>
<script src="validation.js"></script>

<script>
/* ── Toggle prix field when is_paid switch changes ── */
function togglePrix(modalId, show) {
    const field = document.getElementById('prix-field-' + modalId);
    if (!field) return;
    field.classList.toggle('show', show);
    const input = field.querySelector('input[name="prix"]');
    if (input && !show) input.value = '';   // clear price if unchecked
}

(function () {
    /* ════════════════════════════════════
       TABLE: sort + filter + export
    ════════════════════════════════════ */
    const table      = document.getElementById('eventsTable');
    if (!table) return;

    const tbody        = table.querySelector('tbody');
    const rowCount     = document.getElementById('rowCount');
    const searchInput  = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    let sortCol = -1, sortAsc = true;

    function cellValue(row, col) {
        const cell = row.cells[col];
        return (cell.dataset.sort !== undefined ? cell.dataset.sort : cell.innerText).trim().toLowerCase();
    }

    function updateRowCount() {
        const visible = [...tbody.querySelectorAll('tr')].filter(r => r.style.display !== 'none').length;
        const total   = tbody.querySelectorAll('tr').length;
        rowCount.textContent = visible === total
            ? `${total} événement${total !== 1 ? 's' : ''}`
            : `${visible} sur ${total} événement${total !== 1 ? 's' : ''}`;
    }

    table.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', () => {
            const col = parseInt(th.dataset.col, 10);
            sortAsc = sortCol === col ? !sortAsc : true;
            sortCol = col;
            table.querySelectorAll('th.sortable').forEach(h => h.classList.remove('asc', 'desc'));
            th.classList.add(sortAsc ? 'asc' : 'desc');
            [...tbody.querySelectorAll('tr')]
                .sort((a, b) => {
                    const av = cellValue(a, col), bv = cellValue(b, col);
                    const an = parseFloat(av), bn = parseFloat(bv);
                    const cmp = (!isNaN(an) && !isNaN(bn)) ? an - bn : av.localeCompare(bv, 'fr');
                    return sortAsc ? cmp : -cmp;
                })
                .forEach(r => tbody.appendChild(r));
        });
    });

    function applyFilters() {
        const q = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        tbody.querySelectorAll('tr').forEach(row => {
            const matchText   = !q      || row.innerText.toLowerCase().includes(q);
            const matchStatus = !status || (row.dataset.statut || '').toLowerCase() === status;
            row.style.display = (matchText && matchStatus) ? '' : 'none';
        });
        updateRowCount();
    }

    searchInput.addEventListener('input',   applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    updateRowCount();

    window.exportCSV = function () {
        const headers = ['Titre','Date début','Date fin','Lieu','Capacité','Inscrits','Statut','Tarif'];
        const csvRows = [...tbody.querySelectorAll('tr')]
            .filter(r => r.style.display !== 'none')
            .map(r => [0,1,2,3,4,5,6,7].map(i => {
                const cell = r.cells[i];
                let val = (cell.dataset.sort !== undefined ? cell.dataset.sort : cell.innerText.trim()).replace(/\s+/g,' ').trim();
                return '"' + val.replace(/"/g,'""') + '"';
            }));
        const csv  = [headers.map(h => '"'+h+'"').join(','), ...csvRows.map(r=>r.join(','))].join('\r\n');
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const a    = Object.assign(document.createElement('a'), {
            href: URL.createObjectURL(blob),
            download: 'evenements_' + new Date().toISOString().slice(0,10) + '.csv'
        });
        a.click(); URL.revokeObjectURL(a.href);
    };

    window.exportPrint = () => window.print();
})();


/* ════════════════════════════════════
   PARTICIPANTS MODAL — fixed fetch
════════════════════════════════════ */
(function () {
    const modal    = document.getElementById('participantsModal');
    const body     = document.getElementById('participantsBody');
    const titleEl  = document.getElementById('participantsEventTitle');
    const fullLink = document.getElementById('participantsFullLink');

    window.openParticipants = function (eventId, eventTitle) {
        titleEl.textContent = eventTitle;
        fullLink.href = 'participants.php?event_id=' + eventId;

        body.innerHTML = `
            <div style="text-align:center;padding:3rem 0;">
                <div style="width:36px;height:36px;border:3px solid #1a9e7a;border-top-color:transparent;
                     border-radius:50%;animation:_pspin .7s linear infinite;margin:0 auto;"></div>
                <p style="margin-top:.75rem;color:#9ca3af;font-size:.85rem;">Chargement…</p>
            </div>`;

        modal.style.display  = 'block';
        document.body.style.overflow = 'hidden';

        // ── Fixed: send BOTH headers so participants.php detects AJAX reliably ──
        fetch('participants.php?event_id=' + encodeURIComponent(eventId), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) {
            const ct = res.headers.get('Content-Type') || '';
            if (!res.ok) throw new Error('HTTP ' + res.status);
            if (!ct.includes('application/json')) {
                throw new Error('Réponse inattendue du serveur (non-JSON). Vérifiez les logs PHP.');
            }
            return res.json();
        })
        .then(function (data) {
            if (data && data.error) throw new Error(data.error);
            renderParticipants(Array.isArray(data) ? data : []);
        })
        .catch(function (err) {
            body.innerHTML = `
                <div style="margin:1rem;padding:.9rem 1.1rem;background:rgba(220,38,38,.07);
                     border:1px solid rgba(220,38,38,.2);border-radius:10px;
                     font-size:.85rem;color:#dc2626;display:flex;align-items:flex-start;gap:.6rem;">
                    <span style="font-size:1.1rem;flex-shrink:0;">⚠️</span>
                    <span>
                        <strong>Impossible de charger les participants.</strong><br>
                        <span style="font-size:.8rem;opacity:.8;">${esc(err.message)}</span>
                    </span>
                </div>`;
        });
    };

    window.closeParticipants = function () {
        modal.style.display  = 'none';
        document.body.style.overflow = '';
    };

    modal.addEventListener('click', e => { if (e.target === modal) closeParticipants(); });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.style.display === 'block') closeParticipants();
    });

    function renderParticipants(data) {
        if (!data.length) {
            body.innerHTML = `
                <div style="text-align:center;padding:3rem 0;">
                    <div style="width:52px;height:52px;border-radius:14px;background:#f3f4f6;
                         display:inline-flex;align-items:center;justify-content:center;margin-bottom:.75rem;">
                        <i class="bi bi-person-x" style="font-size:1.4rem;color:#9ca3af;"></i>
                    </div>
                    <p style="color:#9ca3af;font-size:.9rem;margin:0;">Aucun participant inscrit pour le moment.</p>
                </div>`;
            return;
        }

        const colors     = ['#e8f5f0','#eef0ff','#fff3e8','#fce8f0','#e8f0ff'];
        const textColors = ['#1a9e7a','#6C5CE7','#e67e22','#d63384','#2563eb'];

        const rows = data.map((p, i) => {
            const ci       = i % colors.length;
            const initials = (p.prenom||'?')[0].toUpperCase() + (p.nom||'?')[0].toUpperCase();
            return `<tr style="border-bottom:1px solid #f5f3f0;">
                <td style="padding:.7rem 1rem;color:#9ca3af;font-size:.8rem;width:36px;">${i+1}</td>
                <td style="padding:.7rem 1rem;">
                    <div style="display:flex;align-items:center;gap:.6rem;">
                        <span style="width:34px;height:34px;border-radius:50%;background:${colors[ci]};
                              color:${textColors[ci]};display:inline-flex;align-items:center;
                              justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;">
                            ${esc(initials)}
                        </span>
                        <div>
                            <div style="font-weight:600;font-size:.88rem;color:#1a1a2e;">${esc(p.nom)} ${esc(p.prenom)}</div>
                            <div style="font-size:.75rem;color:#9ca3af;">CIN : ${esc(String(p.user_id||'—'))}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:.7rem 1rem;">
                    <span style="background:#f3f4f6;border-radius:20px;padding:.2rem .65rem;font-size:.78rem;font-weight:600;color:#374151;">
                        ${esc(String(p.age||'—'))} ans
                    </span>
                </td>
                <td style="padding:.7rem 1rem;font-size:.82rem;color:#6b7280;">${esc(p.date_inscription||'—')}</td>
            </tr>`;
        }).join('');

        body.innerHTML = `
            <div style="margin-bottom:1rem;font-size:.82rem;color:#6b7280;">
                <strong style="color:#1a1a2e;">${data.length}</strong>
                participant${data.length!==1?'s':''} inscrit${data.length!==1?'s':''}
            </div>
            <div style="border:1px solid #f0ede8;border-radius:12px;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#faf9f7;border-bottom:1px solid #f0ede8;">
                            <th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;width:36px;">#</th>
                            <th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">Participant</th>
                            <th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">Âge</th>
                            <th style="padding:.6rem 1rem;font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">Inscription</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>`;
    }

    function esc(str) {
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }

    /* spinner keyframe */
    const s = document.createElement('style');
    s.textContent = '@keyframes _pspin{to{transform:rotate(360deg)}}';
    document.head.appendChild(s);
})();
</script>

<?php include 'footer.php'; ?>
</body>
</html>
