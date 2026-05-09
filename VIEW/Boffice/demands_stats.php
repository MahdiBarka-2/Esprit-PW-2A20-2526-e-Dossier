<?php
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../MODEL/Database.php';
require_once '../../CONTROLLER/demandeC.php';

// ── Feature 1 : Données réelles pour les graphiques demandes ──────────────────
$database = new Database();
$db       = $database->getConnection();
$dc       = new demandeC();

// 1. Nombre de demandes par mois (année en cours)
$stmtMois   = $db->query("SELECT MONTH(created_at) as mois, COUNT(*) as total FROM demande WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY MONTH(created_at) ORDER BY mois");
$donneesMois = array_fill(0, 12, 0);
foreach ($stmtMois->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $donneesMois[(int)$row['mois'] - 1] = (int)$row['total'];
}

// 2. Répartition par catégorie
$stmtCat    = $db->query("SELECT categorie.nom, COUNT(demande.id) as total FROM demande, categorie WHERE demande.categorie_id = categorie.id GROUP BY categorie.id, categorie.nom ORDER BY total DESC LIMIT 6");
$parCat     = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
$catLabels  = json_encode(array_column($parCat, 'nom'));
$catData    = json_encode(array_map('intval', array_column($parCat, 'total')));

// 3. Approuvées vs Rejetées par mois
$stmtStat   = $db->query("SELECT MONTH(created_at) as mois, SUM(statut='approuvee') as ok, SUM(statut='rejetee') as rej FROM demande WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY mois ORDER BY mois");
$approuvees = array_fill(0, 12, 0);
$rejetees   = array_fill(0, 12, 0);
foreach ($stmtStat->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $approuvees[(int)$row['mois'] - 1] = (int)$row['ok'];
    $rejetees[(int)$row['mois']   - 1] = (int)$row['rej'];
}

// 4. Stats globales
$demandesAll = $dc->listeDemandes()->fetchAll();
$totalAll    = count($demandesAll);
$totalApp    = count(array_filter($demandesAll, fn($d) => $d['statut'] === 'approuvee'));
$totalRej    = count(array_filter($demandesAll, fn($d) => $d['statut'] === 'rejetee'));
$totalAtt    = $dc->getCountEnAttente();
$txApprob    = $totalAll > 0 ? round(($totalApp / $totalAll) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>
<head>
	<title>Statistiques Demandes - E-Dossier</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script>
		const storedTheme = localStorage.getItem('theme');
		const getPreferredTheme = () => {
			if (storedTheme) return storedTheme;
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
		};
		const setTheme = function (theme) {
			if (theme === 'auto') {
				document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme);
			}
		};
		setTheme(getPreferredTheme());
	</script>
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/apexcharts/css/apexcharts.css">
	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
</head>
<body>
	<main>
        <?php include 'sidebar.php'; ?>
        <div class="page-content">
            <?php include 'topbar.php'; ?>
			<div class="page-content-wrapper p-xxl-4">

				<div class="row mb-4">
					<div class="col-12 d-sm-flex justify-content-between align-items-center">
						<div>
                            <h1 class="h3 mb-2 mb-sm-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Statistiques des Demandes</h1>
                            <p class="text-muted small mb-0">Données en temps réel depuis la base de données &mdash; année <?= date('Y') ?></p>
                        </div>
                        <a href="demands.php" class="btn btn-outline-primary mb-0"><i class="bi bi-arrow-left me-2"></i>Retour aux demandes</a>
					</div>
				</div>

				<!-- KPI mini-cards -->
				<div class="row g-3 mb-4">
					<div class="col-6 col-lg-3">
						<div class="card border-0 shadow-sm text-center py-3">
							<div class="fw-bold fs-3 text-primary"><?= $totalAll ?></div>
							<small class="text-muted">Total demandes</small>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="card border-0 shadow-sm text-center py-3">
							<div class="fw-bold fs-3 text-warning"><?= $totalAtt ?></div>
							<small class="text-muted">En attente</small>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="card border-0 shadow-sm text-center py-3">
							<div class="fw-bold fs-3 text-success"><?= $totalApp ?></div>
							<small class="text-muted">Approuvées</small>
						</div>
					</div>
					<div class="col-6 col-lg-3">
						<div class="card border-0 shadow-sm text-center py-3">
							<div class="fw-bold fs-3 text-info"><?= $txApprob ?>%</div>
							<small class="text-muted">Taux d'approbation</small>
						</div>
					</div>
				</div>

				<!-- Graphiques -->
				<div class="row g-4 mb-4">
					<div class="col-lg-8">
						<div class="card border-0 shadow-sm h-100">
							<div class="card-header bg-transparent border-bottom">
								<h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Demandes par mois</h6>
							</div>
							<div class="card-body">
								<div id="chartDemandsMois"></div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="card border-0 shadow-sm h-100">
							<div class="card-header bg-transparent border-bottom">
								<h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Par catégorie</h6>
							</div>
							<div class="card-body d-flex align-items-center justify-content-center">
								<div id="chartDemandsCategorie" style="width:100%;"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-4 mb-4">
					<div class="col-12">
						<div class="card border-0 shadow-sm">
							<div class="card-header bg-transparent border-bottom">
								<h6 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Évolution Approuvées vs Rejetées</h6>
							</div>
							<div class="card-body">
								<div id="chartDemandsStatut"></div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</main>

    <?php require_once "footer.php"; ?>
	<script src="../../assets/vendor/apexcharts/js/apexcharts.min.js"></script>
	<script src="../../assets/js/functions.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

        var optionsMois = {
            series: [{ name: 'Demandes soumises', data: <?= json_encode($donneesMois) ?> }],
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            colors: ['#376cbe'],
            xaxis: { categories: moisLabels },
            dataLabels: { enabled: false },
            border: { radius: 4 }
        };
        new ApexCharts(document.querySelector("#chartDemandsMois"), optionsMois).render();

        var optionsCat = {
            series: <?= $catData ?>,
            labels: <?= $catLabels ?>,
            chart: { type: 'donut', height: 300 },
            colors: ['#1d3461', '#376cbe', '#4a85e8', '#198754', '#ffc107', '#dc3545'],
            dataLabels: { enabled: false },
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#chartDemandsCategorie"), optionsCat).render();

        var optionsStatut = {
            series: [
                { name: 'Approuvées', data: <?= json_encode($approuvees) ?> },
                { name: 'Rejetées', data: <?= json_encode($rejetees) ?> }
            ],
            chart: { type: 'area', height: 300, toolbar: { show: false } },
            colors: ['#198754', '#dc3545'],
            xaxis: { categories: moisLabels },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05, stops: [0, 100] } }
        };
        new ApexCharts(document.querySelector("#chartDemandsStatut"), optionsStatut).render();
    });
    </script>
</body>
</html>
