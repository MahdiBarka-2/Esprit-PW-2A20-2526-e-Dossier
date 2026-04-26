<?php
require_once '../../CONTROLLER/LanguageController.php';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
	<title>e_dossier - Earnings</title>
	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="e_dossier">
	<meta name="description" content="e_dossier Management System">

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

			<div class="page-content-wrapper p-xxl-4">
				<div class="row">
					<div class="col-12 mb-4 mb-sm-5">
						<h1 class="h3 mb-0">Earnings Summary</h1>
					</div>
				</div>

				<div class="row g-4 mb-5">
					<div class="col-md-6 col-lg-4">
						<div class="card card-body shadow p-4 h-100">
							<h6 class="mb-3">Total Earnings</h6>
							<h2 class="mb-2 text-primary">$45,862.00</h2>
							<p class="mb-0"><span class="badge bg-success bg-opacity-10 text-success">+12% <i
										class="bi bi-graph-up"></i></span> vs last month</p>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="card card-body shadow p-4 h-100">
							<h6 class="mb-3">Service Fees</h6>
							<h2 class="mb-2 text-info">$8,245.50</h2>
							<p class="mb-0">Collected from dossiers</p>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="card card-body shadow p-4 h-100">
							<h6 class="mb-3">Pending Payouts</h6>
							<h2 class="mb-2 text-warning">$1,450.00</h2>
							<p class="mb-0">Awaiting verification</p>
						</div>
					</div>
				</div>

				<div class="row g-4 mb-5">
					<div class="col-12">
						<div class="card shadow">
							<div class="card-header border-bottom">
								<h5 class="card-header-title">Earnings Graph</h5>
							</div>
							<div class="card-body">
								<div id="EarningsChart"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="card shadow">
					<div class="card-header border-bottom">
						<h5 class="card-header-title">Recent Transactions</h5>
					</div>
					<div class="card-body">
						<div class="table-responsive border-0">
							<table class="table align-middle p-4 mb-0 table-hover">
								<thead class="table-light">
									<tr>
										<th class="border-0 rounded-start">ID</th>
										<th class="border-0">User</th>
										<th class="border-0">Dossier Type</th>
										<th class="border-0">Amount</th>
										<th class="border-0">Status</th>
										<th class="border-0 rounded-end">Date</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>#ED-8562</td>
										<td>John Doe</td>
										<td>Employment Dossier</td>
										<td>$150.00</td>
										<td><span class="badge bg-success bg-opacity-10 text-success">Paid</span></td>
										<td>12 April 2026</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
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

	<script>
		var options = {
			series: [{
				name: 'Earnings',
				data: [400, 500, 450, 600, 800, 750, 900, 1000, 1200, 1100, 1300, 1500]
			}],
			chart: {
				height: 350,
				type: 'line',
				toolbar: { show: false }
			},
			colors: ['#1d3b53'],
			stroke: { curve: 'smooth', width: 4 },
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
			}
		};
		var chart = new ApexCharts(document.querySelector("#EarningsChart"), options);
		chart.render();
	</script>

    <?php include 'footer.php'; ?>

</body>
</html>