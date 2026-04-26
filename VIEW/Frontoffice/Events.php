<?php
require_once '../../CONTROLLER/EvenementController.php';
require_once '../../CONTROLLER/LanguageController.php'; 

$controller    = new EvenementC();
$active_events = $controller->findActive();
$tab           = $_GET['tab'] ?? 'all';

// ── Real participant counts via LEFT JOIN ─────────────────────────────────
$database = new Database();
$db= $database->getConnection();
$rows = $db->query('
    SELECT e.id, COUNT(p.id) AS cnt
    FROM evenements e
    LEFT JOIN participations p ON p.event_id = e.id
    WHERE e.statut = \'active\'
    GROUP BY e.id
')->fetchAll();

$counts_map = [];
foreach ($rows as $r) {
    $counts_map[$r['id']] = (int) $r['cnt'];
}
// ─────────────────────────────────────────────────────────────────────────

// $joined stays empty (no sessions) — user sees counts but not personal state
$joined = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <!-- Dark mode script -->
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
</head>
  <title>Booking – Portail des Événements</title>

  <link rel="shortcut icon" href="assets/images/favicon.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">
  <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/vendor/tiny-slider/tiny-slider.css">
  <link rel="stylesheet" href="assets/vendor/glightbox/css/glightbox.css">
  <link rel="stylesheet" href="assets/vendor/flatpickr/css/flatpickr.min.css">
  <link rel="stylesheet" href="assets/vendor/choices/css/choices.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===================== HEADER ===================== -->
<header class="navbar-light py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="index.php"><?php echo ('home'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Boffice/index.php"><?php echo ('dashboard'); ?></a>
                         <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/Events.php"><?php echo ('Events'); ?></a>
                        </li>
                    </ul>
                </nav>

                <style>
                    .nav-link-custom {
                        color: var(--bs-body-color);
                        transition: color 0.3s ease;
                    }
                    [data-bs-theme='light'] .nav-link-custom {
                        color: #0b0a12 !important;
                    }
                    [data-bs-theme='dark'] .nav-link-custom,
                    [data-bs-theme='dark'] h1, 
                    [data-bs-theme='dark'] h2, 
                    [data-bs-theme='dark'] h3, 
                    [data-bs-theme='dark'] h4, 
                    [data-bs-theme='dark'] h5, 
                    [data-bs-theme='dark'] h6,
                    [data-bs-theme='dark'] p,
                    [data-bs-theme='dark'] .lead,
                    [data-bs-theme='dark'] label {
                        color: #f5f5dc !important;
                    }
                    .nav-link-custom:hover {
                        color: var(--bs-primary) !important;
                    }
                    [data-bs-theme='light'] .brand-text {
                        color: #0b0a12 !important;
                    }
                    [data-bs-theme='dark'] .brand-text {
                        color: #f5f5dc !important;
                    }
                    [data-bs-theme='light'] .highlight-brand {
                        background-color: var(--bs-primary) !important;
                        color: white !important;
                        padding: 0 4px;
                        border-radius: 4px;
                    }
                    .hero-section {
                        background-color: var(--bs-cream);
                    }
                    [data-bs-theme='dark'] .hero-section {
                        background-color: var(--bs-body-bg) !important;
                    }
                    /* Floating search button */
                    .btn-position-md-middle {
                        position: absolute;
                        bottom: -20px;
                        left: 50%;
                        transform: translateX(-50%);
                    }
                    @media (min-width: 768px) {
                        .btn-position-md-middle {
                            top: 50%;
                            bottom: auto;
                            right: -20px;
                            left: auto;
                            transform: translateY(-50%);
                        }
                    }
                    .btn-round {
                        border-radius: 50% !important;
                        width: 44px;
                        height: 44px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding: 0;
                    }
                    .icon-lg {
                        width: 44px;
                        height: 44px;
                    }
                </style>

                <!-- Language Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm mb-0 px-2" id="languageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> <?php echo strtoupper($lang); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item <?php echo $lang === 'en' ? 'active' : ''; ?>" href="?lang=en">EN English</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'fr' ? 'active' : ''; ?>" href="?lang=fr">FR French</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'ar' ? 'active' : ''; ?>" href="?lang=ar">AR Arabic</a></li>
                    </ul>
                </div>

                <!-- Theme Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm lh-0 mb-0" id="bd-theme" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-circle-half theme-icon-active"></i>
                    </button>
                    <ul class="dropdown-menu min-w-auto dropdown-menu-end shadow" aria-labelledby="bd-theme">
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"><i class="bi bi-brightness-high-fill me-2"></i>Light</button></li>
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"><i class="bi bi-moon-stars-fill me-2"></i>Dark</button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"><i class="bi bi-circle-half me-2"></i>Auto</button></li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Profile dropdown START -->
                    <div class="dropdown ms-3">
                        <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            $profile_img = (isset($_SESSION['profile_image_url']) && !empty($_SESSION['profile_image_url'])) 
                                            ? $_SESSION['profile_image_url'] 
                                            : '../../assets/images/avatar/01.jpg';
                            ?>
                            <img class="avatar-img rounded-circle" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid var(--bs-primary);">
                        </a>
                        <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                            <li class="px-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img class="avatar-img rounded-circle shadow" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <a class="h6 mt-2 mt-sm-0" href="#"><?php echo $_SESSION['name'] ?? 'User'; ?></a>
                                        <p class="small m-0 text-truncate" style="max-width: 150px;"><?php echo $_SESSION['email'] ?? ''; ?></p>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Boffice/account-settings.php"><i class="bi bi-person fa-fw me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="../Boffice/settings.php"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
                            <li><a class="dropdown-item bg-danger-soft-hover" href="../Boffice/logout.php"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="ms-3">
                        <a href="../Boffice/sign-in.php" class="btn btn-primary btn-sm mb-0 px-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" title="<?php echo __('sign_in'); ?>">
                            <i class="fa-solid fa-user fs-5 text-white"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
<!-- ===================== HEADER END ===================== -->


<!-- ===================== MAIN CONTENT ===================== -->
<div id="view-front">
  <div class="content">

    <div class="section-head">
      <div class="section-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#6C5CE7" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        Événements actifs
      </div>
      <span class="badge-count"><?= count($active_events) ?> événement(s)</span>
    </div>

    <div class="tabs">
      <a href="?tab=all"  class="tab <?= $tab === 'all'  ? 'active' : '' ?>">Tous</a>
      <a href="?tab=mine" class="tab <?= $tab === 'mine' ? 'active' : '' ?>">Mes participations</a>
    </div>

    <!-- ---- TAB: ALL EVENTS ---- -->
    <?php if ($tab === 'all'): ?>

      <?php if (empty($active_events)): ?>
        <div class="empty">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <p>Aucun événement actif pour le moment.</p>
        </div>

      <?php else: ?>
        <div class="events-grid">
          <?php foreach ($active_events as $e):
            $cnt     = $counts_map[$e['id']] ?? 0;
            $cap     = $e['capacite_max'];
            $is_full = $cap !== null && $cnt >= (int)$cap;
          ?>
          <div class="event-card">
            <h3><?= htmlspecialchars($e['titre']) ?></h3>

            <?php if ($e['lieu']): ?>
            <div class="event-meta">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
              </svg>
              <?= htmlspecialchars($e['lieu']) ?>
            </div>
            <?php endif; ?>

            <div class="event-meta">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <?= htmlspecialchars($e['date_debut']) ?>
              <?= $e['date_fin'] ? ' → ' . htmlspecialchars($e['date_fin']) : '' ?>
            </div>

            <?php if ($e['description']): ?>
            <p class="event-desc"><?= htmlspecialchars($e['description']) ?></p>
            <?php endif; ?>

            <div class="card-footer">
              <span class="cap-badge">
                <?php if ($cap): ?>
                  <?= $cnt ?> / <?= $cap ?> pers.
                  <?php if ($is_full): ?>
                    <span style="color:#dc2626;font-weight:600;"> · Complet</span>
                  <?php endif; ?>
                <?php else: ?>
                  <?= $cnt ?> participant(s)
                <?php endif; ?>
              </span>

              <?php if (isset($joined[$e['id']])): ?>
                <a href="join_event.php?event_id=<?= $e['id'] ?>&action=leave" class="btn-leave">Quitter</a>
              <?php elseif ($is_full): ?>
                <span class="btn-join" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">Complet</span>
              <?php else: ?>
                <a href="join_event.php?event_id=<?= $e['id'] ?>&action=join" class="btn-join">Rejoindre</a>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <!-- ---- TAB: MY EVENTS ---- -->
    <?php elseif ($tab === 'mine'): ?>
      <?php $mine_events = array_filter($active_events, fn($e) => isset($joined[$e['id']])); ?>

      <?php if (empty($mine_events)): ?>
        <div class="empty">
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <p>Vous ne participez à aucun événement.</p>
        </div>
      <?php else: ?>
        <div class="events-grid">
          <?php foreach ($mine_events as $e): ?>
          <div class="event-card">
            <h3><?= htmlspecialchars($e['titre']) ?></h3>
            <?php if ($e['lieu']): ?>
            <div class="event-meta">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
              </svg>
              <?= htmlspecialchars($e['lieu']) ?>
            </div>
            <?php endif; ?>
            <div class="event-meta">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <?= htmlspecialchars($e['date_debut']) ?>
              <?= $e['date_fin'] ? ' → ' . htmlspecialchars($e['date_fin']) : '' ?>
            </div>
            <?php if ($e['description']): ?>
            <p class="event-desc"><?= htmlspecialchars($e['description']) ?></p>
            <?php endif; ?>
            <div class="card-footer">
              <span class="cap-badge" style="font-size:11px;color:var(--gray-400);">
                Inscrit en tant que : <strong><?= htmlspecialchars($joined[$e['id']]) ?></strong>
              </span>
              <a href="join_event.php?event_id=<?= $e['id'] ?>&action=leave" class="btn-leave">Quitter</a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</div>
<!-- ===================== MAIN CONTENT END ===================== -->

</body>
</html>
