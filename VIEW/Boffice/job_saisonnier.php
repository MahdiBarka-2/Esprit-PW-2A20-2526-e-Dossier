<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
?>
<!DOCTYPE html>
<html lang="fr">			

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Job Saisonnier – E-Dossier</title>

<script>
    const storedTheme = localStorage.getItem('theme');
    const getPreferredTheme = () => storedTheme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    const setTheme = (theme) => {
        document.documentElement.setAttribute('data-bs-theme',
            theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : theme);
    };
    setTheme(getPreferredTheme());

    window.addEventListener('DOMContentLoaded', () => {
        const showActiveTheme = theme => {
            document.querySelectorAll('[data-bs-theme-value]').forEach(el => el.classList.remove('active'));
            const activeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`);
            if (activeBtn) activeBtn.classList.add('active');
        };
        showActiveTheme(getPreferredTheme());
        document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const theme = toggle.getAttribute('data-bs-theme-value');
                localStorage.setItem('theme', theme);
                setTheme(theme);
                showActiveTheme(theme);
            });
        });
    });
</script>	
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="shortcut icon" href="../../assets/images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css">

	<style>
		/* ── Font matching Gestion des Demandes ── */
		body { font-family: 'Inter', 'DM Sans', sans-serif; }

		/* ── Page title ── */
		.page-title { font-size: 2rem; font-weight: 700; color: var(--bs-body-color); }
		.page-subtitle { color: var(--bs-secondary-color); font-size: .9rem; margin-top: .2rem; }

		/* ── Stat cards with colored left border ── */
		.stat-border-card {
			border-radius: .75rem;
			border: 1px solid var(--bs-border-color) !important;
			border-left-width: 4px !important;
			padding: 1.25rem 1.5rem;
		}
		.stat-border-card.blue  { border-left-color: #0d6efd !important; }
		.stat-border-card.yellow{ border-left-color: #ffc107 !important; }
		.stat-border-card.green { border-left-color: #198754 !important; }
		.stat-border-card.red   { border-left-color: #dc3545 !important; }
		.stat-border-card .stat-num { font-size: 2rem; font-weight: 700; line-height: 1; }
		.stat-border-card .stat-icon-wrap {
			width: 48px; height: 48px; border-radius: 50%;
			display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
		}
		.stat-border-card.blue  .stat-icon-wrap { background: #e7f1ff; color: #0d6efd; }
		.stat-border-card.yellow .stat-icon-wrap { background: #fff8e1; color: #ffc107; }
		.stat-border-card.green .stat-icon-wrap { background: #e9f7ef; color: #198754; }
		.stat-border-card.red   .stat-icon-wrap { background: #fdecea; color: #dc3545; }

		/* ── Top action buttons ── */
		.btn-action-group .btn {
			border-radius: .5rem;
			font-size: .85rem;
			font-weight: 500;
			padding: .45rem 1rem;
		}

		/* ── Tab nav ── */
		.dash-tabs .nav-link { color: var(--bs-secondary-color); font-weight: 500; border-radius: .5rem; padding: .45rem .85rem; font-size: .88rem; }
		.dash-tabs .nav-link.active { background: var(--bs-primary); color: #fff; }
		.dash-tabs .nav-link i { margin-right: .35rem; }

		/* ── Table ── */
		.admin-table thead th {
			font-size: .75rem; text-transform: uppercase;
			letter-spacing: .06em; color: var(--bs-secondary-color);
			font-weight: 600; border-bottom: 2px solid var(--bs-border-color);
			padding: .85rem 1rem;
		}
		.admin-table tbody td { padding: .85rem 1rem; font-size: .88rem; vertical-align: middle; }
		.admin-table tbody tr:hover { background: var(--bs-secondary-bg); }

		/* ── Pills ── */
		.pill { display: inline-flex; align-items: center; gap: .3rem; padding: .3rem .75rem; border-radius: 999px; font-size: .78rem; font-weight: 600; }
		.pill::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; opacity: .7; }
		.pill-ok   { background: #d1fae5; color: #065f46; }
		.pill-warn { background: #fef3c7; color: #92400e; }
		.pill-ko   { background: #fee2e2; color: #991b1b; }
		[data-bs-theme="dark"] .pill-ok   { background: #064e3b; color: #6ee7b7; }
		[data-bs-theme="dark"] .pill-warn { background: #451a03; color: #fcd34d; }
		[data-bs-theme="dark"] .pill-ko   { background: #450a0a; color: #fca5a5; }

		/* ── Misc ── */
		.section-label { font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--bs-secondary-color); margin-bottom: .6rem; }
		.req { color: #dc3545; }
		.btn-icon { background: none; border: none; cursor: pointer; font-size: 1rem; padding: .25rem .45rem; border-radius: .35rem; transition: background .15s; }
		.btn-icon:hover { background: var(--bs-secondary-bg); }
		.btn-icon.danger { color: #dc3545; }
		.btn-icon.warning { color: #f59e0b; }
		.info-row { display: flex; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid var(--bs-border-color); font-size: .88rem; }
		.info-row:last-child { border-bottom: none; }
		.btn-block { display: block; width: 100%; margin-bottom: .5rem; padding: .55rem; border-radius: .5rem; border: 1px solid var(--bs-border-color); background: var(--bs-body-bg); color: var(--bs-body-color); cursor: pointer; font-size: .88rem; text-align: left; transition: background .15s; }
		.btn-block:hover { background: var(--bs-secondary-bg); }
		.hidden { display: none !important; }
		.tab-section { display: none; }
		.tab-section.active { display: block; }

		/* ── Search filter row ── */
		.filter-card { background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: .75rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
		.filter-card label { font-size: .78rem; font-weight: 600; color: var(--bs-secondary-color); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .3rem; }
	</style>
</head>

<body>
<main>
	<?php include 'sidebar.php'; ?>
	<div class="page-content">
		<?php include 'topbar.php'; ?>
		<div class="page-content-wrapper p-xxl-4">

			<!-- ── PAGE HEADER ── -->
			<div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
				<div>
					<div class="d-flex align-items-center gap-2 mb-1">
						<i class="bi bi-briefcase-fill fs-4 text-primary"></i>
						<h1 class="page-title mb-0">Gestion des Jobs Saisonniers</h1>
					</div>
					<p class="page-subtitle mb-0">Administration des offres d'emploi et candidatures</p>
				</div>
				<div class="btn-action-group d-flex flex-wrap gap-2">
					<button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV('jobs-table','jobs.csv')">
						<i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
					</button>
					<button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
						<i class="bi bi-printer me-1"></i>Imprimer
					</button>
					<button class="btn btn-outline-primary btn-sm" onclick="showTab('candidatures')">
						<i class="bi bi-people me-1"></i>Candidatures
					</button>
					<button class="btn btn-primary btn-sm" onclick="showTab('ajouter-job')">
						<i class="bi bi-plus-lg me-1"></i>Nouveau job
					</button>
				</div>
			</div>

			<!-- ── STAT CARDS ── -->
			<div class="row g-3 mb-4">
				<div class="col-6 col-xl-3">
					<div class="card stat-border-card blue h-100">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="stat-num" id="stat-total-jobs">—</div>
								<div class="text-muted mt-1" style="font-size:.85rem;">Total jobs</div>
							</div>
							<div class="stat-icon-wrap"><i class="bi bi-briefcase-fill"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-xl-3">
					<div class="card stat-border-card yellow h-100">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="stat-num" id="stat-cands-wait">—</div>
								<div class="text-muted mt-1" style="font-size:.85rem;">En attente</div>
							</div>
							<div class="stat-icon-wrap"><i class="bi bi-hourglass-split"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-xl-3">
					<div class="card stat-border-card green h-100">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="stat-num" id="stat-cands-ok">—</div>
								<div class="text-muted mt-1" style="font-size:.85rem;">Approuvées</div>
							</div>
							<div class="stat-icon-wrap"><i class="bi bi-check-circle-fill"></i></div>
						</div>
					</div>
				</div>
				<div class="col-6 col-xl-3">
					<div class="card stat-border-card red h-100">
						<div class="d-flex justify-content-between align-items-center">
							<div>
								<div class="stat-num" id="stat-cands-ko">—</div>
								<div class="text-muted mt-1" style="font-size:.85rem;">Rejetées</div>
							</div>
							<div class="stat-icon-wrap"><i class="bi bi-x-circle-fill"></i></div>
						</div>
					</div>
				</div>
			</div>

			<!-- ── TAB NAV ── -->
			<div class="card border p-3 pb-2 mb-4" style="border-radius:.75rem;">
				<nav class="dash-tabs">
					<ul class="nav flex-wrap gap-1">
						<li class="nav-item"><a class="nav-link active" href="#" onclick="showTab('jobs'); return false;"><i class="bi bi-briefcase"></i>Jobs</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('candidatures'); return false;"><i class="bi bi-people"></i>Candidatures</a></li>
						<li class="nav-item"><a class="nav-link" href="#" onclick="showTab('ajouter-job'); return false;"><i class="bi bi-plus-circle"></i>Ajouter un job</a></li>
					</ul>
				</nav>
			</div>

			<!-- ════ TAB: JOBS ════ -->
			<div id="tab-jobs" class="tab-section active">
				<!-- Filter row -->
				<div class="filter-card">
					<div class="row g-3 align-items-end">
						<div class="col-md-5">
							<label>Rechercher</label>
							<div class="input-group">
								<span class="input-group-text bg-transparent"><i class="bi bi-search text-muted"></i></span>
								<input type="text" class="form-control" placeholder="Titre, lieu, référence…"
									id="job-search-input" oninput="filterTable('jobs-table','job-search-input')">
							</div>
						</div>
						<div class="col-md-3">
							<label>Statut</label>
							<select class="form-select" id="filter-statut" onchange="filterTable('jobs-table','job-search-input')">
								<option value="">Tous les statuts</option>
								<option value="publie">Publié</option>
								<option value="brouillon">Brouillon</option>
								<option value="ferme">Fermé</option>
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
								<i class="bi bi-x-circle me-1"></i>Réinitialiser
							</button>
						</div>
					</div>
					<div class="mt-2 text-muted small" id="jobs-count"></div>
				</div>

				<!-- Table -->
				<div class="card border" style="border-radius:.75rem;">
					<div class="card-header border-bottom d-flex justify-content-between align-items-center py-3 px-4">
						<h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Toutes les offres</h6>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-hover align-middle admin-table mb-0" id="jobs-table">
								<thead class="table-light">
									<tr>
										<th>#</th><th>Référence</th><th>Titre</th><th>Contrat</th>
										<th>Lieu</th><th>Statut</th><th>Deadline</th><th>Actions</th>
									</tr>
								</thead>
								<tbody id="jobs-tbody">
									<tr><td colspan="8" class="text-center text-muted py-4">Chargement…</td></tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- ════ TAB: CANDIDATURES ════ -->
			<div id="tab-candidatures" class="tab-section">
				<div class="filter-card">
					<div class="row g-3 align-items-end">
						<div class="col-md-6">
							<label>Rechercher</label>
							<div class="input-group">
								<span class="input-group-text bg-transparent"><i class="bi bi-search text-muted"></i></span>
								<input type="text" class="form-control" placeholder="Nom, email, job…"
									id="cand-search-input" oninput="filterTable('cands-table','cand-search-input')">
							</div>
						</div>
						<div class="col-md-2">
							<button class="btn btn-outline-secondary w-100" onclick="document.getElementById('cand-search-input').value=''; filterTable('cands-table','cand-search-input')">
								<i class="bi bi-x-circle me-1"></i>Réinitialiser
							</button>
						</div>
						<div class="col-md-2 ms-auto text-end">
							<button class="btn btn-outline-secondary btn-sm" onclick="exportToCSV('cands-table','candidatures.csv')">
								<i class="bi bi-download me-1"></i>Export CSV
							</button>
						</div>
					</div>
				</div>

				<div class="card border" style="border-radius:.75rem;">
					<div class="card-header border-bottom d-flex justify-content-between align-items-center py-3 px-4">
						<h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Toutes les candidatures</h6>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-hover align-middle admin-table mb-0" id="cands-table">
								<thead class="table-light">
									<tr><th>#</th><th>Référence</th><th>Nom</th><th>Message</th><th>Job</th><th>Date</th><th>Statut</th><th>Actions</th></tr>
								</thead>
								<tbody>
									<?php
										ob_start();
										require_once(__DIR__ . "/../../CONTROLLER/Candidature.php");
										ob_end_clean(); // discard any output from the router
										$candidature = new CandidatureC();
										$candidatures = $candidature->getAll();	
										if (empty($candidatures)) {
											echo '<tr><td colspan="8" class="text-center text-muted py-4">Aucune candidature trouvée.</td></tr>';
										} else {
											foreach ($candidatures as $i => $c) {
												echo '<tr data-cand-id="' . htmlspecialchars($c['id']) . '">
													<td class="text-muted">#' . ($i+1) . '</td>
													<td><code>' . htmlspecialchars($c['reference']) . '</code></td>
													<td>
														<div class="d-flex align-items-center gap-2">
															<div style="width:32px;height:32px;border-radius:50%;background:var(--bs-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0;">'
																. strtoupper(substr($c['nom'] ?? 'N', 0, 1)) .
															'</div>
															<span>' . htmlspecialchars($c['nom']) . '</span>
														</div>
													</td>
													<td>
														<div style="font-size:.82rem;">
    <div class="msg-short" style="max-width:180px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;white-space:normal;">
        ' . htmlspecialchars($c['message'] ?? '—') . '
    </div>
    <div class="msg-full" style="display:none;font-size:.82rem;max-width:250px;white-space:normal;">
        ' . htmlspecialchars($c['message'] ?? '—') . '
    </div>
    <a href="#" class="small text-primary" style="font-size:.75rem;" 
       onclick="toggleMsg(this); return false;">Voir plus</a>
</div>
														<button class="btn btn-sm btn-outline-primary mt-1" style="font-size:.72rem;padding:2px 8px"
															onclick="translateMsg(this, \'' . addslashes(htmlspecialchars($c['message'] ?? '')) . '\')">
															<i class="bi bi-translate me-1"></i>Traduire
														</button>
														<div class="translate-result text-muted mt-1" style="font-size:.78rem;display:none"></div>
													</td>
													<td>' . htmlspecialchars($c['titre'] ?? 'N/A') . '</td>
													<td>' . htmlspecialchars($c['date_candidature'] ?? '—') . '</td>
													<td><span class="pill pill-warn">En attente</span></td>
													<td>
														<div class="d-flex gap-1">
															<button class="btn btn-sm btn-outline-success" title="Approuver" onclick="setStatus(this,\'pill-ok\',\'Approuvé\')">
																<i class="bi bi-check-lg"></i>
															</button>
															<button class="btn btn-sm btn-outline-danger" title="Refuser" onclick="setStatus(this,\'pill-ko\',\'Refusé\')">
																<i class="bi bi-x-lg"></i>
															</button>
															<form action="../../VIEW/FrontOffice/SupprimerCondidature.php" method="post" style="display:inline">
																<input type="hidden" name="id" value="' . htmlspecialchars($c['id']) . '" />
																<button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"
																	onclick="return confirm(\'Supprimer cette candidature ?\')">
																	<i class="bi bi-trash"></i>
																</button>
															</form>
														</div>
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
				<h5 class="fw-bold mb-4"><i class="bi bi-plus-circle me-2 text-primary"></i><span id="job-form-title">Ajouter un job</span></h5>
				<div class="card border" style="border-radius:.75rem;">
					<div class="card-body p-4">
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
									<label class="form-label">Date limite</label>
									<input type="date" class="form-control" id="job-deadline">
								</div>
							</div>
						</div>
						<div class="d-flex gap-2 flex-wrap">
							<button class="btn btn-primary" id="job-save-btn" onclick="saveJob()"><i class="bi bi-save me-1"></i>Enregistrer</button>
							<button class="btn btn-outline-secondary hidden" id="job-update-btn" onclick="saveJob()"><i class="bi bi-pencil me-1"></i>Mettre à jour</button>
							<button class="btn btn-light hidden" id="job-cancel-edit-btn" onclick="resetJobForm()">Annuler</button>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /page-content-wrapper -->
	</div><!-- /page-content -->
</main>

<?php include 'footer.php'; ?>

<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../assets/js/functions.js"></script>

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
		const statusFilter = document.getElementById('filter-statut')?.value.toLowerCase() || '';
		let visible = 0;
		document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
			const text = row.textContent.toLowerCase();
			const show = text.includes(q) && (statusFilter === '' || text.includes(statusFilter));
			row.style.display = show ? '' : 'none';
			if (show) visible++;
		});
		const countEl = document.getElementById('jobs-count');
		if (countEl) countEl.textContent = visible + ' résultat(s)';
	}

	function resetFilters() {
		document.getElementById('job-search-input').value = '';
		if (document.getElementById('filter-statut')) document.getElementById('filter-statut').value = '';
		filterTable('jobs-table', 'job-search-input');
	}

	/* ── CANDIDATURE STATUS ── */
	function setStatus(btn, pillClass, label) {
		const pill = btn.closest('tr').querySelector('.pill');
		pill.className = 'pill ' + pillClass;
		pill.textContent = label;
		updateStats();
	}

	/* ── UPDATE STATS ── */
	function updateStats() {
		const rows = document.querySelectorAll('#cands-table tbody tr');
		let wait = 0, ok = 0, ko = 0;
		rows.forEach(r => {
			const pill = r.querySelector('.pill');
			if (!pill) return;
			if (pill.classList.contains('pill-warn')) wait++;
			if (pill.classList.contains('pill-ok'))   ok++;
			if (pill.classList.contains('pill-ko'))   ko++;
		});
		document.getElementById('stat-cands-wait').textContent = wait;
		document.getElementById('stat-cands-ok').textContent   = ok;
		document.getElementById('stat-cands-ko').textContent   = ko;
	}

	/* ── SAVE JOB ── */
	let editingJobId = null;

	function saveJob() {
		const titre = document.getElementById('job-titre').value.trim();
		const description = document.getElementById('job-description').value.trim();
		if (!titre || !description) { alert('Veuillez remplir les champs obligatoires.'); return; }

		const fd = new FormData();
		fd.append('titre', titre);
		fd.append('reference', document.getElementById('job-ref').value.trim());
		fd.append('lieu', document.getElementById('job-lieu').value.trim());
		fd.append('type', document.getElementById('job-contrat').value);
		fd.append('description', description);
		fd.append('date_limite', document.getElementById('job-deadline').value);
		fd.append('statut', document.getElementById('job-statut').value);
		fd.append('action', editingJobId ? 'modifier' : 'ajouter');
		if (editingJobId) fd.append('id', editingJobId);

		fetch('../../CONTROLLER/Job.php', { method: 'POST', body: fd })
			.then(r => r.text())
			.then(text => {
				editingJobId = null;
				resetJobForm();
				loadJobs();
				// Success modal
				const modal = document.createElement('div');
				modal.innerHTML = `
				<div class="modal fade" id="job-modal" tabindex="-1">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body text-center py-4">
								<div style="width:60px;height:60px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem">
									<i class="bi bi-check-lg text-success fs-3"></i>
								</div>
								<h5 class="mb-2">Job enregistré !</h5>
								<p class="text-muted mb-0">${text}</p>
							</div>
							<div class="modal-footer border-0 justify-content-center pt-0">
								<button class="btn btn-primary" data-bs-dismiss="modal" onclick="showTab('jobs')">Voir les jobs</button>
							</div>
						</div>
					</div>
				</div>`;
				document.body.appendChild(modal);
				const bsModal = new bootstrap.Modal(document.getElementById('job-modal'));
				bsModal.show();
				document.getElementById('job-modal').addEventListener('hidden.bs.modal', () => modal.remove());
			})
			.catch(() => alert('Erreur de connexion au serveur.'));
	}

	function resetJobForm() {
		['job-titre','job-ref','job-lieu','job-description','job-deadline'].forEach(id => document.getElementById(id).value = '');
		document.getElementById('job-contrat').value = '';
		document.getElementById('job-statut').value = 'brouillon';
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
		fetch('../../CONTROLLER/Job.php', { method: 'POST', body: fd })
			.then(r => r.json())
			.then(jobs => {
				const tbody = document.getElementById('jobs-tbody');
				if (!jobs.length) {
					tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">Aucun job trouvé.</td></tr>';
					document.getElementById('stat-total-jobs').textContent = 0;
					return;
				}
				document.getElementById('stat-total-jobs').textContent = jobs.length;
				tbody.innerHTML = jobs.map((job, i) => `
					<tr>
					<td class="text-muted">#${i+1}</td>
					<td><code>${job.reference ?? '—'}</code></td>
					<td><strong>${job.titre}</strong></td>
					<td>${job.type ?? '—'}</td>
					<td><i class="bi bi-geo-alt me-1 text-muted"></i>${job.lieu ?? '—'}</td>
					<td><span class="pill ${job.statut === 'publie' ? 'pill-ok' : job.statut === 'ferme' ? 'pill-ko' : 'pill-warn'}">${job.statut ?? '—'}</span></td>
					<td>${job.date_limite ?? '—'}</td>
					<td>
						<div class="d-flex gap-1">
							<button class="btn btn-sm btn-outline-primary" title="Modifier" onclick="editJob(${job.id}, ${JSON.stringify(job).replace(/"/g, '&quot;')})">
								<i class="bi bi-pencil"></i>
							</button>
							<button class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="deleteJob(${job.id}, this)">
								<i class="bi bi-trash"></i>
							</button>
						</div>
					</td>
					</tr>
				`).join('');
				// update count
				document.getElementById('jobs-count').textContent = jobs.length + ' résultat(s)';
			})
			.catch(() => {
				document.getElementById('jobs-tbody').innerHTML =
					'<tr><td colspan="8" class="text-center text-danger py-4">Erreur de chargement.</td></tr>';
			});
	}

	function deleteJob(id, btn) {
		if (!confirm('Supprimer ce job ?')) return;
		const fd = new FormData();
		fd.append('action', 'supprimer');
		fd.append('id', id);
		fetch('../../CONTROLLER/Job.php', { method: 'POST', body: fd })
			.then(() => { btn.closest('tr').remove(); loadJobs(); })
			.catch(() => alert('Erreur de suppression.'));
	}

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

	/* ── TRANSLATE ── */
	async function translateMsg(btn, text) {
		if (!text || text === '—') { alert('Aucun message à traduire.'); return; }
		const resultDiv = btn.nextElementSibling;
		btn.disabled = true;
		btn.innerHTML = '<div class="spinner-border spinner-border-sm"></div>';
		const prompt = `Détecte la langue de ce texte et traduis-le en français. 
		Réponds UNIQUEMENT avec ce format JSON :
		{"langue": "nom de la langue détectée", "traduction": "texte traduit en français"}
		Texte : "${text}"`;
		try {
			const res = await fetch('https://api.groq.com/openai/v1/chat/completions', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer YOUR_GROQ_API_KEY_HERE' },
				body: JSON.stringify({ model: 'llama-3.3-70b-versatile', messages: [{ role: 'user', content: prompt }], temperature: 0.1, max_tokens: 300 })
			});
			const data = await res.json();
			let content = data.choices?.[0]?.message?.content?.trim() || '';
			content = content.replace(/^```(?:json)?\s*/i, '').replace(/\s*```$/, '');
			const result = JSON.parse(content);
			resultDiv.innerHTML = `<span class="badge bg-secondary bg-opacity-10 text-secondary me-1">${result.langue}</span>${result.traduction}`;
			resultDiv.style.display = 'block';
			btn.innerHTML = '<i class="bi bi-translate me-1"></i>Retraduit';
		} catch (err) {
			resultDiv.textContent = 'Erreur traduction.';
			resultDiv.style.display = 'block';
			btn.innerHTML = '<i class="bi bi-translate me-1"></i>Traduire';
		} finally {
			btn.disabled = false;
		}
	}

	// Init
	loadJobs();
	updateStats();
	function toggleMsg(link) {
    const wrapper = link.parentElement;
    const short = wrapper.querySelector('.msg-short');
    const full  = wrapper.querySelector('.msg-full');
    if (full.style.display === 'none') {
        short.style.display = 'none';
        full.style.display  = 'block';
        link.textContent    = 'Voir moins';
    } else {
        short.style.display = '-webkit-box';
        full.style.display  = 'none';
        link.textContent    = 'Voir plus';
    }
}
</script>
</body>
</html>
