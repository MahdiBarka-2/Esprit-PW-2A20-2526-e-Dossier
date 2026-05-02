<?php 
// view/design/comments/comments.php - Unified Midnight Design Layer
require_once __DIR__ . '/../../Boffice/header.php'; 
?>

<!-- Custom Midnight Theme Styles -->
<style>
    /* Styles preserved */
    .card { background: rgba(30, 41, 59, 0.7) !important; backdrop-filter: blur(12px) !important; border: 1px solid rgba(255,255,255,0.05) !important; box-shadow: 0 8px 32px rgba(0,0,0,0.3) !important; border-radius: 1.5rem !important; }
    .text-primary { color: #38BDF8 !important; }
    .bg-primary { background-color: #38BDF8 !important; }
    .btn-primary { background: linear-gradient(135deg, #38BDF8 0%, #2563EB 100%) !important; border: none !important; box-shadow: 0 4px 14px rgba(56, 189, 248, 0.4) !important; }
    .btn-outline-primary { border-color: #38BDF8 !important; color: #38BDF8 !important; }
    .form-control, .form-select { background-color: #1E293B !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #F1F5F9 !important; border-radius: 0.75rem !important; }
    .badge-soft-primary { background: rgba(56, 189, 248, 0.1) !important; color: #38BDF8 !important; }
    .text-muted { color: #94A3B8 !important; }
    .border-start-primary { border-left: 4px solid #38BDF8 !important; }
    .avatar-midnight { background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%) !important; border: 1px solid rgba(56, 189, 248, 0.3) !important; color: #38BDF8 !important; }
</style>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-12">
            <!-- Header -->
            <div class="mb-5 mt-4 ps-2">
                <h1 class="display-6 fw-bold text-white mb-3">
                    <i class="bi bi-chat-fill me-3 text-primary"></i>User Comments
                </h1>
                <p class="text-muted fs-5 mb-0">Manage citizen engagement and AI-moderated feedback.</p>
            </div>


            <!-- Search & Sort Filter -->
            <div class="card p-3 mb-4 border-0 input-dark-mode">
                <form method="GET" action="/integration/VIEW/Boffice/posts.php" class="row g-3 align-items-center m-0">
                    <input type="hidden" name="action" value="comments">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-muted pe-0"><i class="bi bi-search"></i></span>
                            <input type="search" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search comments..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="sort" class="form-select border-0 bg-transparent text-white shadow-none">
                            <option value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                            <option value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
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
                        <div class="card border-0 rounded-4 overflow-hidden p-4 transition-all hover-shadow border-start-primary mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center text-md-start">
                                    <div class="avatar avatar-xl mx-auto mx-md-0">
                                        <div class="avatar-midnight rounded-circle d-flex align-items-center justify-content-center fs-3 fw-bold shadow-sm" style="width: 60px; height: 60px;">
                                            <?= strtoupper(substr($c['utilisateur'] ?? 'C', 0, 1)) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="mb-0 me-3 text-white"><?= htmlspecialchars($c['utilisateur'] ?? 'Citizen') ?></h5>
                                        <span class="text-muted small ps-3 border-start"><i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($c['date'])) ?></span>
                                        
                                        <?php if(($c['status'] ?? 'Approved') === 'Flagged'): ?>
                                            <span class="badge bg-danger ms-2">AI Flagged</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-3 bg-dark bg-opacity-25 rounded-3 mb-3 border" style="border-color: rgba(255,255,255,0.05) !important;">
                                        <p class="mb-0 text-white opacity-75"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-soft-primary px-3 py-2 rounded-pill small">
                                            <i class="bi bi-journal-text me-2"></i>Post: <?= htmlspecialchars($c['publication_titre'] ?? 'Unmapped') ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3 text-center text-md-end">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <?php if(($c['status'] ?? 'Approved') === 'Flagged'): ?>
                                            <a href="/integration/VIEW/Boffice/posts.php?action=approveComment&id=<?= $c['id'] ?>" class="btn btn-sm btn-success rounded-pill px-3">Approve</a>
                                        <?php endif; ?>
                                        <a href="javascript:void(0)" onclick="confirmDeleteComment(<?= $c['id'] ?>)" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card py-5 text-center">
                        <h3 class="text-white">No Comments</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeleteComment(id) {
    if(confirm('Delete this comment permanently?')) {
        window.location.href = `/integration/VIEW/Boffice/posts.php?action=deleteComment&id=${id}&from=admin`;
    }
}
</script>

<?php include __DIR__ . '/../../Boffice/footer.php'; ?>
