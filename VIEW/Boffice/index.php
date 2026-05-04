<?php
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/UserController.php';

$totalClients = countUsersByRole('client');
$recentUsers = findRecentUsers(5);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
	<title>e_dossier - Management System</title>
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="e_dossier">
	<meta name="description" content="e_dossier Management System">

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

	<!-- Favicon -->
	<link rel="shortcut icon" href="../../assets/images/favicon.ico">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/apexcharts/css/apexcharts.css">

	<!-- Theme CSS -->
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
							<h1 class="h3 mb-2 mb-sm-0"><?php echo __('dashboard'); ?></h1>
						</div>
					</div>
				</div>

				<!-- Counter boxes START -->
				<div class="row g-4 mb-4">
					<!-- Counter item -->
					<div class="col-md-6 col-xxl-3">
						<a href="clients.php" class="card card-body bg-warning bg-opacity-10 border border-warning border-opacity-25 p-4 h-100 text-decoration-none transition-all hover-shadow">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0"><?php echo number_format($totalClients); ?></h4>
									<span class="h6 fw-light mb-0 text-body"><?php echo __('total_clients'); ?></span>
								</div>
								<div class="icon-lg rounded-circle bg-warning text-white mb-0"><i
										class="fa-solid fa-users fa-fw"></i></div>
							</div>
						</a>
					</div>

					<!-- Counter item -->
					<div class="col-md-6 col-xxl-3">
						<div
							class="card card-body bg-success bg-opacity-10 border border-success border-opacity-25 p-4 h-100">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0">856</h4>
									<span class="h6 fw-light mb-0">Total Dossiers</span>
								</div>
								<div class="icon-lg rounded-circle bg-success text-white mb-0"><i
										class="fa-solid fa-file-invoice fa-fw"></i></div>
							</div>
						</div>
					</div>

					<!-- Counter item -->
					<div class="col-md-6 col-xxl-3">
						<div
							class="card card-body bg-primary bg-opacity-10 border border-primary border-opacity-25 p-4 h-100">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0">42</h4>
									<span class="h6 fw-light mb-0">Active Materiels</span>
								</div>
								<div class="icon-lg rounded-circle bg-primary text-white mb-0"><i
										class="fa-solid fa-toolbox fa-fw"></i></div>
							</div>
						</div>
					</div>

					<!-- Counter item -->
					<div class="col-md-6 col-xxl-3">
						<div
							class="card card-body bg-info bg-opacity-10 border border-info border-opacity-25 p-4 h-100">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0">128</h4>
									<span class="h6 fw-light mb-0">New Demands</span>
								</div>
								<div class="icon-lg rounded-circle bg-info text-white mb-0"><i
										class="fa-solid fa-clipboard-list fa-fw"></i></div>
							</div>
						</div>
					</div>
				</div>
				<!-- Counter boxes END -->

				<!-- Charts START -->
				<div class="row g-4 mb-5">
					<!-- Client Sign-up Chart -->
					<div class="col-xxl-8">
						<div class="card shadow h-100">
							<div class="card-header border-bottom">
								<h5 class="card-header-title">Client Sign-up Activity</h5>
							</div>
							<div class="card-body">
								<!-- Apex chart -->
								<div id="ChartGuesttraffic" class="mt-2"></div>
							</div>
						</div>
					</div>

					<!-- Age Distribution Chart -->
					<div class="col-lg-6 col-xxl-4">
						<div class="card shadow h-100">
							<div class="card-header border-bottom">
								<h5 class="card-header-title">Client Age Distribution</h5>
							</div>
							<div class="card-body p-3">
								<div class="d-flex justify-content-center" id="ChartTrafficRooms"></div>
								<ul class="list-group list-group-borderless mb-0 mt-3">
									<li class="list-group-item d-flex justify-content-between">
										<span class="h6 fw-light mb-0"><i class="text-primary fas fa-circle me-2"></i>
											18-25 Years</span>
										<span class="h6 fw-light mb-0">35%</span>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<span class="h6 fw-light mb-0"><i class="text-info fas fa-circle me-2"></i>
											26-45 Years</span>
										<span class="h6 fw-light mb-0">50%</span>
									</li>
									<li class="list-group-item d-flex justify-content-between">
										<span class="h6 fw-light mb-0"><i class="text-warning fas fa-circle me-2"></i>
											45+ Years</span>
										<span class="h6 fw-light mb-0">15%</span>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- Charts END -->

				<!-- Recent Activity START -->
				<div class="row g-4">
					<div class="col-12">
						<div class="card shadow h-100">
							<div class="card-header border-bottom d-flex justify-content-between align-items-center">
								<h5 class="card-header-title">Recent User Registrations</h5>
								<a href="clients.php" class="btn btn-link p-0 mb-0">View all</a>
							</div>
							<div class="card-body">
								<div class="table-responsive border-0">
									<table class="table align-middle p-4 mb-0 table-hover">
										<thead class="table-light">
											<tr>
												<th scope="col" class="border-0 rounded-start">User</th>
												<th scope="col" class="border-0">Role</th>
												<th scope="col" class="border-0">Status</th>
												<th scope="col" class="border-0">Joined Date</th>
												<th scope="col" class="border-0 rounded-end text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php while ($user = $recentUsers->fetch(PDO::FETCH_ASSOC)): ?>
											<tr>
												<td>
													<div class="d-flex align-items-center">
														<div class="avatar avatar-sm me-2">
															<?php 
															$user_img = (isset($user['profile_image_url']) && !empty($user['profile_image_url'])) 
																		? $user['profile_image_url'] 
																		: '../../assets/images/avatar/01.jpg';
															?>
															<img src="<?php echo $user_img; ?>" class="rounded-circle" alt="">
														</div>
														<h6 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h6>
													</div>
												</td>
												<td><?php echo ucfirst($user['role']); ?></td>
												<td>
													<?php if ($user['status'] === 'active'): ?>
														<span class="badge bg-success bg-opacity-10 text-success">Active</span>
													<?php else: ?>
														<span class="badge bg-danger bg-opacity-10 text-danger"><?php echo ucfirst($user['status']); ?></span>
													<?php endif; ?>
												</td>
												<td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
												<td class="text-center">
													<a href="<?php echo ($user['role'] === 'client' ? 'client-detail.php' : 'agent-detail.php'); ?>?id=<?php echo $user['id']; ?>"
														class="btn btn-sm btn-light mb-0">View</a>
												</td>
											</tr>
											<?php endwhile; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Recent Activity END -->

			</div>


		</div> <!-- Page content END -->
	</main>
	<!-- Bootstrap JS -->
	<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Vendor Scripts -->
	<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
	<script src="../../assets/vendor/apexcharts/js/apexcharts.min.js"></script>

	<!-- Theme Functions -->
	<script src="../../assets/js/functions.js"></script>

    <?php include 'footer.php'; ?>
<?php
// Removed redundant script block that was causing duplication with functions.js
?>