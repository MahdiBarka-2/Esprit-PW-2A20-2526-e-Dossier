<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edossier - Digital Document Platform</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Dark mode (runs before page renders to avoid flash) -->
    <script>
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📁</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="/projetweb/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="/projetweb/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/projetweb/assets/css/style.css">
    
</head>

<body class="has-navbar-mobile">

<header class="navbar-light header-sticky">
    <nav class="navbar navbar-expand-xl">
        <div class="container">

            <!-- Logo -->
            <!-- Logo -->
           <a class="navbar-brand fw-bold text-primary" href="/projetweb/index.php">
            <i class="fas fa-folder-open me-2"></i>Edossier
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler ms-auto mx-3 p-0 p-sm-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-animation">
                    <span></span><span></span><span></span>
                </span>
                <span class="d-none d-sm-inline-block small">Menu</span>
            </button>

            <!-- Navbar links -->
            <div class="navbar-collapse collapse" id="navbarCollapse">
                <ul class="navbar-nav navbar-nav-scroll me-auto">

                    <!-- Publications dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-newspaper me-1"></i> Publications
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/projetweb/index1.php"><i class="bi bi-grid me-2"></i>All Publications</a></li>
                        </ul>
                    </li>

                    <!-- Dashboard dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog me-1"></i> Dashboard
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/projetweb/back-office/index.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="/projetweb/back-office/index.php?action=create"><i class="bi bi-plus-circle me-2"></i>New Publication</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            <!-- Right side -->
            <ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">

                <!-- Dark mode toggle -->
                <li class="nav-item me-2">
                    <button class="btn btn-link p-0 mb-0" id="darkModeToggle" title="Toggle dark mode">
                        <i class="bi bi-moon-stars fs-5" id="darkModeIcon"></i>
                    </button>
                </li>

                <!-- Search -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-search fs-5"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:280px">
                        <form class="input-group">
                            <input class="form-control" type="search" placeholder="Search publications...">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                </li>

                <!-- Notification -->
                <li class="nav-item dropdown ms-0 ms-md-2">
                    <a class="nav-notification btn btn-light p-0 mb-0" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                        <i class="bi bi-bell fa-fw"></i>
                    </a>
                    <span class="notif-badge animation-blink"></span>

                    <div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md shadow-lg p-0">
                        <div class="card bg-transparent">
                            <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                                <h6 class="m-0">Notifications <span class="badge bg-danger bg-opacity-10 text-danger ms-2">1 new</span></h6>
                                <a class="small" href="#">Clear all</a>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush list-unstyled p-2">
                                    <li>
                                        <a href="/projetweb/index1.php" class="list-group-item list-group-item-action rounded notif-unread border-0 mb-1 p-3">
                                            <h6 class="mb-2">New publication added 📄</h6>
                                            <p class="mb-0 small">Check the latest official documents.</p>
                                            <span>Today</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-transparent text-center border-top">
                                <a href="/projetweb/index1.php" class="btn btn-sm btn-link mb-0 p-0">See all publications</a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Profile dropdown -->
                <li class="nav-item ms-3 dropdown">
                    <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button"
                        data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="avatar-img rounded-2" src="/projetweb/assets/images/avatar/01.jpg" alt="avatar">
                    </a>

                    <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                        <li class="px-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img class="avatar-img rounded-circle shadow" src="/projetweb/assets/images/avatar/01.jpg" alt="avatar">
                                </div>
                                <div>
                                    <a class="h6 mt-2 mt-sm-0" href="#">Admin</a>
                                    <p class="small m-0">admin@edossier.com</p>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/projetweb/back-office/index.php"><i class="bi bi-speedometer2 fa-fw me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="/projetweb/back-office/index.php?action=create"><i class="bi bi-plus-circle fa-fw me-2"></i>New Publication</a></li>
                        <li><a class="dropdown-item" href="/projetweb/index1.php"><i class="bi bi-newspaper fa-fw me-2"></i>All Publications</a></li>
                        <li>
    <a class="dropdown-item" href="/projetweb/back-office/account.php">
        <!-- Account, Settings & Logout -->
<li>
    <a class="dropdown-item" href="/projetweb/back-office/account.php">
        <i class="bi bi-person-circle fa-fw me-2"></i>My Account
    </a>
</li>
<li>
    <a class="dropdown-item" href="/projetweb/back-office/settings.php">
        <i class="bi bi-gear fa-fw me-2"></i>Settings
    </a>
</li>
<li><hr class="dropdown-divider"></li>
<li>
    <a class="dropdown-item text-danger" href="/projetweb/logout.php">
        <i class="bi bi-box-arrow-right fa-fw me-2"></i>Sign Out
    </a>
</li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <div class="d-flex justify-content-between align-items-center px-3 pb-2">
                                <span class="small">Theme:</span>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setThemeMode('light')" title="Light">
                                        <i class="bi bi-sun"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setThemeMode('dark')" title="Dark">
                                        <i class="bi bi-moon-stars"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

<!-- Bootstrap JS — required for dropdowns to work -->
<script src="/projetweb/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/projetweb/assets/js/functions.js"></script>

<script>
    function setThemeMode(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        updateDarkIcon();
    }

    function updateDarkIcon() {
        const icon = document.getElementById('darkModeIcon');
        if (!icon) return;
        const theme = document.documentElement.getAttribute('data-bs-theme');
        icon.className = theme === 'dark' ? 'bi bi-sun fs-5 text-warning' : 'bi bi-moon-stars fs-5';
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateDarkIcon();
        const btn = document.getElementById('darkModeToggle');
        if (btn) {
            btn.addEventListener('click', () => {
                const current = document.documentElement.getAttribute('data-bs-theme');
                setThemeMode(current === 'dark' ? 'light' : 'dark');
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
