<?php
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/UserController.php';

// Safe fetch for friend's user data
$totalClients = 0;
$recentUsers = null;
$hasUserTables = false;
try {
    $totalClients = countUsersByRole('client');
    $recentUsers = findRecentUsers(5);
    $hasUserTables = true;
} catch (Exception $e) {
    // Missing friend's tables, ignore gracefully
}

// Fetch Materiel & Mission stats
$db = new Database();
$conn = $db->getConnection();

// Total materiels
$stmt1 = $conn->query("SELECT COUNT(*) as total FROM materiels");
$total_materiels = $stmt1->fetch(PDO::FETCH_ASSOC)['total'];

// Ongoing missions
$stmt2 = $conn->query("SELECT COUNT(*) as total FROM missions WHERE etat = 'En cours' OR (CURDATE() BETWEEN date_debut AND date_fin AND (etat IS NULL OR etat = ''))");
$ongoing_missions = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];

// Materiels by status
$stmt3 = $conn->query("SELECT etat, COUNT(*) as count FROM materiels GROUP BY etat");
$materiels_par_etat = [];
while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
    $materiels_par_etat[$row['etat']] = $row['count'];
}

// Missions by status
$stmt4 = $conn->query("SELECT etat, COUNT(*) as count FROM missions GROUP BY etat");
$missions_par_etat = [];
while ($row = $stmt4->fetch(PDO::FETCH_ASSOC)) {
    $missions_par_etat[$row['etat']] = $row['count'];
}
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
					<!-- Counter item 1: Friend's stat -->
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

					<!-- Counter item 2: Total Materiels -->
					<div class="col-md-6 col-xxl-3">
						<a href="materiels.php" class="card card-body bg-primary bg-opacity-10 border border-primary border-opacity-25 p-4 h-100 text-decoration-none transition-all hover-shadow">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0 text-body"><?php echo number_format($total_materiels); ?></h4>
									<span class="h6 fw-light mb-0 text-body">Total Matériels</span>
								</div>
								<div class="icon-lg rounded-circle bg-primary text-white mb-0"><i
										class="fa-solid fa-toolbox fa-fw"></i></div>
							</div>
						</a>
					</div>

					<!-- Counter item 3: Ongoing Missions -->
					<div class="col-md-6 col-xxl-3">
						<a href="missions.php" class="card card-body bg-success bg-opacity-10 border border-success border-opacity-25 p-4 h-100 text-decoration-none transition-all hover-shadow">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h4 class="mb-0 text-body"><?php echo number_format($ongoing_missions); ?></h4>
									<span class="h6 fw-light mb-0 text-body">Missions en cours</span>
								</div>
								<div class="icon-lg rounded-circle bg-success text-white mb-0"><i
										class="fa-solid fa-rocket fa-fw"></i></div>
							</div>
						</a>
					</div>

					<!-- Counter item 4: Friend's stat -->
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

				<!-- Middle Row START -->
				<div class="row g-4 mb-5">
					<!-- Materiel Status List -->
					<div class="col-lg-6 col-xxl-4">
						<div class="card shadow h-100">
							<div class="card-header border-bottom">
								<h5 class="card-header-title"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Répartition des matériels</h5>
							</div>
							<div class="card-body p-4">
								<ul class="list-group list-group-borderless mb-0">
                                    <?php if (empty($materiels_par_etat)): ?>
                                        <li class="list-group-item">Aucun matériel enregistré.</li>
                                    <?php else: ?>
                                        <?php 
                                        $colors = ['Disponible' => 'success', 'En panne' => 'danger', 'En réparation' => 'warning', 'En mission' => 'info'];
                                        foreach ($materiels_par_etat as $etat => $count): 
                                            $color = $colors[$etat] ?? 'primary';
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center pb-3">
                                            <span class="h6 fw-light mb-0"><i class="text-<?php echo $color; ?> fas fa-circle me-2"></i><?php echo htmlspecialchars($etat); ?></span>
                                            <span class="badge bg-<?php echo $color; ?> rounded-pill fs-6"><?php echo htmlspecialchars($count); ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
								</ul>
							</div>
						</div>
					</div>

                    <!-- Mission Status List -->
					<div class="col-lg-6 col-xxl-4">
						<div class="card shadow h-100">
							<div class="card-header border-bottom">
								<h5 class="card-header-title"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Statut des Missions</h5>
							</div>
							<div class="card-body p-4">
								<ul class="list-group list-group-borderless mb-0">
                                    <?php if (empty($missions_par_etat)): ?>
                                        <li class="list-group-item">Aucune mission enregistrée.</li>
                                    <?php else: ?>
                                        <?php 
                                        $mColors = ['Planifiée' => 'secondary', 'En cours' => 'info', 'Terminée' => 'success', 'Annulée' => 'danger'];
                                        foreach ($missions_par_etat as $etat => $count): 
                                            $color = $mColors[$etat] ?? 'primary';
                                        ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center pb-3">
                                            <span class="h6 fw-light mb-0"><i class="text-<?php echo $color; ?> fas fa-circle me-2"></i><?php echo htmlspecialchars($etat ?: 'Non défini'); ?></span>
                                            <span class="badge bg-<?php echo $color; ?> rounded-pill fs-6"><?php echo htmlspecialchars($count); ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
								</ul>
							</div>
						</div>
					</div>

                    <!-- Small spacing for layouts -->
					<div class="col-xxl-4 d-none d-xxl-block">
                        <div class="card shadow h-100 bg-primary bg-opacity-10 border-0">
                            <div class="card-body d-flex align-items-center justify-content-center text-center p-4">
                                <div>
                                    <i class="bi bi-shield-fill-check text-primary display-4 mb-3"></i>
                                    <h5 class="text-primary">Système Sécurisé</h5>
                                    <p class="small text-body opacity-75">Toutes les données de la municipalité sont protégées et sauvegardées quotidiennement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
				<!-- Middle Row END -->

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
                                            <?php if ($hasUserTables && $recentUsers): ?>
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
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">User tables not available in current database schema.</td>
                                                </tr>
                                            <?php endif; ?>
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
</body>
</html>