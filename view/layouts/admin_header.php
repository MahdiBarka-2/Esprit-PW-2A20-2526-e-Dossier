<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
	<title>Edossier Platform | Management</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Dark mode logic -->
	<script>
		const storedTheme = localStorage.getItem('theme')
		const getPreferredTheme = () => {
			if (storedTheme) return storedTheme
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}
		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}
		setTheme(getPreferredTheme())
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="/projetweb/assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/projetweb/assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="/projetweb/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/projetweb/assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
	<link rel="stylesheet" type="text/css" href="/projetweb/assets/vendor/apexcharts/css/apexcharts.css">

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/projetweb/assets/css/style.css">

	<!-- Custom Sidebar Styles -->
	<style>
		.sidebar-menu-item .nav-link {
			font-family: 'Poppins', sans-serif;
			font-weight: 500;
			padding: 0.85rem 1.25rem;
			border-radius: 0.75rem;
			color: var(--bs-body-color);
			transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
			margin-bottom: 0.25rem;
		}
		.sidebar-menu-item .nav-link i {
			font-size: 1.1rem;
			color: var(--bs-secondary);
			transition: color 0.3s ease;
		}
		.sidebar-menu-item .nav-link:hover {
			background-color: rgba(var(--bs-primary-rgb), 0.08);
			color: var(--bs-primary);
			transform: translateX(4px);
		}
		.sidebar-menu-item .nav-link:hover i {
			color: var(--bs-primary);
		}
		.sidebar-menu-item .nav-link.active {
			background-color: rgba(var(--bs-primary-rgb), 0.12);
			color: var(--bs-primary);
			font-weight: 600;
			border-left: 4px solid var(--bs-primary);
			padding-left: calc(1.25rem - 4px); /* Adjust padding for border */
		}
		.sidebar-menu-item .nav-link.active i {
			color: var(--bs-primary);
		}
		.sidebar-submenu-item .nav-link {
			font-family: 'DM Sans', sans-serif;
			font-weight: 500;
			font-size: 0.95rem;
			padding: 0.5rem 1rem;
			border-radius: 0.5rem;
			color: var(--bs-secondary);
			transition: all 0.2s ease;
		}
		.sidebar-submenu-item .nav-link:hover {
			color: var(--bs-primary);
			background-color: transparent;
			transform: translateX(2px);
		}
		.sidebar-submenu-item .nav-link.active {
			color: var(--bs-primary);
			font-weight: 700;
			background-color: transparent;
		}
		.sidebar-title {
			letter-spacing: 1.5px;
			font-size: 0.75rem;
			color: var(--bs-gray-500);
		}
	</style>
</head>

<body>

<main>
	
	<!-- Sidebar START -->
	<nav class="navbar sidebar navbar-expand-xl navbar-light">
		<!-- Navbar brand for xl START -->
		<div class="d-flex align-items-center">
			<a class="navbar-brand" href="/projetweb/index1.php">
				<h3 class="mb-0 text-primary fw-bold"><i class="bi bi-file-earmark-text-fill me-2"></i>Edossier</h3>
			</a>
		</div>
		<!-- Navbar brand for xl END -->
		
		<div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100" data-bs-backdrop="true" tabindex="-1" id="offcanvasSidebar">
			<div class="offcanvas-body sidebar-content d-flex flex-column pt-4">
	
				<!-- Sidebar menu START -->
				<ul class="navbar-nav flex-column px-2" id="navbar-sidebar">
					<!-- Menu item -->
					<li class="nav-item sidebar-menu-item mb-2">
						<a href="/projetweb/view/back-office/index.php?action=dashboard" class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'dashboard' ? 'active' : '' ?>">
							<i class="bi bi-grid-1x2-fill fa-fw me-3"></i>Dashboard Overview
						</a>
					</li>

					<!-- Title -->
					<li class="nav-item sidebar-title ms-3 my-3 fw-bold text-uppercase">System Management</li>

					<!-- Menu item -->
					<li class="nav-item sidebar-menu-item mb-2">
						<a class="nav-link" data-bs-toggle="collapse" href="#collapsebooking" role="button" aria-expanded="true" aria-controls="collapsebooking">
							<i class="bi bi-folder-fill fa-fw me-3"></i>Publications Center
						</a>
						<!-- Submenu -->
						<ul class="nav collapse flex-column show ms-4 mt-2 border-start border-2 border-light ps-2" id="collapsebooking" data-bs-parent="#navbar-sidebar">
							<li class="nav-item sidebar-submenu-item mb-1"> 
								<a class="nav-link <?= (!isset($_GET['action']) || $_GET['action'] == 'index') ? 'active' : '' ?>" href="/projetweb/view/back-office/index.php?action=index">Publications List</a>
							</li>
							<li class="nav-item sidebar-submenu-item"> 
								<a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'create') ? 'active' : '' ?>" href="/projetweb/view/back-office/index.php?action=create">Add/Publish Document</a>
							</li>
						</ul>
					</li>
	
					<!-- Menu item -->
					<li class="nav-item sidebar-menu-item mb-2"> 
						<a class="nav-link <?= isset($_GET['action']) && $_GET['action'] == 'comments' ? 'active' : '' ?>" href="/projetweb/view/back-office/index.php?action=comments">
							<i class="bi bi-chat-square-quote-fill fa-fw me-3"></i>Citizen Feedback
						</a>
					</li>
				</ul>
				<!-- Sidebar menu end -->

				<!-- Sidebar footer START -->
				<div class="mt-auto p-3">
					<a class="btn btn-danger-soft w-100 d-flex justify-content-center align-items-center fw-bold py-2 rounded-3 shadow-sm" href="/projetweb/index1.php" style="transition: all 0.3s;">
						<i class="fa-solid fa-power-off me-2"></i> Terminate Session
					</a>
				</div>
				<!-- Sidebar footer END -->
			</div>
		</div>
	</nav>
	<!-- Sidebar END -->
	
	<!-- Page content START -->
	<div class="page-content">
	
		<!-- Top bar START -->
		<nav class="navbar top-bar navbar-light py-2 py-xl-3 border-bottom border-light">
			<div class="container-fluid p-0">
				<div class="d-flex align-items-center w-100">
	
					<!-- Logo START -->
					<div class="d-flex align-items-center d-xl-none">
						<a class="navbar-brand" href="/projetweb/index1.php">
							<h3 class="mb-0 text-primary fw-bold"><i class="bi bi-file-earmark-text-fill me-2"></i>Edossier</h3>
						</a>
					</div>
	
					<!-- Toggler for sidebar START -->
					<div class="navbar-expand-xl sidebar-offcanvas-menu">
						<button class="navbar-toggler me-auto p-2 border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar" aria-expanded="false" aria-label="Toggle navigation" data-bs-auto-close="outside">
							<i class="bi bi-list text-primary fa-fw fs-2" data-bs-target="#offcanvasMenu"></i>
						</button>
					</div>
					
					<!-- Top bar right START -->
					<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">
                        <li class="nav-item ms-2 me-3 d-none d-sm-block">
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="bi bi-circle-fill small me-1"></i> System Online</span>
                        </li>

						<!-- Profile dropdown START -->
						<li class="nav-item dropdown">
							<a class="avatar avatar-sm p-0 " href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
								<img class="avatar-img rounded-circle border border-white border-2 shadow-sm" src="/projetweb/assets/images/avatar/01.jpg" alt="avatar">
							</a>
		
							<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow-lg rounded-4 border-0 pt-3" aria-labelledby="profileDropdown">
								<!-- Profile info -->
								<li class="px-4 mb-3">
									<div class="d-flex align-items-center">
										<div class="avatar me-3">
											<img class="avatar-img rounded-circle shadow" src="/projetweb/assets/images/avatar/01.jpg" alt="avatar">
										</div>
										<div>
											<a class="h6 mt-2 mt-sm-0 fw-bold" href="#">System Administrator</a>
											<p class="small text-muted m-0">admin@edossier.gov</p>
										</div>
									</div>
								</li>
								<li> <hr class="dropdown-divider border-light mx-3"></li>
								<li><a class="dropdown-item py-2" href="#"><i class="bi bi-gear fa-fw me-2 text-primary"></i>Platform Settings</a></li>
								<li><a class="dropdown-item py-2" href="#"><i class="bi bi-shield-check fa-fw me-2 text-success"></i>Security Log</a></li>
								<li> <hr class="dropdown-divider border-light mx-3"></li>
								<li><a class="dropdown-item py-2 bg-danger-soft-hover text-danger fw-bold rounded-bottom-4" href="/projetweb/index1.php"><i class="bi bi-power fa-fw me-2"></i>Secure Sign Out</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<!-- Top bar END -->
