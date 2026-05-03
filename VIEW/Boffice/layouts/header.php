<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edossier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Dark mode (runs before page renders to avoid flash) -->
    <script>
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = storedTheme || (prefersDark ? 'dark' : 'light');
        document.documentElement.setAttribute('data-bs-theme', theme);
    </script>

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%23066ac9' d='M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z'/%3E%3Cpath fill='%23066ac9' d='M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="/integration/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="/integration/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/integration/assets/css/style.css">
    
    <!-- Professional Validation Engine -->
    <script src="/integration/assets/js/validation.js" defer></script>
</head>

<body class="has-navbar-mobile">

<header class="navbar-light header-sticky">
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-xl">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand logo-hover" href="/integration/VIEW/index.php">
				<h3 class="mb-0 fw-bold transition-all"><i class="bi bi-file-earmark-text-fill me-2 transition-all"></i>Edossier</h3>
			</a>
            <style>
                .logo-hover h3 { color: var(--bs-body-color); }
                .logo-hover:hover h3, .logo-hover:hover i { color: var(--bs-primary) !important; }
                .transition-all { transition: all 0.3s ease-in-out; }
            </style>
			<!-- Logo END -->

			<!-- Responsive navbar toggler -->
			<button class="navbar-toggler ms-auto ms-sm-0 p-0 p-sm-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
        <span class="d-none d-sm-inline-block small">Menu</span>
			</button>

			<!-- Main navbar START -->
			<div class="navbar-collapse collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-nav-scroll me-auto">
					<!-- Nav item Home -->
					<li class="nav-item">
						<a class="nav-link" href="/integration/VIEW/index1.php"><i class="bi bi-compass me-2"></i>Explore</a>
					</li>

					<!-- Nav item Resources -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="listingMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-folder2-open me-2"></i>Collections</a>
						<ul class="dropdown-menu shadow-sm border-0" aria-labelledby="listingMenu">
							<li> <a class="dropdown-item" href="/integration/VIEW/index1.php"><i class="bi bi-archive text-primary me-2"></i>All Documents</a></li>
							<li> <a class="dropdown-item" href="/integration/template/about.html"><i class="bi bi-diagram-3 text-warning me-2"></i>Architecture Overview</a></li>
                            <li><hr class="dropdown-divider"></li>
							<li> <a class="dropdown-item" href="/integration/VIEW/index1.php?category=Law"><i class="bi bi-journal-bookmark-fill text-danger me-2"></i>Legal & Laws</a></li>
							<li> <a class="dropdown-item" href="/integration/VIEW/index1.php?category=Announcement"><i class="bi bi-megaphone-fill text-warning me-2"></i>Announcements</a></li>
							<li> <a class="dropdown-item" href="/integration/VIEW/index1.php?category=Report"><i class="bi bi-file-earmark-bar-graph-fill text-info me-2"></i>Reports</a></li>
							<li> <a class="dropdown-item" href="/integration/VIEW/index1.php?category=General"><i class="bi bi-collection-fill text-success me-2"></i>General</a></li>
						</ul>
					</li>
                    
                    <!-- Nav item Statistics -->
					<li class="nav-item">
						<a class="nav-link" href="/integration/VIEW/Boffice/posts.php?action=dashboard"><i class="bi bi-graph-up-arrow me-2"></i>Stats</a>
					</li>
				</ul>
			</div>
			<!-- Main navbar END -->

            <!-- Right side content START -->
            <div class="ms-xl-auto d-flex align-items-center">
                <!-- Search bar -->
                <div class="nav-item d-none d-xl-block me-3">
                    <form class="input-group" action="/integration/VIEW/index1.php" method="GET">
                        <input class="form-control form-control-sm border-primary border-opacity-10 bg-light rounded-start-pill ps-3" type="search" placeholder="Search dossier..." name="search" style="width: 200px;">
                        <button class="btn btn-sm btn-primary rounded-end-pill px-3" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <!-- Icons group -->
                <div class="d-flex align-items-center gap-2 me-3">
                    <!-- Dark mode toggle -->
                    <button class="btn btn-link text-secondary p-0 mb-0" id="darkModeToggle" type="button" title="Toggle theme">
                        <i id="darkModeIcon" class="bi bi-moon-stars fs-5"></i>
                    </button>

                    <!-- Saved Favorites -->
                    <a href="/integration/VIEW/index1.php?action=saved" class="btn btn-link text-secondary p-0 mb-0" title="Saved Documents">
                        <i class="bi bi-bookmark-heart fs-5"></i>
                    </a>
                </div>

                <!-- Admin Button -->
                <a href="/integration/VIEW/Boffice/posts.php" class="btn btn-sm btn-primary-soft mb-0 d-none d-lg-block me-3"><i class="bi bi-shield-lock"></i></a>
                
                <!-- Profile dropdown -->
                <div class="dropdown">
                    <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="avatar-img rounded-circle border border-white shadow-sm" src="/integration/assets/images/avatar/01.jpg" alt="avatar">
                    </a>

                    <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow-lg border-0 pt-3 mt-2" aria-labelledby="profileDropdown" style="min-width: 240px;">
                        <!-- Profile info -->
                        <li class="px-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <img class="avatar-img rounded-circle" src="/integration/assets/images/avatar/01.jpg" alt="avatar">
                                </div>
                                <div>
                                    <h6 class="mb-0">Citizen User</h6>
                                    <p class="small m-0 text-muted">citizen@edossier.gov</p>
                                </div>
                            </div>
                        </li>
                        <li> <hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/integration/VIEW/Boffice/posts.php?action=dashboard"><i class="bi bi-speedometer2 fa-fw me-2"></i>Admin Dashboard</a></li>
                        <li><a class="dropdown-item" href="/integration/VIEW/index1.php?action=saved"><i class="bi bi-bookmark-heart fa-fw me-2"></i>Saved Documents</a></li>
                        <li> <hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger bg-danger-soft-hover" href="#"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <!-- Right side content END -->
		</div>
	</nav>
	<!-- Logo Nav END -->
</header>

<!-- Bootstrap JS — required for dropdowns to work -->
<script src="/integration/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/integration/assets/js/functions.js"></script>

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
