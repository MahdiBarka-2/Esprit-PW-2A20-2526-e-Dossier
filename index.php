<?php
require_once 'controller/PublicationC.php';
$pubCtrl = new PublicationC();
$list = $pubCtrl->listePublication();
?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from edossier template -->
<head>
	<title>Edossier - Digital Document Platform</title>
<meta name="description" content="Edossier - Official Government Publications and Documents">

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')
 
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
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

		window.addEventListener('DOMContentLoaded', () => {
			var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})

				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})

			showActiveTheme(getPreferredTheme())

			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})

			}
		})
		
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Poppins:wght@400;500;700&amp;display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/tiny-slider/tiny-slider.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/glightbox/css/glightbox.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/flatpickr/css/flatpickr.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/choices/css/choices.min.css">
	

	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <style>
        .glass-card {
            background: rgba(15, 23, 42, 0.9) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            transition: all 0.3s ease !important;
        }
        .glass-card:hover {
            background: rgba(30, 41, 59, 0.8) !important;
            border-color: var(--bs-primary) !important;
            transform: translateY(-5px);
        }
    </style>

</head>

<body class="has-navbar-mobile dark-mode">

<!-- Header START -->
<header class="navbar-light header-sticky">
	<!-- Logo Nav START -->
	<nav class="navbar navbar-expand-xl">
		<div class="container">
			<!-- Logo START -->
			<a class="navbar-brand logo-hover" href="/projetweb/index.php">
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

			<!-- Responsive category toggler -->
			<button class="navbar-toggler ms-sm-auto mx-3 me-md-0 p-0 p-sm-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategoryCollapse" aria-controls="navbarCategoryCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<i class="bi bi-grid-3x3-gap-fill fa-fw"></i><span class="d-none d-sm-inline-block small">Category</span>
			</button>

			<!-- Main navbar START -->
			<div class="navbar-collapse collapse" id="navbarCollapse">
				<ul class="navbar-nav navbar-nav-scroll me-auto">

					<!-- Nav item Dropdown -->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="exploreMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Explore</a>
						<ul class="dropdown-menu" aria-labelledby="exploreMenu">
							<!-- Publications submenu -->
							<li class="dropdown-submenu dropend">
								<a class="dropdown-item dropdown-toggle" href="#">Publications</a>
								<ul class="dropdown-menu" data-bs-popper="none">
									<li><a class="dropdown-item" href="/projetweb/index1.php"><i class="bi bi-grid me-2"></i>All Publications</a></li>
									<li><a class="dropdown-item" href="/projetweb/view/back-office/index.php?action=create"><i class="bi bi-plus-circle me-2"></i>Add Publication</a></li>
									<li><a class="dropdown-item" href="/projetweb/view/back-office/index.php?action=dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
								</ul>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="about.html">Architecture Overview</a></li>
							<li><a class="dropdown-item" href="contact.html">System Support</a></li>
						</ul>
					</li>

					<!-- Nav item Pages -->
					<li class="nav-item">
						<a class="nav-link" href="/projetweb/view/back-office/index.php?action=dashboard">
							<i class="bi bi-graph-up-arrow me-2"></i>Stats
						</a>
					</li>
				</ul>
			</div>
			<!-- Main navbar END -->

			<!-- Nav category menu START -->
			<div class="navbar-collapse collapse" id="navbarCategoryCollapse">
				<ul class="navbar-nav navbar-nav-scroll nav-pills-primary-soft text-center ms-auto p-2 p-xl-0">
					<!-- Nav item Legal -->
					<li class="nav-item"> <a class="nav-link active" href="/projetweb/index1.php"><i class="fa-solid fa-gavel me-2"></i>Legal</a>	</li>

					<!-- Nav item Notices -->
					<li class="nav-item"> <a class="nav-link" href="/projetweb/index1.php"><i class="fa-solid fa-bullhorn me-2"></i>Notices</a>	</li>

					<!-- Nav item Reports -->
					<li class="nav-item"> <a class="nav-link" href="/projetweb/index1.php"><i class="fa-solid fa-file-contract me-2"></i>Reports</a> </li>
				</ul>
			</div>
			<!-- Nav category menu END -->

			<!-- Profile and Notification START -->
			<ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">

				<!-- Notification dropdown START -->
				<li class="nav-item dropdown ms-0 ms-md-3">
					<!-- Notification button -->
					<a class="nav-notification btn btn-light p-0 mb-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
						<i class="bi bi-bell fa-fw"></i>
					</a>
					<!-- Notification dote -->
					<span class="notif-badge animation-blink"></span>

					<!-- Notification dropdown menu START -->
					<div class="dropdown-menu dropdown-animation dropdown-menu-end dropdown-menu-size-md shadow-lg p-0">
						<div class="card bg-transparent">
							<!-- Card header -->
							<div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
								<h6 class="m-0">Notifications <span class="badge bg-primary bg-opacity-10 text-primary ms-2">2 new</span></h6>
								<a class="small" href="#">Clear all</a>
							</div>

							<!-- Card body START -->
							<div class="card-body p-0">
								<ul class="list-group list-group-flush list-unstyled p-2">
									<!-- Notification item -->
									<li>
										<div class="list-group-item list-group-item-action rounded notif-unread border-0 mb-1 p-3">
											<h6 class="mb-2">New Regulation Published 📜</h6>
											<p class="mb-0 small">The latest legal framework for digital governance has been uploaded.</p>
											<span>2 hours ago</span>
										</div>
									</li>
									<!-- Notification item -->
									<li>
										<div class="list-group-item list-group-item-action rounded border-0 mb-1 p-3">
											<h6 class="mb-2">System Audit Complete 🔒</h6>
											<p class="mb-0 small">Weekly security and data integrity verification successful.</p>
											<span>Yesterday</span>
										</div>
									</li>
								</ul>
							</div>
							<!-- Card body END -->

							<!-- Card footer -->
							<div class="card-footer bg-transparent text-center border-top">
								<a href="#" class="btn btn-sm btn-link mb-0 p-0">See all incoming activity</a>
							</div>
						</div>
					</div>
					<!-- Notification dropdown menu END -->
				</li>
				<!-- Notification dropdown END -->

				<!-- Profile dropdown START -->
				<li class="nav-item ms-3 dropdown">
					<!-- Avatar -->
					<a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
						<img class="avatar-img rounded-2" src="assets/images/avatar/01.jpg" alt="avatar">
					</a>

					<ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
						<!-- Profile info -->
						<li class="px-3 mb-3">
							<div class="d-flex align-items-center">
								<!-- Avatar -->
								<div class="avatar me-3">
									<img class="avatar-img rounded-circle shadow" src="assets/images/avatar/01.jpg" alt="avatar">
								</div>
								<div>
									<a class="h6 mt-2 mt-sm-0" href="#">Lori Ferguson</a>
									<p class="small m-0">example@gmail.com</p>
								</div>
							</div>
						</li>

						<!-- Links -->
						<li> <hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-check fa-fw me-2"></i>Saved Documents</a></li>
						<li><a class="dropdown-item" href="#"><i class="bi bi-clock-history fa-fw me-2"></i>Reading History</a></li>
						<li><a class="dropdown-item" href="#"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
						<li><a class="dropdown-item" href="#"><i class="bi bi-info-circle fa-fw me-2"></i>Help Center</a></li>
						<li><a class="dropdown-item bg-danger-soft-hover" href="#"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
						<li> <hr class="dropdown-divider"></li>

						<!-- Dark mode options START -->
						<li>
							<div class="nav-pills-primary-soft theme-icon-active d-flex justify-content-between align-items-center p-2 pb-0">
								<span>Mode:</span>
								<button type="button" class="btn btn-link nav-link text-primary-hover mb-0 p-0" data-bs-theme-value="light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Light">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun fa-fw mode-switch" viewBox="0 0 16 16">
										<path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
										<use href="#"></use>
									</svg>
								</button>
								<button type="button" class="btn btn-link nav-link text-primary-hover mb-0 p-0" data-bs-theme-value="dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Dark">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars fa-fw mode-switch" viewBox="0 0 16 16">
										<path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z"/>
										<path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
										<use href="#"></use>
									</svg>
								</button>
								<button type="button" class="btn btn-link nav-link text-primary-hover mb-0 p-0 active" data-bs-theme-value="auto" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Auto">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half fa-fw mode-switch" viewBox="0 0 16 16">
										<path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
										<use href="#"></use>
									</svg>
								</button>
							</div>
						</li> 
						<!-- Dark mode options END-->
					</ul>
				</li>
				<!-- Profile dropdown END -->
			</ul>
			<!-- Profile and Notification START -->

		</div>
	</nav>
	<!-- Logo Nav END -->
</header>
<!-- Header END -->

<!-- **************** MAIN CONTENT START **************** -->
<main>
	
<!-- =======================
Main Banner START -->
<section class="pt-3 pt-lg-5 mesh-gradient overflow-hidden">
	<div class="container">
			<!-- Content and Image START -->
			<div class="row g-4 g-lg-5 align-items-center">
				<!-- Content -->
				<div class="col-lg-6 position-relative mb-4 mb-lg-0 animate-fade-in">
					<div class="d-inline-flex align-items-center bg-dark border border-secondary rounded-pill px-3 py-1 mb-4 shadow-sm">
						<span class="status-dot"></span>
						<span class="small fw-bold text-success">System Status: Online</span>
					</div>
					<h1 class="mb-4 display-4 text-white fw-bold lh-sm">
						The New Way to Store <span class="text-primary">State</span> Files.
					</h1>
					<!-- Info -->
					<p class="mb-4 fs-5 text-secondary pe-lg-5">Read official papers and state documents in one safe place. Everything is verified and secure.</p>

					<!-- Buttons -->
					<div class="hstack gap-3 flex-wrap align-items-center">
						<a href="/projetweb/index1.php" class="btn btn-primary btn-lg rounded-pill mb-0 shadow-lg px-5 py-3 fw-bold">Open Records</a>
						<a href="/projetweb/view/back-office/index.php" class="btn btn-link text-white btn-lg mb-0 px-4"><i class="fas fa-user-lock me-2"></i>Admin Login</a>
					</div>

					<!-- Trust Bar -->
					<div class="mt-5 pt-4 border-top border-secondary">
						<p class="small text-muted mb-3 fw-bold text-uppercase">Used by</p>
						<div class="d-flex gap-4 opacity-75">
							<i class="fas fa-landmark fs-4 text-white"></i>
							<i class="fas fa-balance-scale fs-4 text-white"></i>
							<i class="fas fa-university fs-4 text-white"></i>
						</div>
					</div>
				</div>

				<!-- Image/Visual -->
				<div class="col-lg-6 position-relative animate-fade-in">
					<div class="rounded-5 p-5 shadow-2xl overflow-hidden position-relative h-100 border border-secondary border-4" style="background: #1e293b; min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
						<div class="bg-primary bg-opacity-20 rounded-circle p-4 mb-4">
							<i class="fas fa-file-shield text-primary" style="font-size: 6rem;"></i>
						</div>
						<div class="position-relative text-center z-index-9">
							<h3 class="text-white mb-3">Safe Registry</h3>
							<p class="text-secondary px-md-5">All files are saved permanently and cannot be changed.</p>
							
							<div class="bg-dark bg-opacity-50 rounded-4 p-3 mt-4 border border-secondary">
								<div class="d-flex align-items-center gap-3">
									<div class="icon-sm bg-success rounded-circle"><i class="fas fa-check text-white"></i></div>
									<div class="text-start">
										<p class="mb-0 text-white small fw-bold">Verified File System</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Content and Image END -->
	</div>
</section>
<!-- =======================
Main Banner END -->

<!-- =======================
Institutional Stats START -->
<section class="pb-2 pb-lg-5">
	<div class="container">
		<div class="row g-4">
			<div class="col-md-4">
				<div class="card bg-primary bg-opacity-10 border-0 p-4 h-100 rounded-4">
					<div class="d-flex align-items-center">
						<div class="icon-lg bg-primary text-white rounded-circle me-3"><i class="fas fa-file-shield"></i></div>
						<div>
							<h5 class="mb-0">Immutable Access</h5>
							<p class="mb-0 small text-muted">Records verified by eDossier Governance</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card bg-success bg-opacity-10 border-0 p-4 h-100 rounded-4">
					<div class="d-flex align-items-center">
						<div class="icon-lg bg-success text-white rounded-circle me-3"><i class="fas fa-fingerprint"></i></div>
						<div>
							<h5 class="mb-0">Cryptographic Security</h5>
							<p class="mb-0 small text-muted">End-to-end institutional integrity</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card bg-info bg-opacity-10 border-0 p-4 h-100 rounded-4">
					<div class="d-flex align-items-center">
						<div class="icon-lg bg-info text-white rounded-circle me-3"><i class="fas fa-network-wired"></i></div>
						<div>
							<h5 class="mb-0">Global Scalability</h5>
							<p class="mb-0 small text-muted">Managing documentation at national scale</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- =======================
Institutional Stats END -->

<!-- =======================
About START -->
<section class="pb-0 pb-xl-5">
	<div class="container">
		<div class="row g-4 justify-content-between align-items-center">
			<!-- Left side START -->
			<div class="col-lg-5 position-relative">
				<!-- Svg Decoration -->
				<figure class="position-absolute top-0 start-0 translate-middle z-index-1 ms-4">
					<svg class="fill-warning" width="77px" height="77px">
						<path d="M76.997,41.258 L45.173,41.258 L67.676,63.760 L63.763,67.673 L41.261,45.171 L41.261,76.994 L35.728,76.994 L35.728,45.171 L13.226,67.673 L9.313,63.760 L31.816,41.258 L-0.007,41.258 L-0.007,35.725 L31.816,35.725 L9.313,13.223 L13.226,9.311 L35.728,31.813 L35.728,-0.010 L41.261,-0.010 L41.261,31.813 L63.763,9.311 L67.676,13.223 L45.174,35.725 L76.997,35.725 L76.997,41.258 Z"/>
					</svg>
				</figure>

				<!-- Svg decoration -->
				<figure class="position-absolute bottom-0 end-0 d-none d-md-block mb-n5 me-n4">
					<svg height="400" class="fill-primary opacity-2" viewBox="0 0 340 340">
						<circle cx="194.2" cy="2.2" r="2.2"></circle>
						<circle cx="2.2" cy="2.2" r="2.2"></circle>
						<circle cx="218.2" cy="2.2" r="2.2"></circle>
						<circle cx="26.2" cy="2.2" r="2.2"></circle>
						<circle cx="242.2" cy="2.2" r="2.2"></circle>
						<circle cx="50.2" cy="2.2" r="2.2"></circle>
						<circle cx="266.2" cy="2.2" r="2.2"></circle>
						<circle cx="74.2" cy="2.2" r="2.2"></circle>
						<circle cx="290.2" cy="2.2" r="2.2"></circle>
						<circle cx="98.2" cy="2.2" r="2.2"></circle>
						<circle cx="314.2" cy="2.2" r="2.2"></circle>
						<circle cx="122.2" cy="2.2" r="2.2"></circle>
						<circle cx="338.2" cy="2.2" r="2.2"></circle>
						<circle cx="146.2" cy="2.2" r="2.2"></circle>
						<circle cx="170.2" cy="2.2" r="2.2"></circle>
						<circle cx="194.2" cy="26.2" r="2.2"></circle>
						<circle cx="2.2" cy="26.2" r="2.2"></circle>
						<circle cx="218.2" cy="26.2" r="2.2"></circle>
						<circle cx="26.2" cy="26.2" r="2.2"></circle>
						<circle cx="242.2" cy="26.2" r="2.2"></circle>
						<circle cx="50.2" cy="26.2" r="2.2"></circle>
						<circle cx="266.2" cy="26.2" r="2.2"></circle>
						<circle cx="74.2" cy="26.2" r="2.2"></circle>
						<circle cx="290.2" cy="26.2" r="2.2"></circle>
						<circle cx="98.2" cy="26.2" r="2.2"></circle>
						<circle cx="314.2" cy="26.2" r="2.2"></circle>
						<circle cx="122.2" cy="26.2" r="2.2"></circle>
						<circle cx="338.2" cy="26.2" r="2.2"></circle>
						<circle cx="146.2" cy="26.2" r="2.2"></circle>
						<circle cx="170.2" cy="26.2" r="2.2"></circle>
						<circle cx="194.2" cy="50.2" r="2.2"></circle>
						<circle cx="2.2" cy="50.2" r="2.2"></circle>
						<circle cx="218.2" cy="50.2" r="2.2"></circle>
						<circle cx="26.2" cy="50.2" r="2.2"></circle>
						<circle cx="242.2" cy="50.2" r="2.2"></circle>
						<circle cx="50.2" cy="50.2" r="2.2"></circle>
						<circle cx="266.2" cy="50.2" r="2.2"></circle>
						<circle cx="74.2" cy="50.2" r="2.2"></circle>
						<circle cx="290.2" cy="50.2" r="2.2"></circle>
						<circle cx="98.2" cy="50.2" r="2.2"></circle>
						<circle cx="314.2" cy="50.2" r="2.2"></circle>
						<circle cx="122.2" cy="50.2" r="2.2"></circle>
						<circle cx="338.2" cy="50.2" r="2.2"></circle>
						<circle cx="146.2" cy="50.2" r="2.2"></circle>
						<circle cx="170.2" cy="50.2" r="2.2"></circle>
						<circle cx="194.2" cy="74.2" r="2.2"></circle>
						<circle cx="2.2" cy="74.2" r="2.2"></circle>
						<circle cx="218.2" cy="74.2" r="2.2"></circle>
						<circle cx="26.2" cy="74.2" r="2.2"></circle>
						<circle cx="242.2" cy="74.2" r="2.2"></circle>
						<circle cx="50.2" cy="74.2" r="2.2"></circle>
						<circle cx="266.2" cy="74.2" r="2.2"></circle>
						<circle cx="74.2" cy="74.2" r="2.2"></circle>
						<circle cx="290.2" cy="74.2" r="2.2"></circle>
						<circle cx="98.2" cy="74.2" r="2.2"></circle>
						<circle cx="314.2" cy="74.2" r="2.2"></circle>
						<circle cx="122.2" cy="74.2" r="2.2"></circle>
						<circle cx="338.2" cy="74.2" r="2.2"></circle>
						<circle cx="146.2" cy="74.2" r="2.2"></circle>
						<circle cx="170.2" cy="74.2" r="2.2"></circle>
						<circle cx="194.2" cy="98.2" r="2.2"></circle>
						<circle cx="2.2" cy="98.2" r="2.2"></circle>
						<circle cx="218.2" cy="98.2" r="2.2"></circle>
						<circle cx="26.2" cy="98.2" r="2.2"></circle>
						<circle cx="242.2" cy="98.2" r="2.2"></circle>
						<circle cx="50.2" cy="98.2" r="2.2"></circle>
						<circle cx="266.2" cy="98.2" r="2.2"></circle>
						<circle cx="74.2" cy="98.2" r="2.2"></circle>
						<circle cx="290.2" cy="98.2" r="2.2"></circle>
						<circle cx="98.2" cy="98.2" r="2.2"></circle>
						<circle cx="314.2" cy="98.2" r="2.2"></circle>
						<circle cx="122.2" cy="98.2" r="2.2"></circle>
						<circle cx="338.2" cy="98.2" r="2.2"></circle>
						<circle cx="146.2" cy="98.2" r="2.2"></circle>
						<circle cx="170.2" cy="98.2" r="2.2"></circle>
						<circle cx="194.2" cy="122.2" r="2.2"></circle>
						<circle cx="2.2" cy="122.2" r="2.2"></circle>
						<circle cx="218.2" cy="122.2" r="2.2"></circle>
						<circle cx="26.2" cy="122.2" r="2.2"></circle>
						<circle cx="242.2" cy="122.2" r="2.2"></circle>
						<circle cx="50.2" cy="122.2" r="2.2"></circle>
						<circle cx="266.2" cy="122.2" r="2.2"></circle>
						<circle cx="74.2" cy="122.2" r="2.2"></circle>
						<circle cx="290.2" cy="122.2" r="2.2"></circle>
						<circle cx="98.2" cy="122.2" r="2.2"></circle>
						<circle cx="314.2" cy="122.2" r="2.2"></circle>
						<circle cx="122.2" cy="122.2" r="2.2"></circle>
						<circle cx="338.2" cy="122.2" r="2.2"></circle>
						<circle cx="146.2" cy="122.2" r="2.2"></circle>
						<circle cx="170.2" cy="122.2" r="2.2"></circle>
						<circle cx="194.2" cy="146.2" r="2.2"></circle>
						<circle cx="2.2" cy="146.2" r="2.2"></circle>
						<circle cx="218.2" cy="146.2" r="2.2"></circle>
						<circle cx="26.2" cy="146.2" r="2.2"></circle>
						<circle cx="242.2" cy="146.2" r="2.2"></circle>
						<circle cx="50.2" cy="146.2" r="2.2"></circle>
						<circle cx="266.2" cy="146.2" r="2.2"></circle>
						<circle cx="74.2" cy="146.2" r="2.2"></circle>
						<circle cx="290.2" cy="146.2" r="2.2"></circle>
						<circle cx="98.2" cy="146.2" r="2.2"></circle>
						<circle cx="314.2" cy="146.2" r="2.2"></circle>
						<circle cx="122.2" cy="146.2" r="2.2"></circle>
						<circle cx="338.2" cy="146.2" r="2.2"></circle>
						<circle cx="146.2" cy="146.2" r="2.2"></circle>
						<circle cx="170.2" cy="146.2" r="2.2"></circle>
						<circle cx="194.2" cy="170.2" r="2.2"></circle>
						<circle cx="2.2" cy="170.2" r="2.2"></circle>
						<circle cx="218.2" cy="170.2" r="2.2"></circle>
						<circle cx="26.2" cy="170.2" r="2.2"></circle>
						<circle cx="242.2" cy="170.2" r="2.2"></circle>
						<circle cx="50.2" cy="170.2" r="2.2"></circle>
						<circle cx="266.2" cy="170.2" r="2.2"></circle>
						<circle cx="74.2" cy="170.2" r="2.2"></circle>
						<circle cx="290.2" cy="170.2" r="2.2"></circle>
						<circle cx="98.2" cy="170.2" r="2.2"></circle>
						<circle cx="314.2" cy="170.2" r="2.2"></circle>
						<circle cx="122.2" cy="170.2" r="2.2"></circle>
						<circle cx="338.2" cy="170.2" r="2.2"></circle>
						<circle cx="146.2" cy="170.2" r="2.2"></circle>
						<circle cx="170.2" cy="170.2" r="2.2"></circle>
						<circle cx="194.2" cy="194.2" r="2.2"></circle>
						<circle cx="2.2" cy="194.2" r="2.2"></circle>
						<circle cx="218.2" cy="194.2" r="2.2"></circle>
						<circle cx="26.2" cy="194.2" r="2.2"></circle>
						<circle cx="242.2" cy="194.2" r="2.2"></circle>
						<circle cx="50.2" cy="194.2" r="2.2"></circle>
						<circle cx="266.2" cy="194.2" r="2.2"></circle>
						<circle cx="74.2" cy="194.2" r="2.2"></circle>
						<circle cx="290.2" cy="194.2" r="2.2"></circle>
						<circle cx="98.2" cy="194.2" r="2.2"></circle>
						<circle cx="314.2" cy="194.2" r="2.2"></circle>
						<circle cx="122.2" cy="194.2" r="2.2"></circle>
						<circle cx="338.2" cy="194.2" r="2.2"></circle>
						<circle cx="146.2" cy="194.2" r="2.2"></circle>
						<circle cx="170.2" cy="194.2" r="2.2"></circle>
						<circle cx="194.2" cy="218.2" r="2.2"></circle>
						<circle cx="2.2" cy="218.2" r="2.2"></circle>
						<circle cx="218.2" cy="218.2" r="2.2"></circle>
						<circle cx="26.2" cy="218.2" r="2.2"></circle>
						<circle cx="242.2" cy="218.2" r="2.2"></circle>
						<circle cx="50.2" cy="218.2" r="2.2"></circle>
						<circle cx="266.2" cy="218.2" r="2.2"></circle>
						<circle cx="74.2" cy="218.2" r="2.2"></circle>
						<circle cx="290.2" cy="218.2" r="2.2"></circle>
						<circle cx="98.2" cy="218.2" r="2.2"></circle>
						<circle cx="314.2" cy="218.2" r="2.2"></circle>
						<circle cx="122.2" cy="218.2" r="2.2"></circle>
						<circle cx="338.2" cy="218.2" r="2.2"></circle>
						<circle cx="146.2" cy="218.2" r="2.2"></circle>
						<circle cx="170.2" cy="218.2" r="2.2"></circle>
						<circle cx="194.2" cy="242.2" r="2.2"></circle>
						<circle cx="2.2" cy="242.2" r="2.2"></circle>
						<circle cx="218.2" cy="242.2" r="2.2"></circle>
						<circle cx="26.2" cy="242.2" r="2.2"></circle>
						<circle cx="242.2" cy="242.2" r="2.2"></circle>
						<circle cx="50.2" cy="242.2" r="2.2"></circle>
						<circle cx="266.2" cy="242.2" r="2.2"></circle>
						<circle cx="74.2" cy="242.2" r="2.2"></circle>
						<circle cx="290.2" cy="242.2" r="2.2"></circle>
						<circle cx="98.2" cy="242.2" r="2.2"></circle>
						<circle cx="314.2" cy="242.2" r="2.2"></circle>
						<circle cx="122.2" cy="242.2" r="2.2"></circle>
						<circle cx="338.2" cy="242.2" r="2.2"></circle>
						<circle cx="146.2" cy="242.2" r="2.2"></circle>
						<circle cx="170.2" cy="242.2" r="2.2"></circle>
						<circle cx="194.2" cy="266.2" r="2.2"></circle>
						<circle cx="2.2" cy="266.2" r="2.2"></circle>
						<circle cx="218.2" cy="266.2" r="2.2"></circle>
						<circle cx="26.2" cy="266.2" r="2.2"></circle>
						<circle cx="242.2" cy="266.2" r="2.2"></circle>
						<circle cx="50.2" cy="266.2" r="2.2"></circle>
						<circle cx="266.2" cy="266.2" r="2.2"></circle>
						<circle cx="74.2" cy="266.2" r="2.2"></circle>
						<circle cx="290.2" cy="266.2" r="2.2"></circle>
						<circle cx="98.2" cy="266.2" r="2.2"></circle>
						<circle cx="314.2" cy="266.2" r="2.2"></circle>
						<circle cx="122.2" cy="266.2" r="2.2"></circle>
						<circle cx="338.2" cy="266.2" r="2.2"></circle>
						<circle cx="146.2" cy="266.2" r="2.2"></circle>
						<circle cx="170.2" cy="266.2" r="2.2"></circle>
						<circle cx="194.2" cy="290.2" r="2.2"></circle>
						<circle cx="2.2" cy="290.2" r="2.2"></circle>
						<circle cx="218.2" cy="290.2" r="2.2"></circle>
						<circle cx="26.2" cy="290.2" r="2.2"></circle>
						<circle cx="242.2" cy="290.2" r="2.2"></circle>
						<circle cx="50.2" cy="290.2" r="2.2"></circle>
						<circle cx="266.2" cy="290.2" r="2.2"></circle>
						<circle cx="74.2" cy="290.2" r="2.2"></circle>
						<circle cx="290.2" cy="290.2" r="2.2"></circle>
						<circle cx="98.2" cy="290.2" r="2.2"></circle>
						<circle cx="314.2" cy="290.2" r="2.2"></circle>
						<circle cx="122.2" cy="290.2" r="2.2"></circle>
						<circle cx="338.2" cy="290.2" r="2.2"></circle>
						<circle cx="146.2" cy="290.2" r="2.2"></circle>
						<circle cx="170.2" cy="290.2" r="2.2"></circle>
						<circle cx="194.2" cy="314.2" r="2.2"></circle>
						<circle cx="2.2" cy="314.2" r="2.2"></circle>
						<circle cx="218.2" cy="314.2" r="2.2"></circle>
						<circle cx="26.2" cy="314.2" r="2.2"></circle>
						<circle cx="242.2" cy="314.2" r="2.2"></circle>
						<circle cx="50.2" cy="314.2" r="2.2"></circle>
						<circle cx="266.2" cy="314.2" r="2.2"></circle>
						<circle cx="74.2" cy="314.2" r="2.2"></circle>
						<circle cx="290.2" cy="314.2" r="2.2"></circle>
						<circle cx="98.2" cy="314.2" r="2.2"></circle>
						<circle cx="314.2" cy="314.2" r="2.2"></circle>
						<circle cx="122.2" cy="314.2" r="2.2"></circle>
						<circle cx="338.2" cy="314.2" r="2.2"></circle>
						<circle cx="146.2" cy="314.2" r="2.2"></circle>
						<circle cx="170.2" cy="314.2" r="2.2"></circle>
						<circle cx="194.2" cy="338.2" r="2.2"></circle>
						<circle cx="2.2" cy="338.2" r="2.2"></circle>
						<circle cx="218.2" cy="338.2" r="2.2"></circle>
						<circle cx="26.2" cy="338.2" r="2.2"></circle>
						<circle cx="242.2" cy="338.2" r="2.2"></circle>
						<circle cx="50.2" cy="338.2" r="2.2"></circle>
						<circle cx="266.2" cy="338.2" r="2.2"></circle>
						<circle cx="74.2" cy="338.2" r="2.2"></circle>
						<circle cx="290.2" cy="338.2" r="2.2"></circle>
						<circle cx="98.2" cy="338.2" r="2.2"></circle>
						<circle cx="314.2" cy="338.2" r="2.2"></circle>
						<circle cx="122.2" cy="338.2" r="2.2"></circle>
						<circle cx="338.2" cy="338.2" r="2.2"></circle>
						<circle cx="146.2" cy="338.2" r="2.2"></circle>
						<circle cx="170.2" cy="338.2" r="2.2"></circle>
					</svg>
				</figure>

				<!-- Image -->
				<div class="rounded-4 overflow-hidden shadow-lg">
					<img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&q=80&w=1000" class="img-fluid" alt="Digital Governance">
				</div>
			</div>
			<!-- Left side END -->

			<!-- Right side START -->
			<div class="col-lg-6">
				<h2 class="mb-4 text-white fw-bold">Main Features</h2>
				<p class="mb-5 fs-5 text-secondary">A simple way to find and verify state files.</p>

				<!-- Features START -->
				<div class="row g-4">
					<!-- Item -->
					<div class="col-sm-6 animate-fade-in">
						<div class="card card-body glass-card border-0 p-3 hover-lift">
							<div class="icon-lg bg-success bg-opacity-10 text-success rounded-3"><i class="fa-solid fa-check-circle"></i></div>
							<h5 class="mt-3">Verified Files</h5>
							<p class="mb-0 small text-secondary">Every file is checked for accuracy.</p>
						</div>
					</div>
					<!-- Item -->
					<div class="col-sm-6 animate-fade-in">
						<div class="card card-body glass-card border-0 p-3 hover-lift">
							<div class="icon-lg bg-danger bg-opacity-10 text-danger rounded-3"><i class="bi bi-lock-fill"></i></div>
							<h5 class="mt-3">Safe History</h5>
							<p class="mb-0 small text-secondary">We keep a permanent log of all changes.</p>
						</div>
					</div>
					<!-- Item -->
					<div class="col-sm-6 animate-fade-in">
						<div class="card card-body glass-card border-0 p-3 hover-lift">
							<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-3"><i class="bi bi-speedometer2"></i></div>
							<h5 class="mt-3">Fast Access</h5>
							<p class="mb-0 small text-secondary">Find any document in seconds.</p>
						</div>
					</div>
					<!-- Item -->
					<div class="col-sm-6 animate-fade-in">
						<div class="card card-body glass-card border-0 p-3 hover-lift">
							<div class="icon-lg bg-info bg-opacity-10 text-info rounded-3"><i class="bi bi-cloud-check-fill"></i></div>
							<h5 class="mt-3">Cloud Sync</h5>
							<p class="mb-0 small text-secondary">Files are updated across all systems.</p>
						</div>
					</div>		
				</div>
				<!-- Features END -->
				<!-- Features END -->
				<!-- Features END -->

			</div>
			<!-- Right side END -->
		</div>
	</div>
</section>
<!-- =======================
About END -->

<!-- =======================
Featured Publications START -->
<section>
	<div class="container">

		<!-- Title -->
		<div class="row mb-5 align-items-center">
			<div class="col-md-8">
				<h2 class="mb-2">Latest Documents</h2>
				<p class="mb-0 text-secondary">A list of all recent state files and updates.</p>
			</div>
			<div class="col-md-4 text-md-end mt-3 mt-md-0">
				<a href="/projetweb/index1.php" class="btn btn-primary btn-sm rounded-pill mb-0">See All Records</a>
			</div>
		</div>

		<div class="row g-4">
			<!-- Featured Asset item -->
			<?php if (isset($list) && count($list) > 0): ?>
					<?php foreach (array_slice($list, 0, 8) as $p): ?>
						<div class="col-sm-6 col-lg-4 col-xl-3 animate-fade-in">
							<!-- Card START -->
							<div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-mode-item hover-lift glass-card">
								<!-- Iconic Header Overlay -->
								<div class="card-header border-0 bg-transparent p-4 pb-0">
									<div class="d-flex justify-content-between align-items-center">
										<span class="badge bg-<?= $p['categorie'] == 'Law' ? 'danger' : ($p['categorie'] == 'Announcement' ? 'warning text-dark' : 'info') ?>-soft rounded-pill px-3 py-2 small fw-bold tracking-tight"><?= strtoupper(htmlspecialchars($p['categorie'])) ?></span>
										<div class="icon-sm bg-success bg-opacity-10 text-success rounded-circle" data-bs-toggle="tooltip" title="Verified Integrity"><i class="fas fa-shield-check tiny"></i></div>
									</div>
								</div>
								<!-- Card body -->
								<div class="card-body p-4 pt-3 d-flex flex-column">
									<!-- Title -->
									<h5 class="card-title mb-3 lh-base"><a href="/projetweb/index1.php?action=show&id=<?= $p['id'] ?>" class="stretched-link text-reset"><?= htmlspecialchars($p['titre']) ?></a></h5>
									<p class="card-text small text-muted mb-4"><?= htmlspecialchars(substr($p['contenu'], 0, 85)) ?>...</p>
							
									<!-- Metadata -->
									<div class="mt-auto pt-3 border-top border-light">
										<div class="d-flex justify-content-between align-items-center">
											<div class="d-flex align-items-center">
												<div class="avatar avatar-xxs me-2">
													<div class="avatar-img rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 24px; height: 24px; font-size: 10px;">
														<?= strtoupper(substr($p['auteur'], 0, 1)) ?>
													</div>
												</div>
												<span class="tiny fw-bold text-dark text-uppercase"><?= htmlspecialchars($p['auteur']) ?></span>
											</div>
											<div class="d-flex align-items-center gap-2">
												<span class="text-muted tiny"><i class="bi bi-chat-dots me-1"></i><?= $p['comment_count'] ?></span>
												<span class="text-muted tiny"><i class="bi bi-calendar-event me-1"></i><?= date('d M', strtotime($p['date'])) ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Card END -->
						</div>
					<?php endforeach; ?>
			<?php else: ?>
					<div class="col-12 text-center py-5">
						<div class="icon-xxl bg-light rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
							<i class="fas fa-database opacity-25 display-3"></i>
						</div>
						<h4 class="text-muted">Archive Matrix Empty</h4>
						<p class="text-secondary small">No institutional assets localized in the current synchronization stream.</p>
					</div>
			<?php endif; ?>
		</div> <!-- Row END -->

	</div>
</section>
<!-- =======================
Featured Publications END -->
<!-- =======================
Platform Capabilities START -->
<section class="pb-0 py-md-5">
	<div class="container">
		<div class="row mb-5">
			<div class="col-12 text-center">
				<h2 class="mb-2">System Tools</h2>
				<p class="mb-0 text-secondary">Simple tools to manage and check your files.</p>
			</div>
		</div>

		<div class="row g-4">
			<!-- Item -->
			<div class="col-md-6 col-lg-4">
				<div class="card card-body glass-card border-0 h-100 p-4 hover-lift animate-fade-in">
					<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mb-4"><i class="fas fa-signature fs-4"></i></div>
					<h5 class="mb-3">Digital Proof</h5>
					<p class="small text-secondary">All records have a digital signature to prove they are real.</p>
				</div>
			</div>

			<!-- Item -->
			<div class="col-md-6 col-lg-4">
				<div class="card card-body glass-card border-0 h-100 p-4 hover-lift animate-fade-in">
					<div class="icon-lg bg-success bg-opacity-10 text-success rounded-circle mb-4"><i class="fas fa-share-alt fs-4"></i></div>
					<h5 class="mb-3">Easy Sharing</h5>
					<p class="small text-secondary">Share files with other departments in one click.</p>
				</div>
			</div>

			<!-- Item -->
			<div class="col-md-6 col-lg-4">
				<div class="card card-body glass-card border-0 h-100 p-4 hover-lift animate-fade-in">
					<div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle mb-4"><i class="fas fa-chart-bar fs-4"></i></div>
					<h5 class="mb-3">Stats View</h5>
					<p class="small text-secondary">See how many people read or downloaded the files.</p>
				</div>
			</div>
		</div>
	</div>
</section>
<style>
	.hover-translate-y { transition: transform 0.3s ease; }
	.hover-translate-y:hover { transform: translateY(-10px); }
</style>
<!-- =======================
Platform Capabilities END -->

<!-- =======================
Institutional Ecosystem START -->
<section class="py-5">
	<div class="container">
		<div class="row mb-5">
			<div class="col-12 text-center">
				<h2 class="mb-2">Partners</h2>
				<p class="mb-0 text-secondary">The groups that work with us.</p>
			</div>
		</div>

		<div class="row g-4 justify-content-center">
			<!-- Vector 1 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-balance-scale"></i>
					</div>
					<h6 class="small fw-bold mb-1">Justice</h6>
					<p class="tiny text-muted mb-0">Legal Compliance</p>
				</div>
			</div>
			<!-- Vector 2 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-landmark"></i>
					</div>
					<h6 class="small fw-bold mb-1">Legislature</h6>
					<p class="tiny text-muted mb-0">National Core</p>
				</div>
			</div>
			<!-- Vector 3 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-university"></i>
					</div>
					<h6 class="small fw-bold mb-1">Bureaucracy</h6>
					<p class="tiny text-muted mb-0">Administrative</p>
				</div>
			</div>
			<!-- Vector 4 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-shield-alt"></i>
					</div>
					<h6 class="small fw-bold mb-1">Cyber-Guard</h6>
					<p class="tiny text-muted mb-0">Security Dept</p>
				</div>
			</div>
			<!-- Vector 5 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-microchip"></i>
					</div>
					<h6 class="small fw-bold mb-1">Informatics</h6>
					<p class="tiny text-muted mb-0">Digital Infra</p>
				</div>
			</div>
			<!-- Vector 6 -->
			<div class="col-6 col-sm-4 col-lg-2">
				<div class="card glass-card border-0 p-3 h-100 text-center hover-lift">
					<div class="icon-lg bg-dark bg-opacity-10 text-dark rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
						<i class="fas fa-fingerprint"></i>
					</div>
					<h6 class="small fw-bold mb-1">Identity</h6>
					<p class="tiny text-muted mb-0">Verification</p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- =======================
Institutional Ecosystem END -->


<!-- =======================
Download app START -->
<section class="bg-light pb-0">
	<div class="container overflow-hidden">
		<div class="row g-4 justify-content-between align-items-center">

			<!-- Help -->
			<div class="col-md-6 col-lg-5">
				<h2 class="mb-4">Administrative Mobile Access</h2>
				<p class="mb-5">Access the eDossier archive from any secure device with our citizen-centric application, designed for real-time institutional awareness.</p>
				<div class="hstack gap-3">
					<a href="#" class="btn btn-dark btn-lg rounded-pill px-4"><i class="bi bi-apple me-2"></i>App Store</a>
					<a href="#" class="btn btn-dark btn-lg rounded-pill px-4"><i class="bi bi-google-play me-2"></i>Play Store</a>
				</div>
			</div>

			<!-- Image -->
			<div class="col-md-6 text-center">
				<div class="bg-primary bg-opacity-10 rounded-circle p-5 d-inline-block shadow-sm">
					<i class="fas fa-mobile-alt display-1 text-primary"></i>
				</div>
			</div>

		</div>
	</div>
</section>
<!-- =======================
Download app END -->

</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
Footer START -->
<footer class="bg-dark pt-5">
	<div class="container">
		<!-- Row START -->
		<div class="row g-4">

			<!-- Widget 1 START -->
			<div class="col-lg-3">
				<!-- logo -->
				<a href="/projetweb/index.php">
					<h3 class="text-white">Edossier</h3>
				</a>
				<p class="my-3 text-body-secondary">The state matrix for official publications and digital institutional archives.</p>
				<p class="mb-2"><a href="#" class="text-body-secondary text-primary-hover"><i class="bi bi-telephone me-2"></i>+216 71 000 000</a> </p>
				<p class="mb-0"><a href="#" class="text-body-secondary text-primary-hover"><i class="bi bi-envelope me-2"></i>support@edossier.gov</a></p>
			</div>
			<!-- Widget 1 END -->
			<!-- Widget 1 END -->

			<!-- Widget 2 START -->
			<div class="col-lg-8 ms-auto">
				<div class="row g-4">
					<!-- Link block -->
					<div class="col-6 col-md-3">
						<h5 class="text-white mb-2 mb-md-4">About</h5>
						<ul class="nav flex-column text-primary-hover">
							<li class="nav-item"><a class="nav-link text-body-secondary" href="about.html">Our System</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="contact.html">Contact Us</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Data Stats</a></li>
						</ul>
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-3">
						<h5 class="text-white mb-2 mb-md-4">Rules</h5>
						<ul class="nav flex-column text-primary-hover">
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Policy</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Login Rules</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Privacy</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Terms</a></li>
						</ul>
					</div>
									
					<!-- Link block -->
					<div class="col-6 col-md-3">
						<h5 class="text-white mb-2 mb-md-4">Groups</h5>
						<ul class="nav flex-column text-primary-hover">
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Main Office</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">Justice</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="#">State Hub</a></li>
						</ul>
					</div>

					<!-- Link block -->
					<div class="col-6 col-md-3">
						<h5 class="text-white mb-2 mb-md-4">Files</h5>
						<ul class="nav flex-column text-primary-hover">
							<li class="nav-item"><a class="nav-link text-body-secondary" href="/projetweb/index1.php">Laws</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="/projetweb/index1.php">Acts</a></li>
							<li class="nav-item"><a class="nav-link text-body-secondary" href="/projetweb/index1.php">Notices</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- Widget 2 END -->

		</div><!-- Row END -->

		<!-- Tops Links -->
		<div class="row mt-5">
			<h5 class="mb-2 text-white">Institutional Directories</h5>
			<ul class="list-inline text-primary-hover lh-lg">
				<li class="list-inline-item"><a href="/projetweb/index1.php" class="text-body-secondary">State Gazettes</a></li>
				<li class="list-inline-item"><a href="/projetweb/index1.php" class="text-body-secondary">Legal Amendments</a></li>
				<li class="list-inline-item"><a href="/projetweb/index1.php" class="text-body-secondary">Public Notices</a></li>
				<li class="list-inline-item"><a href="/projetweb/index1.php" class="text-body-secondary">Institutional Records</a></li>
				<li class="list-inline-item"><a href="about.html" class="text-body-secondary">About Platform</a></li>
				<li class="list-inline-item"><a href="contact.html" class="text-body-secondary">Contact Governance</a></li>
				<li class="list-inline-item"><a href="#" class="text-body-secondary">Transparency Policy</a></li>
				<li class="list-inline-item"><a href="#" class="text-body-secondary">System Status</a></li>
			</ul>
		</div>

		<!-- Payment and card -->
		<div class="row g-4 justify-content-between mt-0 mt-md-2">

			<!-- Security Widget START -->
			<div class="col-sm-7 col-md-6 col-lg-4">
				<h5 class="text-white mb-2">Safe Records</h5>
				<p class="text-body-secondary small">Every document is safe and verified. We make sure all data stays correct and permanent.</p>
			</div>
			<!-- Security Widget END -->

			<!-- Social media icon -->
			<div class="col-sm-5 col-md-6 col-lg-3 text-sm-end">
				<h5 class="text-white mb-2">Follow us on</h5>
				<ul class="list-inline mb-0 mt-3">
					<li class="list-inline-item"> <a class="btn btn-sm px-2 bg-facebook mb-0" href="#"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-sm shadow px-2 bg-instagram mb-0" href="#"><i class="fab fa-fw fa-instagram"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-sm shadow px-2 bg-twitter mb-0" href="#"><i class="fab fa-fw fa-twitter"></i></a> </li>
					<li class="list-inline-item"> <a class="btn btn-sm shadow px-2 bg-linkedin mb-0" href="#"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
				</ul>	
			</div>
		</div>

		<!-- Divider -->
		<hr class="mt-4 mb-0">

		<!-- Bottom footer -->
		<div class="row">
			<div class="container">
				<div class="d-lg-flex justify-content-between align-items-center py-3 text-center text-lg-start">
					<!-- copyright text -->
					<div class="text-body-secondary text-primary-hover"> Copyrights ©2026 Edossier Platform. All Rights Reserved. <a href="https://www.webestica.com/" class="text-body-secondary">All Rights Reserved.</a>. </div>
					<!-- copyright links-->
					<div class="nav mt-2 mt-lg-0">
						<ul class="list-inline text-primary-hover mx-auto mb-0">
							<li class="list-inline-item me-0"><a class="nav-link text-body-secondary py-1" href="#">Privacy policy</a></li>
							<li class="list-inline-item me-0"><a class="nav-link text-body-secondary py-1" href="#">Terms and conditions</a></li>
							<li class="list-inline-item me-0"><a class="nav-link text-body-secondary py-1 pe-0" href="#">Refund policy</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- =======================
Footer END -->

<!-- Back to top -->
<div class="back-top"></div>

<!-- Navbar mobile START -->
<div class="navbar navbar-mobile">
	<ul class="navbar-nav">
		<!-- Nav item Home -->
		<li class="nav-item">
			<a class="nav-link active" href="/projetweb/index.php"><i class="bi bi-house-door fa-fw"></i>
				<span class="mb-0 nav-text">Home</span>
			</a>	
		</li>

		<!-- Nav item Publications -->
		<li class="nav-item"> 
			<a class="nav-link" href="/projetweb/index1.php"><i class="bi bi-file-earmark-text fa-fw"></i>
				<span class="mb-0 nav-text">Files</span>
			</a>	
		</li>

		<!-- Nav item Dashboard -->
		<li class="nav-item"> 
			<a class="nav-link" href="/projetweb/view/back-office/index.php"><i class="bi bi-shield-lock fa-fw"></i>
				<span class="mb-0 nav-text">Login</span> 
			</a>
		</li>
	</ul>
</div>
<!-- Navbar mobile END -->

<!-- Bootstrap JS -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="assets/vendor/tiny-slider/tiny-slider.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.js"></script>
<script src="assets/vendor/flatpickr/js/flatpickr.min.js"></script>
<script src="assets/vendor/choices/js/choices.min.js"></script>

<!-- ThemeFunctions -->
<script src="assets/js/functions.js"></script>

</body>
<!-- Mirrored from edossier template -->
</html>
