<?php 
// view/design/comments/comments.php - Unified Midnight Design Layer
include __DIR__ . '/../../layouts/admin_header.php'; 
?>

<!-- Custom Midnight Theme Styles -->
<style>
    body { background-color: #0F172A !important; color: #E2E8F0 !important; }
    .card { background: rgba(30, 41, 59, 0.7) !important; backdrop-filter: blur(12px) !important; border: 1px solid rgba(255,255,255,0.05) !important; box-shadow: 0 8px 32px rgba(0,0,0,0.3) !important; border-radius: 1.5rem !important; }
    .text-primary { color: #38BDF8 !important; }
    .bg-primary { background-color: #38BDF8 !important; }
    .btn-primary { background: linear-gradient(135deg, #38BDF8 0%, #2563EB 100%) !important; border: none !important; box-shadow: 0 4px 14px rgba(56, 189, 248, 0.4) !important; }
    .btn-outline-primary { border-color: #38BDF8 !important; color: #38BDF8 !important; }
    .form-control, .form-select { background-color: #1E293B !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #F1F5F9 !important; border-radius: 0.75rem !important; }
    .form-control:focus { box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2) !important; }
    .badge-soft-primary { background: rgba(56, 189, 248, 0.1) !important; color: #38BDF8 !important; }
    .text-muted { color: #94A3B8 !important; }
    .border-start-primary { border-left: 4px solid #38BDF8 !important; }
    .avatar-midnight { background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%) !important; border: 1px solid rgba(56, 189, 248, 0.3) !important; color: #38BDF8 !important; }
    hr { border-color: rgba(255,255,255,0.1) !important; }
    @keyframes pulse-live {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .badge-live {
        background-color: #EF4444 !important;
        animation: pulse-live 2s infinite;
        display: inline-block;
        width: 10px;
        height: 10px;
    }
</style>

<div class="row g-4">
    <div class="col-12">
        <!-- Header -->
        <div class="mb-5 mt-4 ps-2">
            <h1 class="display-6 fw-bold text-white mb-3">
                <i class="bi bi-chat-fill me-3 text-primary"></i>User Comments
            </h1>
            <p class="text-muted fs-5 mb-0 d-flex align-items-center">
                <span class="badge-live rounded-circle me-3"></span>
                See what people are saying right now.
            </p>
        </div>


        <!-- Search & Sort Filter -->
        <div class="card p-3 mb-4 border-0 input-dark-mode">
            <form method="GET" action="/projetweb/view/back-office/index.php" class="row g-3 align-items-center m-0">
                <input type="hidden" name="action" value="comments">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-muted pe-0"><i class="bi bi-search"></i></span>
                        <input type="search" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search by content, author, or publication title..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="sort" class="form-select border-0 bg-transparent text-white shadow-none">
                        <option class="text-dark" value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Sort by Date (Newest)</option>
                        <option class="text-dark" value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Sort by Date (Oldest)</option>
                        <option class="text-dark" value="auteur_asc" <?= ($_GET['sort'] ?? '') === 'auteur_asc' ? 'selected' : '' ?>>Sort by Author (A-Z)</option>
                        <option class="text-dark" value="auteur_desc" <?= ($_GET['sort'] ?? '') === 'auteur_desc' ? 'selected' : '' ?>>Sort by Author (Z-A)</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill mb-0">Apply</button>
                </div>
            </form>
        </div>

        <!-- Global Stream -->
        <div class="vstack gap-4">
            <?php if(isset($list) && count($list) > 0): ?>
                <?php foreach($list as $c): ?>
                    <div class="card border-0 rounded-4 overflow-hidden p-4 transition-all hover-shadow border-start-primary">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                                <div class="avatar avatar-xl mx-auto mx-md-0">
                                    <div class="avatar-midnight rounded-circle d-flex align-items-center justify-content-center fs-3 fw-bold shadow-sm">
                                        <?= strtoupper(substr($c['auteur'], 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="mt-2 text-primary fw-bold small text-uppercase ls-wide" style="font-size: 0.6rem;">Verified Auditor</div>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 me-3 text-white"><?= htmlspecialchars($c['auteur']) ?></h5>
                                    <span class="text-muted small ps-3 border-start"><i class="bi bi-clock me-1"></i><?= date('h:i A / d M Y', strtotime($c['date'])) ?></span>
                                    
                                    <?php 
                                        $sentiment = $commentCtrl->analyzeSentiment($c['contenu']);
                                        $badgeClass = 'bg-secondary';
                                        if ($sentiment === 'Positive') $badgeClass = 'bg-success';
                                        if ($sentiment === 'Critical') $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $badgeClass) ?> ms-3 rounded-pill px-2">
                                        <i class="bi bi-lightning-charge-fill me-1"></i><?= $sentiment ?>
                                    </span>
                                </div>
                                <div class="p-3 bg-dark bg-opacity-25 rounded-3 mb-3 border" style="border-color: rgba(255,255,255,0.05) !important;">
                                    <p class="mb-0 text-white opacity-75"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-soft-primary px-3 py-2 rounded-pill small">
                                        <i class="bi bi-journal-text me-2"></i>Post: <?= htmlspecialchars($c['publication_titre'] ?? 'Unmapped') ?>
                                    </span>
                                    <span class="text-muted small ms-3"><i class="bi bi-person-check me-1"></i>By: <?= htmlspecialchars($c['publication_auteur'] ?? 'Unknown') ?></span>
                                </div>
                            </div>

                            <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="/projetweb/view/back-office/index.php?action=editComment&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4">Edit</a>
                                    <a href="/projetweb/view/comments/delete.php?id=<?= $c['id'] ?>&from=admin" class="btn btn-sm btn-link text-danger text-decoration-none px-4" onclick="return confirm('Delete this comment?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card py-5 text-center">
                    <div class="icon-xl bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-4"><i class="bi bi-slash-circle fs-1"></i></div>
                    <h3 class="text-white">No Comments</h3>
                    <p class="text-muted mb-0">No comments found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
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

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
