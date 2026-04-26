
<?php
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/EvenementController.php';

$controller = new EvenementC();
$events     = $controller->listeEvenement()->fetchAll(PDO::FETCH_ASSOC);

// COUNT participants per event
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

$totalEvents      = count($events);
$activeEvents     = count(array_filter($events, fn($e) => $e['statut'] === 'active'));
$totalParticipants = array_sum(array_column($rows, 'cnt'));

$show_modal = isset($_GET['modal']);
$edit_id    = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$edit_event = $edit_id > 0 ? $controller->findById($edit_id) : null;

$f = [
    'titre'        => $_GET['titre']        ?? '',
    'description'  => $_GET['description']  ?? '',
    'date_debut'   => $_GET['date_debut']   ?? '',
    'date_fin'     => $_GET['date_fin']     ?? '',
    'lieu'         => $_GET['lieu']         ?? '',
    'capacite_max' => $_GET['capacite_max'] ?? '',
    'statut'       => $_GET['statut']       ?? 'active',
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
</head>

<body>
<main>
    <!-- Sidebar START -->
    <?php include 'sidebar.php'; ?>
    <!-- Sidebar END -->

    <!-- Page content START -->
    <div class="page-content">
        <!-- Top bar START -->
        <?php include 'topbar.php'; ?>
        <!-- Top bar END -->

        <!-- Page main content START -->
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

            <!-- Counter boxes START -->
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
            <!-- Counter boxes END -->

            <!-- Events Table START -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="card-header-title">Liste des Événements</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive border-0">
                                <table class="table align-middle p-4 mb-0 table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Titre</th>
                                            <th class="border-0">Date début</th>
                                            <th class="border-0">Date fin</th>
                                            <th class="border-0">Lieu</th>
                                            <th class="border-0">Capacité</th>
                                            <th class="border-0">Inscrits</th>
                                            <th class="border-0">Statut</th>
                                            <th class="border-0 rounded-end text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($events)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
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
                                            ?>
                                            <tr>
                                                <td><h6 class="mb-0"><?= htmlspecialchars($e['titre']) ?></h6></td>
                                                <td><?= htmlspecialchars($e['date_debut']) ?></td>
                                                <td><?= htmlspecialchars($e['date_fin'] ?? '—') ?></td>
                                                <td><?= htmlspecialchars($e['lieu'] ?? '—') ?></td>
                                                <td><?= $e['capacite_max'] ?? '—' ?></td>
                                                <td>
                                                    <span class="fw-semibold <?= $full_e ? 'text-danger' : '' ?>">
                                                        <?= $cnt_e ?><?= $cap_e ? ' / ' . $cap_e : '' ?>
                                                    </span>
                                                    <?php if ($full_e): ?>
                                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-1">Complet</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($e['statut'] === 'active'): ?>
                                                        <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger bg-opacity-10 text-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="participants.php?event_id=<?= $e['id'] ?>" class="btn btn-sm btn-light mb-0" title="Participants">
                                                        <i class="bi bi-people"></i>
                                                    </a>
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
            <!-- Events Table END -->

        </div>
        <!-- Page main content END -->
    </div>
    <!-- Page content END -->
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
                        <input type="text" class="form-control" name="titre" id="f-titre" value="<?= htmlspecialchars($f['titre']) ?>" placeholder="Ex: Conférence annuelle 2026">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description"  rows="3" placeholder="Décrivez l'événement..."><?= htmlspecialchars($f['description']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="date_debut"  id="f-datedeb" value="<?= htmlspecialchars($f['date_debut']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="date_fin" id="f-datefin" value="<?= htmlspecialchars($f['date_fin']) ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu</label>
                            <input type="text" class="form-control" name="lieu"  id="f-lieu" value="<?= htmlspecialchars($f['lieu']) ?>" placeholder="Ex: Tunis, Salle A">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacité max</label>
                            <input type="number" class="form-control" name="capacite_max" id="f-cap" value="<?= htmlspecialchars($f['capacite_max']) ?>" placeholder="100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="active"   <?= $f['statut'] === 'active'   ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $f['statut'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
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
                        <input type="text" class="form-control" name="titre" id="f-titre" value="<?= htmlspecialchars($ef['titre']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($ef['description']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Date de début</label>
                            <input type="date" class="form-control" name="date_debut" id="f-datedeb" value="<?= htmlspecialchars($ef['date_debut']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de fin</label>
                            <input type="date" class="form-control" name="date_fin" id="f-datefin" value="<?= htmlspecialchars($ef['date_fin']) ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu</label>
                            <input type="text" class="form-control" name="lieu" id="f-lieu" value="<?= htmlspecialchars($ef['lieu']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacité max</label>
                            <input type="number" class="form-control" name="capacite_max" id="f-cap" value="<?= htmlspecialchars($ef['capacite_max']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="statut">
                            <option value="active"   <?= $ef['statut'] === 'active'   ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $ef['statut'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
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

<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../assets/js/functions.js"></script>
<script src="validation.js"></script>

<?php include 'footer.php'; ?>
<script src="validation.js"></script>
</body>
</html>