<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Agent Dashboard – Jobs & Réservations</title>

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme');
		const getPreferredTheme = () => storedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
		const setTheme = (theme) => {
			document.documentElement.setAttribute('data-bs-theme',
				theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : theme);
		};
		setTheme(getPreferredTheme());
	</script>

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">

	<style>
		:root {
			--bs-primary: #5143d9;
			--agent-sidebar-bg: #f8f9fa;
		}
		[data-bs-theme="dark"] { --agent-sidebar-bg: #1e1e2d; }
		body { font-family: 'DM Sans', sans-serif; }
		.header-sticky { position: sticky; top: 0; z-index: 1030; background: var(--bs-body-bg); border-bottom: 1px solid var(--bs-border-color); }
		.agent-menu-card { border-radius: .75rem; }
		.dash-tabs .nav-link { color: var(--bs-secondary-color); font-weight: 500; border-radius: .5rem; padding: .45rem .85rem; }
		.dash-tabs .nav-link.active { background: var(--bs-primary); color: #fff; }
		.dash-tabs .nav-link i { margin-right: .35rem; }
		.stat-card { border-radius: .75rem; border: 1px solid var(--bs-border-color); }
		.stat-icon { width: 52px; height: 52px; border-radius: .6rem; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fff; }
		.section-label { font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: .6rem; }
		.req { color: #dc3545; }
		.pill { display: inline-block; padding: .2rem .65rem; border-radius: 999px; font-size: .75rem; font-weight: 600; }
		.pill-ok   { background: #d1fae5; color: #065f46; }
		.pill-warn { background: #fef3c7; color: #92400e; }
		.pill-ko   { background: #fee2e2; color: #991b1b; }
		[data-bs-theme="dark"] .pill-ok   { background: #064e3b; color: #6ee7b7; }
		[data-bs-theme="dark"] .pill-warn { background: #451a03; color: #fcd34d; }
		[data-bs-theme="dark"] .pill-ko   { background: #450a0a; color: #fca5a5; }
		.admin-table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .05em; color: var(--bs-secondary-color); }
		.btn-icon { background: none; border: none; cursor: pointer; font-size: 1rem; padding: .2rem .4rem; border-radius: .35rem; transition: background .15s; }
		.btn-icon:hover { background: var(--bs-secondary-bg); }
		.btn-icon.danger  { color: #dc3545; }
		.btn-icon.warning { color: #f59e0b; }
		.info-row { display: flex; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid var(--bs-border-color); font-size: .88rem; }
		.info-row:last-child { border-bottom: none; }
		.btn-block { display: block; width: 100%; margin-bottom: .5rem; padding: .55rem; border-radius: .5rem; border: 1px solid var(--bs-border-color); background: var(--bs-body-bg); color: var(--bs-body-color); cursor: pointer; font-size: .88rem; text-align: left; transition: background .15s; }
		.btn-block:hover { background: var(--bs-secondary-bg); }
		.hidden { display: none !important; }
		.tab-section { display: none; }
		.tab-section.active { display: block; }
	</style>
</head>

<body>

  <header class="navbar-light py-3 border-bottom shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://placehold.co/60x60?text=E-Dossier" alt="logo" style="height: 60px;">
        <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
      </a>
      <div class="d-flex align-items-center">
        <nav class="navbar-expand-lg">
          <ul class="nav">
            <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link nav-link-custom" href="../BackOffice/index.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link nav-link-custom" href="#">Events</a></li>
            <li class="nav-item"><a class="nav-link nav-link-custom active" href="../FrontOffice/condidature.html">Condidature</a></li>
          </ul>
        </nav>
        <div class="dropdown ms-3">
          <button class="btn btn-light btn-sm mb-0 px-2" type="button" data-bs-toggle="dropdown"><i
              class="bi bi-globe me-1"></i> FR</button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">EN</a></li>
            <li><a class="dropdown-item" href="#">FR</a></li>
          </ul>
        </div>
        <div class="dropdown ms-3">
          <button class="btn btn-light btn-sm lh-0 mb-0" data-bs-toggle="dropdown"><i
              class="bi bi-circle-half"></i></button>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" data-bs-theme-value="light">Light</button></li>
            <li><button class="dropdown-item" data-bs-theme-value="dark">Dark</button></li>
            <li><button class="dropdown-item" data-bs-theme-value="auto">Auto</button></li>
          </ul>
        </div>
        <div class="ms-3">
          <a href="#" class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center"
            style="width:40px;height:40px;"><i class="bi bi-person fs-5"></i></a>
        </div>
      </div>
    </div>
  </header>

	<!-- ══════════════ AGENT PROFILE CARD ══════════════ -->
	<section class="pt-4 pb-0">
		<div class="container">
			<div class="card agent-menu-card border p-3 pb-2">
				<div class="d-sm-flex align-items-center mb-2">
					<div class="avatar mb-2 mb-sm-0 me-sm-3">
						<img src="images/avatar/01.jpg" class="rounded-circle avatar-img" width="56" height="56" alt="">
					</div>
					<h5 class="mb-2 mb-sm-0"><span class="fw-light">Bonjour,</span> Jacqueline Miller</h5>
					<a href="#" class="btn btn-sm btn-primary-soft mb-0 ms-auto" onclick="showTab('ajouter-job'); return false;">
						<i class="bi bi-plus-lg me-1"></i>Nouveau job
					</a>
				</div>

				<!-- Dashboard nav tabs -->
				<nav class="dash-tabs">
					<ul class="nav flex-wrap gap-1">
						<li class="nav-item"><a class="nav-link active" href="#" onclick="showTab('dashboard'); return false;"><i class="bi bi-house-door"></i>Dashboard</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('jobs'); return false;"><i class="bi bi-briefcase"></i>Jobs</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('candidatures'); return false;"><i class="bi bi-people"></i>Candidatures</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('ajouter-job'); return false;"><i class="bi bi-plus-circle"></i>Ajouter un job</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('settings'); return false;"><i class="bi bi-gear"></i>Paramètres</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</section>

	<!-- ══════════════ MAIN ══════════════ -->
	<main class="py-4">
		<div class="container">
			<div class="row g-4">

				<!-- ── LEFT / MAIN CONTENT ── -->
				<div class="col-lg-8 col-xl-9">

					<!-- ════ TAB: DASHBOARD ════ -->
					<div id="tab-dashboard" class="tab-section active">
						<h4 class="mb-3"><i class="bi bi-house-door me-2"></i>Dashboard</h4>
						<div class="row g-3 mb-4">
							<div class="col-6 col-xl-3">
								<div class="card stat-card p-3 h-100">
									<div class="d-flex align-items-center gap-3">
										<div class="stat-icon bg-success"><i class="bi bi-journals"></i></div>
										<div><h5 class="mb-0" id="stat-jobs-dash">3</h5><small class="text-muted">Jobs publiés</small></div>
									</div>
								</div>
							</div>
							<div class="col-6 col-xl-3">
								<div class="card stat-card p-3 h-100">
									<div class="d-flex align-items-center gap-3">
										<div class="stat-icon bg-info"><i class="bi bi-graph-up-arrow"></i></div>
										<div><h5 class="mb-0">$2,553</h5><small class="text-muted">Revenus</small></div>
									</div>
								</div>
							</div>
							<div class="col-6 col-xl-3">
								<div class="card stat-card p-3 h-100">
									<div class="d-flex align-items-center gap-3">
										<div class="stat-icon bg-warning"><i class="bi bi-people"></i></div>
										<div><h5 class="mb-0" id="stat-cands-dash">5</h5><small class="text-muted">Candidatures</small></div>
									</div>
								</div>
							</div>
							<div class="col-6 col-xl-3">
								<div class="card stat-card p-3 h-100">
									<div class="d-flex align-items-center gap-3">
										<div class="stat-icon bg-primary"><i class="bi bi-star"></i></div>
										<div><h5 class="mb-0">12K</h5><small class="text-muted">Avis</small></div>
									</div>
								</div>
							</div>
						</div>
						<div class="card border rounded-3">
							<div class="card-header border-bottom d-flex justify-content-between align-items-center">
								<h6 class="mb-0">Réservations à venir</h6>
								<a href="#" class="btn btn-sm btn-primary" onclick="showTab('reservations'); return false;">Voir tout</a>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-hover align-middle mb-0 admin-table">
										<thead class="table-light">
											<tr><th>#</th><th>Nom</th><th>Type</th><th>Dates</th><th>Statut</th><th>Paiement</th><th>Action</th></tr>
										</thead>
										<tbody>
											<tr><td>01</td><td><a href="#">Deluxe Pool View</a></td><td>Avec petit-déj</td><td>22–25 Nov</td><td><span class="badge text-bg-success">Réservé</span></td><td><span class="badge bg-success bg-opacity-10 text-success">Complet</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
											<tr><td>02</td><td><a href="#">Luxury Balcony Room</a></td><td>Annulation gratuite</td><td>24–28 Nov</td><td><span class="badge text-bg-info">Réservé</span></td><td><span class="badge bg-warning bg-opacity-10 text-warning">Sur place</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
											<tr><td>03</td><td><a href="#">Twin Bed Deluxe</a></td><td>Petit-déj inclus</td><td>28–30 Nov</td><td><span class="badge text-bg-info">En attente</span></td><td><span class="badge bg-info bg-opacity-10 text-info">Acompte</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- ════ TAB: RESERVATIONS ════ -->
					<div id="tab-reservations" class="tab-section">
						<h4 class="mb-3"><i class="bi bi-bookmark-heart me-2"></i>Mes Réservations</h4>
						<div class="card border">
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-hover align-middle mb-0 admin-table">
										<thead class="table-light">
											<tr><th>#</th><th>Chambre</th><th>Type</th><th>Dates</th><th>Statut</th><th>Paiement</th><th>Action</th></tr>
										</thead>
										<tbody>
											<tr><td>01</td><td>Deluxe Pool View</td><td>Avec petit-déj</td><td>22–25 Nov</td><td><span class="badge text-bg-success">Réservé</span></td><td><span class="badge bg-success bg-opacity-10 text-success">Complet</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
											<tr><td>02</td><td>Luxury Balcony Room</td><td>Annulation gratuite</td><td>24–28 Nov</td><td><span class="badge text-bg-info">Réservé</span></td><td><span class="badge bg-warning bg-opacity-10 text-warning">Sur place</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
											<tr><td>03</td><td>Twin Bed Deluxe</td><td>Petit-déj + déjeuner</td><td>28–30 Nov</td><td><span class="badge text-bg-warning">En attente</span></td><td><span class="badge bg-info bg-opacity-10 text-info">Acompte</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
											<tr><td>04</td><td>Suite Présidentielle</td><td>Tout inclus</td><td>01–05 Dec</td><td><span class="badge text-bg-success">Réservé</span></td><td><span class="badge bg-success bg-opacity-10 text-success">Complet</span></td><td><a href="#" class="btn btn-sm btn-light">Voir</a></td></tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- ════ TAB: JOBS LIST ════ -->
					<div id="tab-jobs" class="tab-section">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h4 class="mb-0"><i class="bi bi-briefcase me-2"></i>Liste des jobs</h4>
							<button class="btn btn-sm btn-primary" onclick="showTab('ajouter-job')"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
						</div>
						<div class="card border">
							<div class="card-body">
								<div class="mb-3 d-flex gap-2">
									<input type="text" class="form-control" placeholder="Rechercher un job…" id="job-search-input" oninput="filterTable('jobs-table','job-search-input')">
									<button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV('jobs-table','jobs.csv')"><i class="bi bi-download me-1"></i>Export</button>
								</div>
								<div class="table-responsive">
									<table class="table table-hover align-middle admin-table" id="jobs-table">
										<thead class="table-light">
											<tr><th>Réf</th><th>Titre</th><th>Contrat</th><th>Lieu</th><th>Statut</th><th>Deadline</th><th>Actions</th></tr>
										</thead>
										<tbody id="jobs-tbody"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- ════ TAB: CANDIDATURES ════ -->
					<div id="tab-candidatures" class="tab-section">
						<h4 class="mb-3"><i class="bi bi-people me-2"></i>Candidatures reçues</h4>
						<div class="card border">
							<div class="card-body">
								<div class="mb-3 d-flex gap-2">
									<input type="text" class="form-control" placeholder="Filtrer par job, nom, statut…" id="cand-search-input" oninput="filterTable('cands-table','cand-search-input')">
									<button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV('cands-table','candidatures.csv')"><i class="bi bi-download me-1"></i>Export</button>
								</div>
								<div class="table-responsive">
									<table class="table table-hover align-middle admin-table" id="cands-table">
										<thead class="table-light">
											<tr><th>Réf</th><th>Nom</th><th>Job</th><th>Date</th><th>Statut</th><th>Actions</th></tr>
										</thead>
										<!-- ✅ PHP ICI — dans le bon tbody -->
										<tbody>
											<?php
												require_once(__DIR__ . "/../../Controller/Candidature.php");
												$candidature = new CandidatureC();
												$candidatures = $candidature->getAll();
												if (empty($candidatures)) {
													echo '<tr><td colspan="6" class="text-center text-muted py-3">Aucune candidature trouvée.</td></tr>';
												} else {
													foreach ($candidatures as $c) {
														echo '<tr data-cand-id="' . htmlspecialchars($c['id']) . '">
															<td>' . htmlspecialchars($c['reference']) . '</td>
															<td>' . htmlspecialchars($c['nom']) . '</td>
															<td>' . htmlspecialchars($c['titre'] ? $c['titre'] : 'N/A') . '</td>
															<td>' . htmlspecialchars($c['date_candidature']) . '</td>
															<td><span class="pill pill-warn">En attente</span></td>
															<td>
																<button class="btn-icon text-success" title="Approuver" onclick="setStatus(this,\'pill-ok\',\'Approuvé\')">✓</button>
																<button class="btn-icon warning" title="Refuser" onclick="setStatus(this,\'pill-ko\',\'Refusé\')">✗</button>
																<form action="../../View/FrontOffice/SupprimerCondidature.php" method="post" style="display:inline">
																	<input type="hidden" name="id" value="' . htmlspecialchars($c['id']) . '" />
																	<button type="submit" class="btn-icon danger" title="Supprimer">🗑</button>
																</form>
															</td>
														</tr>';
													}
												}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- ════ TAB: AJOUTER / MODIFIER JOB ════ -->
					<div id="tab-ajouter-job" class="tab-section">
						<h4 class="mb-3"><i class="bi bi-plus-circle me-2"></i><span id="job-form-title">Ajouter un job</span></h4>
						<div class="card border">
							<div class="card-body">
								<div class="mb-4">
									<div class="section-label">Informations du job</div>
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label">Titre du job <span class="req">*</span></label>
											<input type="text" class="form-control" id="job-titre" placeholder="ex. Développeur Full Stack">
										</div>
										<div class="col-md-6">
											<label class="form-label">Référence</label>
											<input type="text" class="form-control" id="job-ref" placeholder="ex. JOB-2025-001">
										</div>
										<div class="col-md-6">
											<label class="form-label">Type de contrat</label>
											<select class="form-select" id="job-contrat">
												<option value="">— Sélectionner —</option>
												<option>CDI</option><option>CDD</option><option>Stage</option><option>Freelance</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label">Lieu</label>
											<input type="text" class="form-control" id="job-lieu" placeholder="ex. Tunis">
										</div>
										<div class="col-12">
											<label class="form-label">Description <span class="req">*</span></label>
											<textarea class="form-control" id="job-description" rows="4" placeholder="Missions, responsabilités, profil recherché…"></textarea>
										</div>
									</div>
								</div>
								<div class="mb-4">
									<div class="section-label">Paramètres de publication</div>
									<div class="row g-3">
										<div class="col-md-6">
											<label class="form-label">Statut</label>
											<select class="form-select" id="job-statut">
												<option value="brouillon">Brouillon</option>
												<option value="publie">Publié</option>
												<option value="ferme">Fermé</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label">Date limite de candidature</label>
											<input type="date" class="form-control" id="job-deadline">
										</div>
									</div>
								</div>
								<div class="d-flex gap-2 flex-wrap">
									<button class="btn btn-primary" id="job-save-btn" onclick="saveJob()"><i class="bi bi-save me-1"></i>Enregistrer</button>
									<button class="btn btn-outline-secondary hidden" id="job-update-btn" onclick="saveJob()">Mettre à jour</button>
<button class="btn btn-light hidden" id="job-cancel-edit-btn" onclick="resetJobForm()">Annuler</button>								</div>
							</div>
						</div>
					</div>

					<!-- ════ TAB: EARNINGS ════ -->
					<div id="tab-earnings" class="tab-section">
						<h4 class="mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Revenus</h4>
						<div class="card border p-4 text-center text-muted">
							<i class="bi bi-graph-up-arrow fs-1 mb-2"></i>
							<p>Les graphiques de revenus s'afficheraient ici (ApexCharts).</p>
						</div>
					</div>

					<!-- ════ TAB: SETTINGS ════ -->
					<div id="tab-settings" class="tab-section">
						<h4 class="mb-3"><i class="bi bi-gear me-2"></i>Paramètres</h4>
						<div class="card border p-4 text-center text-muted">
							<i class="bi bi-gear fs-1 mb-2"></i>
							<p>Les options de configuration apparaîtront ici.</p>
						</div>
					</div>

				</div><!-- /col main -->

				<!-- ── SIDEBAR ── -->
				<div class="col-lg-4 col-xl-3">

					<!-- Quick stats -->
					<div class="card border mb-3">
						<div class="card-header border-bottom py-2">
							<strong class="small">Statistiques rapides</strong>
						</div>
						<div class="card-body py-2 px-3">
							<div class="info-row"><span>Jobs publiés</span><strong id="side-jobs">3</strong></div>
							<div class="info-row"><span>Candidatures en attente</span><strong id="side-cands-wait">2</strong></div>
							<div class="info-row"><span>Candidatures approuvées</span><strong id="side-cands-ok">1</strong></div>
							<div class="info-row"><span>Candidatures refusées</span><strong id="side-cands-ko">0</strong></div>
						</div>
					</div>

					<!-- Quick actions -->
					<div class="card border">
						<div class="card-header border-bottom py-2">
							<strong class="small">Actions rapides</strong>
						</div>
						<!-- ✅ SIDEBAR : boutons uniquement, pas de PHP ici -->
						<div class="card-body py-2 px-3">
							<button class="btn-block" onclick="showTab('ajouter-job')"><i class="bi bi-plus-lg me-2"></i>Nouveau job</button>
							<button class="btn-block" onclick="showTab('jobs')"><i class="bi bi-briefcase me-2"></i>Gérer les jobs</button>
							<button class="btn-block" onclick="showTab('candidatures')"><i class="bi bi-people me-2"></i>Gérer les candidatures</button>
							<button class="btn-block" onclick="showTab('reservations')"><i class="bi bi-bookmark-heart me-2"></i>Voir les réservations</button>
						</div>
					</div>

				</div><!-- /sidebar -->

			</div><!-- /row -->
		</div><!-- /container -->
	</main>

	<!-- ══════════════ FOOTER ══════════════ -->
	<footer class="bg-dark py-3 mt-4">
		<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
			<span class="text-white fw-semibold"><i class="bi bi-briefcase-fill me-1"></i>BackOffice – Jobs & Réservations</span>
			<span class="text-secondary small">© 2025 — Module d'administration</span>
			<div class="d-flex gap-3">
				<a href="#" class="text-secondary small">Confidentialité</a>
				<a href="#" class="text-secondary small">Conditions</a>
				<a href="#" class="text-secondary small">Support</a>
			</div>
		</div>
	</footer>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		/* ── TAB SWITCHING ── */
		function showTab(name) {
			document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
			document.querySelectorAll('.dash-tabs .nav-link').forEach(l => l.classList.remove('active'));
			const section = document.getElementById('tab-' + name);
			if (section) section.classList.add('active');
			const link = [...document.querySelectorAll('.dash-tabs .nav-link')]
				.find(l => l.getAttribute('onclick')?.includes("'" + name + "'"));
			if (link) link.classList.add('active');
			return false;
		}

		/* ── TABLE FILTER ── */
		function filterTable(tableId, inputId) {
			const q = document.getElementById(inputId).value.toLowerCase();
			document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
				row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
			});
		}

		/* ── CANDIDATURE STATUS ── */
		function setStatus(btn, pillClass, label) {
			const pill = btn.closest('tr').querySelector('.pill');
			pill.className = 'pill ' + pillClass;
			pill.textContent = label;
			updateSideStats();
		}

		/* ── DELETE ROW ── */
		function deleteRow(btn) {
			if (confirm('Supprimer cet élément ?')) {
				btn.closest('tr').remove();
				updateSideStats();
			}
		}

		/* ── UPDATE SIDEBAR STATS ── */
		function updateSideStats() {
			const rows = document.querySelectorAll('#cands-table tbody tr');
			let wait = 0, ok = 0, ko = 0;
			rows.forEach(r => {
				const pill = r.querySelector('.pill');
				if (!pill) return;
				if (pill.classList.contains('pill-warn')) wait++;
				if (pill.classList.contains('pill-ok'))   ok++;
				if (pill.classList.contains('pill-ko'))   ko++;
			});
			document.getElementById('side-cands-wait').textContent = wait;
			document.getElementById('side-cands-ok').textContent   = ok;
			document.getElementById('side-cands-ko').textContent   = ko;
			document.getElementById('stat-cands-dash').textContent = rows.length;
		}

		/* ── SAVE JOB ── */
		let editingJobId = null;

		function saveJob() {
		const titre = document.getElementById('job-titre').value.trim();
		const description = document.getElementById('job-description').value.trim();
		if (!titre || !description) {
			alert('Veuillez remplir les champs obligatoires.');
			return;
		}

		const fd = new FormData();
		fd.append('titre', titre);
		fd.append('reference', document.getElementById('job-ref').value.trim());
		fd.append('lieu', document.getElementById('job-lieu').value.trim());
		fd.append('type', document.getElementById('job-contrat').value);
		fd.append('description', description);
		fd.append('date_limite', document.getElementById('job-deadline').value);
		fd.append('statut', document.getElementById('job-statut').value);

		if (editingJobId) {
			fd.append('action', 'modifier');
			fd.append('id', editingJobId);
		} else {
			fd.append('action', 'ajouter');
		}

		fetch('../../Controller/Job.php', { method: 'POST', body: fd })
			.then(r => r.text())
			.then(text => {
			alert(text);
			editingJobId = null;
			resetJobForm();
			
			showTab('jobs');
			})
			.catch(() => alert('Erreur de connexion au serveur.'));
		}

		function resetJobForm() {
			document.getElementById('job-titre').value = '';
			document.getElementById('job-ref').value = '';
			document.getElementById('job-contrat').value = '';
			document.getElementById('job-lieu').value = '';
			document.getElementById('job-description').value = '';
			document.getElementById('job-statut').value = 'brouillon';
			document.getElementById('job-deadline').value = '';
			document.getElementById('job-form-title').textContent = 'Ajouter un job';
			document.getElementById('job-save-btn').classList.remove('hidden');
			document.getElementById('job-update-btn').classList.add('hidden');
			document.getElementById('job-cancel-edit-btn').classList.add('hidden');
			editingJobId = null;
		}

		function editJob(id, job) {
			editingJobId = id;
			document.getElementById('job-titre').value = job.titre ?? '';
			document.getElementById('job-ref').value = job.reference ?? '';
			document.getElementById('job-contrat').value = job.type ?? '';
			document.getElementById('job-lieu').value = job.lieu ?? '';
			document.getElementById('job-description').value = job.description ?? '';
			document.getElementById('job-deadline').value = job.date_limite ?? '';
			document.getElementById('job-statut').value = job.statut ?? 'brouillon';
			document.getElementById('job-form-title').textContent = 'Modifier le job';
			document.getElementById('job-save-btn').classList.add('hidden');
			document.getElementById('job-update-btn').classList.remove('hidden');
			document.getElementById('job-cancel-edit-btn').classList.remove('hidden');
			showTab('ajouter-job');
		}
		

		function loadJobs() {
			const fd = new FormData();
			fd.append('action', 'getAll');

			fetch('../../Controller/Job.php', { method: 'POST', body: fd })
				.then(r => r.json())
				.then(jobs => {
				const tbody = document.getElementById('jobs-tbody');
				if (!jobs.length) {
					tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Aucun job trouvé.</td></tr>';
					return;
				}
				tbody.innerHTML = jobs.map(job => `
					<tr>
					<td>${job.reference ?? '—'}</td>
					<td>${job.titre}</td>
					<td>${job.type ?? '—'}</td>
					<td>${job.lieu ?? '—'}</td>
					<td><span class="pill ${job.statut === 'publie' ? 'pill-ok' : job.statut === 'ferme' ? 'pill-ko' : 'pill-warn'}">${job.statut ?? '—'}</span></td>
					<td>${job.date_limite ?? '—'}</td>
					<td>
						<button class="btn-icon" title="Modifier" onclick="editJob(${job.id}, ${JSON.stringify(job).replace(/"/g, '&quot;')})">✎</button>
						<button class="btn-icon danger" title="Supprimer" onclick="deleteJob(${job.id}, this)">🗑</button>
					</td>
					</tr>
				`).join('');
				})
				.catch(() => alert('Erreur de chargement des jobs.'));
		}

		function deleteJob(id, btn) {
			if (!confirm('Supprimer ce job ?')) return;
			const fd = new FormData();
			fd.append('action', 'supprimer');
			fd.append('id', id);
			fetch('../../Controller/Job.php', { method: 'POST', body: fd })
				.then(r => r.text())
			.then(() => btn.closest('tr').remove())
			.catch(() => alert('Erreur de suppression.'));
		}

		// call on page load
		loadJobs();


		/* ── EXPORT CSV ── */
		function exportToCSV(tableId, filename) {
			const rows = [...document.querySelectorAll('#' + tableId + ' tr')];
			const csv  = rows.map(r => [...r.querySelectorAll('th,td')]
				.slice(0, -1).map(c => '"' + c.textContent.trim().replace(/"/g,'""') + '"').join(',')).join('\n');
			const a = document.createElement('a');
			a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
			a.download = filename;
			a.click();
		}

		/* init */
		updateSideStats();
	</script>
</body>
</html>