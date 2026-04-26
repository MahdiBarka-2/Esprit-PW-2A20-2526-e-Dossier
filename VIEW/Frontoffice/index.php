<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageController.php'; 
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier - Home</title>
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

<body>
    <header class="navbar-light py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="index.php"><?php echo __('home'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Boffice/index.php"><?php echo __('dashboard'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/Events.php"><?php echo __('Events'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/demandes.php"><?php echo __('demand'); ?></a></li>
                    </ul>
                </nav>

                <style>
                    /* Custom styles for navbar links to support beige in dark mode */
                    .nav-link-custom {
                        color: var(--bs-body-color);
                        transition: color 0.3s ease;
                    }
                    [data-bs-theme='light'] .nav-link-custom {
                        color: #0b0a12 !important; /* Dark Navy in light mode */
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
                        color: #f5f5dc !important; /* Beige in dark mode to match Backoffice */
                    }
                    .nav-link-custom:hover {
                        color: var(--bs-primary) !important;
                    }
                    /* Brand color sync with Backoffice */
                    [data-bs-theme='light'] .brand-text {
                        color: #0b0a12 !important; /* Dark Navy to match Boffice */
                    }
                    [data-bs-theme='dark'] .brand-text {
                        color: #f5f5dc !important; /* Beige to match Boffice links */
                    }
                    /* Highlight fix for Light Mode */
                    [data-bs-theme='light'] .highlight-brand {
                        background-color: var(--bs-primary) !important;
                        color: white !important;
                        padding: 0 4px;
                        border-radius: 4px;
                    }
                    /* Theme-aware Hero background */
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

    <main>

        <!-- Hero Section START -->
        <section class="pt-3 pt-lg-5 position-relative overflow-hidden hero-section">
            <div class="container pb-5">
                <div class="row g-4 g-lg-5 align-items-center">
                    <!-- Content -->
                    <div class="col-lg-6 position-relative mb-4 mb-md-0">
                        <!-- Title -->
                        <h1 class="mb-4 display-5 fw-bold text-primary"><?php echo __('hero_title_part1'); ?> <span
                                class="highlight-brand"><?php echo __('hero_title_part2'); ?></span> <?php echo __('hero_title_part3'); ?></h1>
                        <!-- Info -->
                        <p class="mb-4 lead"><?php echo __('hero_info'); ?></p>

                        <!-- Buttons -->
                        <div class="hstack gap-4 flex-wrap align-items-center">
                            <a href="sign-up.php" class="btn btn-primary btn-lg mb-0 text-white"><?php echo __('get_started'); ?></a>
                            <a href="#" class="btn btn-link p-0 mb-0 text-primary fw-bold"><i
                                     class="bi bi-play-circle me-2"></i><?php echo __('watch_tutorial'); ?></a>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="col-lg-6 position-relative text-center">
                        <img src="../../assets/images/element/04.svg" class="img-fluid rounded shadow-sm"
                            alt="Dossier Management" style="max-height: 400px;">
                    </div>
                </div>

                <!-- Search Bar START -->
                <div class="row mt-5">
                    <div class="col-xl-10 mx-auto">
                        <form class="card shadow p-4 rounded-4 border-0">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-control-border form-control-transparent d-flex align-items-center">
                                        <i class="bi bi-search fs-4 me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <label class="form-label mb-0 small"><?php echo __('dossier_name'); ?></label>
                                            <input type="text" class="form-control" placeholder="<?php echo __('dossier_name'); ?>...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-control-border form-control-transparent d-flex align-items-center">
                                        <i class="bi bi-briefcase fs-4 me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <label class="form-label mb-0 small"><?php echo __('category'); ?></label>
                                            <select class="form-select">
                                                <option>Employment</option>
                                                <option>Property</option>
                                                <option>Medical</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <button class="btn btn-primary btn-lg w-100 mb-0 text-white"><?php echo __('search_now'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Search Bar END -->
<
                <!-- Booking Form START -->
                <div class="row mt-4 justify-content-center">
                    <div class="col-xl-10 position-relative">
                        <h6 class="d-none d-xl-block mb-3">Check Availability</h6>
                        <form class="card shadow rounded-3 position-relative p-4 pe-md-5 pb-5 pb-md-4"
                               action="search_events.php" method="GET">
                            <input type="hidden" name="search" value="1">
                            <div class="row g-4 align-items-center">
                                <!-- Location -->
                                <div class="col-lg-4">
                                    <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                        <i class="bi bi-geo-alt fs-3 me-2 mt-2"></i>
                                        <div class="flex-grow-1">
                                            <label class="form-label">Location</label>
                                            <select class="form-select js-choice" name="lieu" data-search-enabled="true">
                                                <option value="">Tous les lieux</option>
                                                <?php foreach ($locs as $l): ?>
                                                    <option value="<?= htmlspecialchars($l) ?>"><?= htmlspecialchars($l) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Date début -->
                                <div class="col-lg-4">
                                    <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                        <i class="bi bi-calendar fs-3 me-2 mt-2"></i>
                                        <div class="flex-grow-1">
                                            <label class="form-label">Date début</label>
                                            <input type="date" class="form-control" name="date_debut">
                                        </div>
                                    </div>
                                </div>
                                <!-- Date fin -->
                                <div class="col-lg-4">
                                    <div class="form-control-border form-control-transparent form-fs-md d-flex">
                                        <i class="bi bi-calendar-check fs-3 me-2 mt-2"></i>
                                        <div class="flex-grow-1">
                                            <label class="form-label">Date fin</label>
                                            <input type="date" class="form-control" name="date_fin">
                                        </div>
                                    </div>
                                </div>
                            </div><!-- row END -->
                            <!-- Search Button -->
                            <div class="btn-position-md-middle">
                                <button type="submit" class="icon-lg btn btn-round btn-primary mb-0">
                                    <i class="bi bi-search fa-fw"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Booking Form END -->

            </div>
        </section>
        <!-- Hero Section END -->

        <!-- Features START -->
        <section class="py-5">
            <div class="container py-4">
                <div class="row g-4 justify-content-center">
                    <!-- Feature Item -->
                    <div class="col-md-4">
                        <div class="card card-body shadow-hover border-0 text-center p-4">
                            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3">
                                <i class="bi bi-shield-check fs-3"></i>
                            </div>
                            <h5><?php echo __('secure_storage'); ?></h5>
                            <p class="mb-0"><?php echo __('secure_storage_desc'); ?></p>
                        </div>
                    </div>
                    <!-- Feature Item -->
                    <div class="col-md-4">
                        <div class="card card-body shadow-hover border-0 text-center p-4">
                            <div class="icon-lg bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3">
                                <i class="bi bi-graph-up-arrow fs-3"></i>
                            </div>
                            <h5><?php echo __('real_time_tracking'); ?></h5>
                            <p class="mb-0"><?php echo __('real_time_tracking_desc'); ?></p>
                        </div>
                    </div>
                    <!-- Feature Item -->
                    <div class="col-md-4">
                        <div class="card card-body shadow-hover border-0 text-center p-4">
                            <div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                            <h5><?php echo __('team_collaboration'); ?></h5>
                            <p class="mb-0"><?php echo __('team_collaboration_desc'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features END -->

    </main>

    <footer class="py-5" style="background-color: #1d3b53; color: white;">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="../../assets/images/e_dossier.png" alt="logo"
                            style="height: 40px; filter: brightness(0) invert(1);">
                        <span class="ms-2 fw-bold text-white fs-4">E-Dossier</span>
                    </div>
                    <p class="small opacity-75">Providing modern solutions for digital dossier management since 2026.</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <ul class="nav justify-content-lg-end mb-3">
                        <li class="nav-item"><a href="#" class="nav-link text-white small px-2">Privacy Policy</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white small px-2">Terms of Use</a></li>
                    </ul>
                    <p class="mb-0 small opacity-50">&copy; 2026 e_dossier. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Vocal & Chat Assistants -->
    <?php
    require_once '../../CONTROLLER/VoiceController.php';
    require_once '../../CONTROLLER/ChatController.php';
    echo renderVocalAssistant($lang ?? 'en');
    echo renderChatAssistant();
    ?>

    <script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>