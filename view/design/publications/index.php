<?php
// view/design/publications/index.php - Professional Edossier Portal
include __DIR__ . '/../../layouts/header.php';
?>

<main>

	<!-- =======================
	Main Banner START -->
	<section class="p-0">
		<div class="container">
			<div class="row g-4 g-lg-5 align-items-center py-5">
				<!-- Content -->
				<div class="col-lg-8 mx-auto text-center">
					<!-- Title -->
					<h1 class="mb-4 display-5 fw-bold">Explore the Official <span
							class="text-primary position-relative">Edossier
							<!-- SVG START -->
							<span
								class="position-absolute top-50 start-50 translate-middle z-index-n1 d-none d-md-block mt-4">
								<svg width="390.5px" height="21.5px" viewBox="0 0 445.5 21.5">
									<path class="fill-primary opacity-2"
										d="M409.9,2.6c-9.7-0.6-19.5-1-29.2-1.5c-3.2-0.2-6.4-0.2-9.7-0.3c-7-0.2-14-0.4-20.9-0.5 c-3.9-0.1-7.8-0.2-11.7-0.3c-1.1,0-2.3,0-3.4,0c-2.5,0-5.1,0-7.6,0c-11.5,0-23,0-34.5,0c-2.7,0-5.5,0.1-8.2,0.1 c-6.8,0.1-13.6,0.2-20.3,0.3c-7.7,0.1-15.3,0.1-23,0.3c-12.4,0.3-24.8,0.6-37.1,0.9c-7.2,0.2-14.3,0.3-21.5,0.6 c-12.3,0.5-24.7,1-37,1.5c-6.7,0.3-13.5,0.5-20.2,0.9C95,5.2,85.8,5.8,76.6,6.4c-0.7,0-1.3,0.1-2,0.2c-4.7,0.3-9.4,0.6-14.2,1 c-6.1,0.5-12.2,1-18.3,1.5c-1.5,0.1-3,0.2-4.5,0.3c-15.3,1.3-30.6,2.8-45.9,4.4c-2.8,0.3-5.6,0.6-8.3,1c-0.7,0.1-1.4,0.1-2.1,0.2 c-1.5,0.2-3,0.3-4.5,0.5c-0.4,0.4-0.7,0.9-1.1,1.4c0.5,0.5,1,1.1,1.4,1.5c11.5,5.2,20.1,4.4,30.4,4.1c3.5-0.1,7-0.2,10.6-0.3 c11.8-0.4,23.5-0.6,35.3-1c9.4-0.3,18.7-0.4,28.1-0.7c7.1-0.2,14.2-0.3,21.4-0.4c6.3-0.1,12.7-0.2,19-0.3 c30.2-0.5,60.5-0.6,90.8-0.5c13.9,0.1,27.7,0.3,41.6,0.4c12.3,0.1,24.6,0.1,36.9,0.2c7.5,0,15,0,22.5,0c13.9-0.1,27.7-0.1,41.6-0.2 c7.6,0.1,15.1,0.3,22.7,0.6c0.8,0,1.7,0.1,2.5,0.1c3.6,0.2,7.1,0.4,10.7,0.6c7.6,0.3,15.3,0.6,22.9,1c6.3,0.3,12.7,0.6,19,1 c2.2,0.1,4.4,0.2,6.6,0.3c0.9,0.1,1.7,0.3,2.6,0.3c0.6-0.5,1.2-1,1.7-1.5c0.1-0.5,0.2-1,0.3-1.5c-0.1-0.5-0.2-1-0.3-1.5 c-0.5-0.5-1-1-1.5-1.4C432,3.9,421,3.3,409.9,2.6z">
									</path>
								</svg>
							</span>
							<!-- SVG END -->
						</span> repository
					</h1>
					<!-- Info -->
					<p class="mb-5 lead">Access transparent government documents, legal frameworks, and official
						announcements. Your portal to informed citizenship and active engagement.</p>

					<!-- Buttons -->
					<div class="hstack gap-3 justify-content-center flex-wrap align-items-center mb-5">
						<a href="#doc-list" class="btn btn-primary mb-0"><i
								class="bi bi-file-earmark-text me-2"></i>Browse Documents</a>
						<a href="/projetweb/view/back-office/index.php" class="btn btn-outline-dark mb-0"><i
								class="bi bi-person-gear me-2"></i>Administrator Console</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- =======================
	Main Banner END -->

	<!-- =======================
	Search START -->
	<section class="py-5" id="doc-list"
		style="background: linear-gradient(135deg, rgba(6, 106, 201, 0.05), rgba(12, 188, 135, 0.05)); border-top: 1px solid rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05);">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-11">
					<div class="text-center mb-4">
						<h3 class="fw-bold">Explore Posts</h3>
						<p class="text-secondary">Search by name or type to find what you need.</p>
					</div>
					<div class="bg-mode rounded-4 shadow-lg p-3 border">
						<form class="row g-3 align-items-center" method="GET" action="/projetweb/index1.php">
							<input type="hidden" name="action" value="frontIndex">
							<!-- Search -->
							<div class="col-lg-4">
								<div class="input-group input-group-lg">
									<span class="input-group-text bg-light border-0 rounded-start-pill text-muted"><i
											class="bi bi-search"></i></span>
									<input type="text" name="search" class="form-control border-0 bg-light shadow-none"
										placeholder="Search keyword..." value="<?= htmlspecialchars($search ?? '') ?>">
								</div>
							</div>

							<!-- Category -->
							<div class="col-lg-3">
								<div class="input-group input-group-lg">
									<span class="input-group-text bg-light border-0 text-muted"><i
											class="bi bi-folder2-open"></i></span>
									<select name="category" class="form-select border-0 bg-light shadow-none">
										<option value="">All Categories</option>
										<option value="Law" <?= ($category ?? '') == 'Law' ? 'selected' : '' ?>>Law</option>
										<option value="Announcement" <?= ($category ?? '') == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
										<option value="Report" <?= ($category ?? '') == 'Report' ? 'selected' : '' ?>>Report</option>
										<option value="General" <?= ($category ?? '') == 'General' ? 'selected' : '' ?>>General</option>
									</select>
								</div>
							</div>

							<!-- Sort -->
							<div class="col-lg-3">
								<div class="input-group input-group-lg">
									<span class="input-group-text bg-light border-0 text-muted"><i
											class="bi bi-sort-alpha-down"></i></span>
									<select name="sort"
										class="form-select border-0 bg-light shadow-none rounded-end-pill me-lg-3">
										<option value="date_desc" <?= ($sort ?? '') == 'date_desc' ? 'selected' : '' ?>>
											Newest</option>
										<option value="date_asc" <?= ($sort ?? '') == 'date_asc' ? 'selected' : '' ?>>
											Oldest</option>
										<option value="title_asc" <?= ($sort ?? '') == 'title_asc' ? 'selected' : '' ?>>A
											to Z</option>
									</select>
								</div>
							</div>

							<!-- Action -->
							<div class="col-lg-2 d-grid">
								<button type="submit"
									class="btn btn-primary btn-lg rounded-pill mb-0 shadow">Search</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- =======================
	Search END -->

	<!-- =======================
	Document List START -->
	<section class="pt-0 pt-md-5">
		<div class="container">
			<!-- Title -->
			<div class="row mb-5 align-items-center">
				<div class="col-md-8">
					<h2 class="mb-0"><?= isset($isSavedView) ? 'Saved Documents' : 'Official Repository' ?></h2>
					<p class="mb-0">
						<?= isset($isSavedView) ? 'Your curated list of important government publications.' : 'Showing latest verified publications from government sources.' ?>
					</p>
				</div>
				<div class="col-md-4 text-md-end mt-3 mt-md-0">
					<span class="badge bg-primary-soft text-primary fs-6"><?= count($list ?? []) ?> Documents
						Found</span>
				</div>
			</div>

			<div class="row g-4">
				<?php if (!empty($list)): ?>
					<?php foreach ($list as $p): ?>
						<!-- Document Item - List View -->
						<div class="col-12">
							<div class="card shadow p-3 pb-0 h-100">
								<!-- Card body START -->
								<div class="card-body px-3 pb-0 mt-2">
									<!-- Rating and bookmark -->
									<div class="d-flex justify-content-between mb-3">
										<a href="#" class="badge bg-primary text-white"><i
												class="bi bi-patch-check-fill me-2 text-warning"></i>Official</a>
										<?php
										$isBookmarked = isset($_SESSION['saved_publications']) && in_array($p['id'], $_SESSION['saved_publications']);
										?>
										<a href="javascript:void(0);" onclick="toggleBookmark(this, <?= $p['id'] ?>)"
											class="h6 mb-0 z-index-2 <?= $isBookmarked ? 'text-primary' : 'text-secondary' ?> hover-primary"
											title="Save to favorites">
											<i
												class="bi fa-fw <?= $isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark' ?> fs-5"></i>
										</a>
									</div>

									<!-- Title -->
									<h4 class="card-title line-clamp-2"><a
											href="/projetweb/index1.php?action=show&id=<?= $p['id'] ?>"><?= htmlspecialchars($p['titre']) ?></a>
									</h4>

									<!-- Metadata -->
									<ul class="nav nav-divider mb-2 mb-sm-4 text-muted">
										<li class="nav-item"><i
												class="bi bi-person me-1"></i><?= htmlspecialchars($p['auteur']) ?></li>
										<li class="nav-item"><i class="bi bi-calendar3 me-1"></i><?= date('F j, Y', strtotime($p['date'])) ?></li>
										<li class="nav-item text-primary"><i class="bi bi-chat-left-dots me-1"></i><?= $p['comment_count'] ?> Comments</li>
									</ul>
								</div>
								<!-- Card body END -->

								<!-- Card footer START-->
								<div class="card-footer pt-0 border-0">
									<hr>
									<!-- Category and Button -->
									<div class="d-sm-flex justify-content-sm-between align-items-center">
										<!-- Category -->
										<div class="d-flex align-items-center">
											<?php
											$catName = htmlspecialchars($p['categorie']);
											$catLower = strtolower(trim($catName));
											$bgClass = 'primary';
											if (strpos($catLower, 'law') !== false)
												$bgClass = 'danger';
											elseif (strpos($catLower, 'general') !== false)
												$bgClass = 'success';
											elseif (strpos($catLower, 'announcement') !== false)
												$bgClass = 'warning text-dark';
											elseif (strpos($catLower, 'report') !== false)
												$bgClass = 'info text-dark';
											else {
												$palette = ['secondary', 'dark', 'primary', 'success'];
												$bgClass = $palette[abs(crc32($catLower)) % count($palette)];
											}
											?>
											<span class="badge bg-<?= $bgClass ?> fw-bold fs-6 py-2 px-3 shadow-sm"><i
													class="fa-solid fa-tag me-2"></i><?= $catName ?></span>
										</div>
										<!-- Button -->
										<div class="mt-2 mt-sm-0 z-index-2">
											<a href="/projetweb/index1.php?action=show&id=<?= $p['id'] ?>"
												class="btn btn-primary mb-0">Read Official Document<i
													class="bi bi-arrow-right ms-2"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="col-12 text-center py-5">
						<div class="icon-xl bg-light text-primary rounded-circle mx-auto mb-4"><i class="bi bi-search"></i>
						</div>
						<h3>No Documents Found</h3>
						<p>We couldn't find any documents matching your criteria. Try adjusting your search or filters.</p>
						<a href="/projetweb/index1.php" class="btn btn-primary-soft">Reset All Filters</a>
					</div>
				<?php endif; ?>
			</div>

			<!-- Pagination -->
			<?php if (!empty($list)): ?>
				<div class="row mt-5">
					<div class="col-12">
						<nav class="d-flex justify-content-center" aria-label="navigation">
							<ul class="pagination pagination-primary-soft d-inline-block d-md-flex rounded mb-0">
								<li class="page-item mb-0"><a class="page-link" href="#" tabindex="-1"><i
											class="fas fa-angle-double-left"></i></a></li>
								<li class="page-item mb-0 active"><a class="page-link" href="#">1</a></li>
								<li class="page-item mb-0"><a class="page-link" href="#">2</a></li>
								<li class="page-item mb-0"><a class="page-link" href="#"><i
											class="fas fa-angle-double-right"></i></a></li>
							</ul>
						</nav>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<!-- =======================
	Document List END -->

</main>

<script>
	// Toggle bookmark icon
	function toggleBookmark(element, id) {
		const icon = element.querySelector('i');

		// Immediate UI feedback
		if (icon.classList.contains('bi-bookmark')) {
			icon.classList.remove('bi-bookmark', 'text-secondary');
			icon.classList.add('bi-bookmark-fill', 'text-primary');
			element.classList.remove('text-secondary');
			element.classList.add('text-primary');
		} else {
			icon.classList.remove('bi-bookmark-fill', 'text-primary');
			icon.classList.add('bi-bookmark', 'text-secondary');
			element.classList.remove('text-primary');
			element.classList.add('text-secondary');
		}

		// Server-side persistence
		fetch(`/projetweb/index1.php?action=toggleSave&id=${id}`)
			.then(response => response.json())
			.then(data => {
				console.log('Save status:', data.status);
				if (data.status === 'added') {
					Swal.fire({
						toast: true,
						position: 'top-end',
						icon: 'success',
						title: 'Saved to your favorites',
						showConfirmButton: false,
						timer: 2000
					});
				}
			})
			.catch(error => {
				console.error('Error toggling save:', error);
				// Revert on error?
			});
	}

	// Remove search params from URL so refreshing the page does not re-trigger the search
	if (window.history.replaceState) {
		const url = new URL(window.location.href);
		if (url.searchParams.has('search') || url.searchParams.has('category') || url.searchParams.has('sort')) {
			const action = url.searchParams.get('action');
			const newUrl = new URL(window.location.pathname, window.location.origin);
			if (action) {
				newUrl.searchParams.set('action', action);
			}
			window.history.replaceState({}, document.title, newUrl.toString());
		}
	}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>