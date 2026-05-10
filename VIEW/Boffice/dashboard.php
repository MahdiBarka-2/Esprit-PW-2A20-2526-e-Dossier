<?php
// view/publication/dashboard.php
require_once "header.php";

// Data for Analytics
$categories = array_keys($stats['category_distribution'] ?? []);
$counts = array_values($stats['category_distribution'] ?? []);
?>

<!-- Page main content START -->
<div class="page-content-wrapper p-xxl-4">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-4 mb-sm-5">
            <div class="d-sm-flex justify-content-between align-items-center">
                <?php $viewAction = $_GET['action'] ?? 'index'; ?>
                <h1 class="h3 mb-2 mb-sm-0">
                    <?= ($viewAction === 'dashboard') ? 'Dashboard Analytics' : 'Posts List' ?></h1>
                <div class="d-flex gap-2">
                    <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/index1.php" class="btn btn-info-soft mb-0"><i
                            class="bi bi-eye fa-fw"></i> Posts</a>
                    <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=create" class="btn btn-primary-soft mb-0"><i
                            class="bi bi-plus-lg fa-fw"></i> Add Post</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Counter boxes START -->
    <div class="row g-4 mb-5">
        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body bg-warning bg-opacity-10 border border-warning border-opacity-25 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['total_publications'] ?></h4>
                        <span class="h6 fw-light mb-0">Total Posts</span>
                    </div>
                    <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i
                            class="fa-solid fa-book fa-fw"></i></div>
                </div>
            </div>
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body bg-success bg-opacity-10 border border-success border-opacity-25 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['total_comments'] ?></h4>
                        <span class="h6 fw-light mb-0">Citizen Feedback</span>
                    </div>
                    <div class="icon-lg rounded-circle bg-success text-white mb-0"><i
                            class="fa-solid fa-comments fa-fw"></i></div>
                </div>
            </div>
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body bg-primary bg-opacity-10 border border-primary border-opacity-25 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['avg_engagement'] ?></h4>
                        <span class="h6 fw-light mb-0">Engagement Rate</span>
                    </div>
                    <div class="icon-lg rounded-circle bg-primary text-white mb-0"><i
                            class="fa-solid fa-chart-line fa-fw"></i></div>
                </div>
            </div>
        </div>

        <!-- Counter item -->
        <div class="col-md-6 col-xxl-3">
            <div class="card card-body bg-info bg-opacity-10 border border-info border-opacity-25 p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><?= $stats['unique_categories'] ?></h4>
                        <span class="h6 fw-light mb-0">Categories</span>
                    </div>
                    <div class="icon-lg rounded-circle bg-info text-white mb-0"><i
                            class="fa-solid fa-layer-group fa-fw"></i></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Counter boxes END -->

    <?php if ($viewAction === 'dashboard'): ?>
        <!-- Trending Insight Metric (Business Logic) -->
        <?php if ($stats['trending']): ?>
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow p-4 overflow-hidden"
                        style="background: linear-gradient(to right, rgba(var(--bs-primary-rgb), 0.05), transparent);">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill"><i
                                        class="fa-solid fa-fire me-2"></i>TRENDING CONTENT</span>
                                <h3 class="fw-bold mb-3"><?= htmlspecialchars($stats['trending']['titre']) ?></h3>
                                <p class="text-body-secondary mb-0">Most popular post with <strong
                                        class="text-body-emphasis"><?= $stats['trending']['comment_count'] ?> comments</strong>.
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                                <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=show&id=<?= $stats['trending']['id'] ?>"
                                    class="btn btn-primary btn-lg rounded-pill px-5">View Post</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
    </div>

    <!-- Premium Sentiment Analysis Overview -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-radar me-2 text-primary"></i>User Feedback</h5>
                        <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-3 py-2">LIVE
                            ANALYSIS</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Positive Metric -->
                        <div class="col-md-4 p-5 border-end border-light">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-lg rounded-circle bg-success bg-opacity-10 text-success me-3"><i
                                        class="bi bi-emoji-smile fa-fw"></i></div>
                                <div>
                                    <h6 class="mb-0 text-muted text-uppercase fw-bold" style="letter-spacing: 1px;">Positive
                                        Rating</h6>
                                    <h2 class="mb-0 fw-bold text-success"><?= $stats['sentiment']['positive'] ?? 0 ?></h2>
                                </div>
                            </div>
                            <?php $posPct = $stats['total_comments'] ? round((($stats['sentiment']['positive'] ?? 0) / $stats['total_comments']) * 100) : 0; ?>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $posPct ?>%"
                                    aria-valuenow="<?= $posPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted fw-bold"><?= $posPct ?>% <span class="fw-normal">of total
                                    feedback</span></small>
                        </div>

                        <!-- Action Required Metric -->
                        <div class="col-md-4 p-5 border-end border-light">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-lg rounded-circle bg-danger bg-opacity-10 text-danger me-3"><i
                                        class="bi bi-exclamation-octagon fa-fw"></i></div>
                                <div>
                                    <h6 class="mb-0 text-muted text-uppercase fw-bold" style="letter-spacing: 1px;">Critical
                                        Review</h6>
                                    <h2 class="mb-0 fw-bold text-danger"><?= $stats['sentiment']['critical'] ?? 0 ?></h2>
                                </div>
                            </div>
                            <?php $critPct = $stats['total_comments'] ? round((($stats['sentiment']['critical'] ?? 0) / $stats['total_comments']) * 100) : 0; ?>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: <?= $critPct ?>%"
                                    aria-valuenow="<?= $critPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted fw-bold"><?= $critPct ?>% <span class="fw-normal">of total
                                    feedback</span></small>
                        </div>

                        <!-- Neutral Metric -->
                        <div class="col-md-4 p-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-lg rounded-circle bg-secondary bg-opacity-10 text-secondary me-3"><i
                                        class="bi bi-chat-square-text fa-fw"></i></div>
                                <div>
                                    <h6 class="mb-0 text-muted text-uppercase fw-bold" style="letter-spacing: 1px;">Neutral
                                        Query</h6>
                                    <h2 class="mb-0 fw-bold text-secondary"><?= $stats['sentiment']['neutral'] ?? 0 ?></h2>
                                </div>
                            </div>
                            <?php $neuPct = $stats['total_comments'] ? round((($stats['sentiment']['neutral'] ?? 0) / $stats['total_comments']) * 100) : 0; ?>
                            <div class="progress mb-2" style="height: 6px;">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: <?= $neuPct ?>%"
                                    aria-valuenow="<?= $neuPct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-muted fw-bold"><?= $neuPct ?>% <span class="fw-normal">of total
                                    feedback</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($viewAction === 'index'): ?>
    <!-- Search and Sort Filters START -->
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php" class="card shadow border-0 p-3">
                <input type="hidden" name="action" value="index">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fa-solid fa-search"></i></span>
                            <input type="search" name="search" class="form-control bg-light border-0"
                                placeholder="Search by title, author, category..."
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select name="sort" class="form-select bg-light border-0">
                            <option value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Date
                                (Newest First)</option>
                            <option value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Date (Oldest
                                First)</option>
                            <option value="titre_asc" <?= ($_GET['sort'] ?? '') === 'titre_asc' ? 'selected' : '' ?>>Title
                                (A-Z)</option>
                            <option value="titre_desc" <?= ($_GET['sort'] ?? '') === 'titre_desc' ? 'selected' : '' ?>>Title
                                (Z-A)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 mb-0">Apply</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Search and Sort Filters END -->

    <!-- Registry START -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 overflow-hidden">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title mb-0">List of Posts</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead>
                                <tr class="small">
                                    <th class="ps-4">Identifier / Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th class="text-center">Document</th>
                                    <th class="text-center">Feedback</th>
                                    <th>Date</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($list)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No posts found matching your criteria.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($list as $p): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-md bg-light rounded-3 me-3 text-primary">
                                                        <i class="fa-solid fa-file-lines"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($p['titre']) ?></h6>
                                                        <small class="text-muted">UID:
                                                            #<?= str_pad($p['id'], 6, '0', STR_PAD_LEFT) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $catName = htmlspecialchars($p['categorie']);
                                                $catLower = strtolower(trim($catName));
                                                $bClass = 'bg-primary bg-opacity-10 text-primary';

                                                if (strpos($catLower, 'law') !== false)
                                                    $bClass = 'bg-danger bg-opacity-10 text-danger';
                                                elseif (strpos($catLower, 'general') !== false)
                                                    $bClass = 'bg-success bg-opacity-10 text-success';
                                                elseif (strpos($catLower, 'announcement') !== false)
                                                    $bClass = 'bg-warning bg-opacity-25 text-dark fw-bold';
                                                elseif (strpos($catLower, 'report') !== false)
                                                    $bClass = 'bg-info bg-opacity-10 text-info';
                                                else {
                                                    $palette = ['bg-secondary bg-opacity-10 text-secondary', 'bg-dark bg-opacity-10 text-dark', 'bg-primary bg-opacity-10 text-primary'];
                                                    $bClass = $palette[abs(crc32($catLower)) % count($palette)];
                                                }
                                                ?>
                                                <span class="badge <?= $bClass ?> px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-tag me-1 small"></i> <?= $catName ?>
                                                </span>
                                            </td>
                                            <td><span
                                                    class="small fw-medium text-body-emphasis"><?= htmlspecialchars($p['auteur']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($p['document'])): ?>
                                                    <a href="/Esprit-PW-2A20-2526-e-Dossier/uploads/<?= $p['document'] ?>" target="_blank"
                                                        class="text-primary h6" title="Download Document">
                                                        <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted opacity-25">--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=show&id=<?= $p['id'] ?>#comments"
                                                    class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill text-decoration-none">
                                                    <i class="fa-solid fa-comments me-1"></i> <?= $p['comment_count'] ?>
                                                </a>
                                            </td>
                                            <td><span
                                                    class="small text-muted"><?= date('d M Y, H:i', strtotime($p['date'])) ?></span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=show&id=<?= $p['id'] ?>"
                                                    class="btn btn-sm btn-light mb-0">View</a>
                                                <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=edit&id=<?= $p['id'] ?>"
                                                    class="btn btn-sm btn-primary-soft mb-0"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="#" onclick="confirmDelete(<?= $p['id'] ?>); return false;"
                                                    class="btn btn-sm btn-danger-soft mb-0"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Charts START -->
    <div class="row g-4 mb-5">
        <div class="col-xxl-8">
            <div class="card shadow h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title">Post Trends (Real-time Velocity)</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-4 mb-3">
                        <?php
                        $pubValues = $stats['trends_pubs'] ?? [];
                        ?>
                        <h6><span class="fw-light text-primary"><i class="bi bi-square-fill me-1"></i>
                                Posts:</span> <?= array_sum($pubValues) ?> (Total 6mo)</h6>
                        <h6><span class="fw-light text-info"><i class="bi bi-square-fill me-1"></i> Comments:</span>
                            <?= array_sum($stats['trends_comments'] ?? []) ?> (Total 6mo)</h6>
                    </div>
                    <div id="velocityChart" class="mt-2"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xxl-4">
            <div class="card shadow h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-header-title">Categories Distribution (%)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mx-auto" style="max-width: 280px;">
                        <div id="compositionChart"></div>
                    </div>
                    <ul class="list-group list-group-borderless mb-0 mt-4 px-2">
                        <?php
                        $colors = ['text-primary', 'text-success', 'text-danger', 'text-warning'];
                        foreach ($categories as $index => $cat):
                            if ($index >= 4)
                                break;
                            $cColor = $colors[$index % 4];
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center pb-2">
                                <span class="h6 fw-light mb-0"><i class="<?= $cColor ?> fas fa-circle me-2 small"></i>
                                    <?= htmlspecialchars($cat) ?></span>
                                <span class="h6 fw-light mb-0 bg-light rounded px-2 py-1"><?= $counts[$index] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Charts END -->

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Premium Chart Config
    const chartTheme = {
        theme: { mode: 'light', palette: 'palette1' },
        grid: { borderColor: 'rgba(0,0,0,0.05)', strokeDashArray: 4 },
        markers: { size: 6, strokeWidth: 3, hover: { size: 8 } }
    };

    // Velocity Chart mimicking the Template's exact curve data to fix point rendering
    var velocityOptions = {
        ...chartTheme,
        series: [{
            name: 'Posts',
            data: <?= json_encode($stats['trends_pubs']) ?>
        }, {
            name: 'Citizen Comments',
            data: <?= json_encode($stats['trends_comments']) ?>
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: [
            '#066ac9', // Primary
            '#17a2b8'  // Info
        ],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            type: 'category',
            categories: <?= json_encode($stats['trends_months']) ?>
        }
    };
    new ApexCharts(document.querySelector("#velocityChart"), velocityOptions).render();

    // Composition Chart
    var compositionOptions = {
        ...chartTheme,
        series: <?= json_encode($counts) ?>,
        chart: { type: 'donut', height: 350, fontFamily: 'Inter, sans-serif' },
        labels: <?= json_encode($categories) ?>,
        colors: ['#066ac9', '#0cbc87', '#d6293e', '#f7c32e'],
        legend: { position: 'bottom', markers: { radius: 12 } },
        stroke: { width: 0 },
        plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'TOTAL' } } } } }
    };
    new ApexCharts(document.querySelector("#compositionChart"), compositionOptions).render();

    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Document?',
            text: "Document #" + id + " will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            background: '#fff',
            customClass: { popup: 'rounded-4 shadow-lg border-0' },
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=delete&id=${id}`;
            }
        });
    }

    // Remove search params from URL so refreshing the page does not re-trigger the search
    if (window.history.replaceState) {
        const url = new URL(window.location.href);
        if (url.searchParams.has('search') || url.searchParams.has('sort')) {
            const action = url.searchParams.get('action');
            const newUrl = new URL(window.location.pathname, window.location.origin);
            if (action) {
                newUrl.searchParams.set('action', action);
            }
            window.history.replaceState({}, document.title, newUrl.toString());
        }
    }
</script>

<?php require_once "footer.php"; ?>