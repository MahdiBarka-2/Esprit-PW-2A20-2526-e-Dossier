<?php
// VIEW/Boffice/comments/comments.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../CONTROLLER/CommentC.php';
$commentCtrl = new CommentC();

// Fetch list and stats for rendering
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'date_desc';

$list = $commentCtrl->getAllComments($search, $sort);
$stats = $commentCtrl->getCommentStats();

require_once __DIR__ . "/../header.php"; 
?>

<!-- Custom Styles adapted for Integration -->
<style>
    .comment-card { border-radius: 1rem !important; transition: all 0.2s ease; }
    .comment-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; }
    .border-start-primary { border-left: 4px solid var(--bs-primary) !important; }
    .badge-live {
        background-color: #EF4444 !important;
        animation: pulse-live 2s infinite;
        display: inline-block;
        width: 10px;
        height: 10px;
    }
    @keyframes pulse-live {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>

<div class="page-content-wrapper p-xxl-4">
    <div class="row g-4">
        <div class="col-12">
            <!-- Header -->
            <div class="mb-5 mt-4">
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-chat-fill me-2 text-primary"></i>Comments Management
                </h1>
                <p class="text-muted small mb-0">
                    View and moderate all comments on posts.
                </p>
            </div>

            <!-- Search & Sort Filter -->
            <div class="card p-3 mb-4 border-0 shadow-sm">
                <form method="GET" action="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php" class="row g-3 align-items-center m-0">
                    <input type="hidden" name="action" value="comments">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                            <input type="search" name="search" class="form-control border-0 bg-light shadow-none" placeholder="Search by content, author, or publication title..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="sort" class="form-select border-0 bg-light shadow-none">
                            <option value="date_desc" <?= ($sort === 'date_desc') ? 'selected' : '' ?>>Newest First</option>
                            <option value="date_asc" <?= ($sort === 'date_asc') ? 'selected' : '' ?>>Oldest First</option>
                            <option value="utilisateur_asc" <?= ($sort === 'utilisateur_asc') ? 'selected' : '' ?>>Author (A-Z)</option>
                            <option value="utilisateur_desc" <?= ($sort === 'utilisateur_desc') ? 'selected' : '' ?>>Author (Z-A)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill mb-0">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Global Stream -->
            <div class="vstack gap-4">
                <?php if(!empty($list)): ?>
                    <?php foreach($list as $c): ?>
                        <div class="card border-0 shadow-sm comment-card overflow-hidden p-4 border-start-primary">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                                    <div class="avatar avatar-xl mx-auto mx-md-0">
                                        <div class="avatar-img rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fs-3 fw-bold text-primary">
                                            <?= strtoupper(substr($c['utilisateur'], 0, 1)) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-0 me-3"><?= htmlspecialchars($c['utilisateur']) ?></h6>
                                        <span class="text-muted small ps-3 border-start"><i class="bi bi-clock me-1"></i><?= date('H:i / d M Y', strtotime($c['date'])) ?></span>
                                        
                                        <?php 
                                            $sentiment = $commentCtrl->analyzeSentiment($c['contenu']);
                                            $badgeClass = 'bg-secondary';
                                            if ($sentiment === 'Positive') $badgeClass = 'bg-success';
                                            if ($sentiment === 'Critical') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $badgeClass) ?> ms-3 rounded-pill px-2">
                                            <?= $sentiment ?>
                                        </span>
                                    </div>
                                    <div class="p-3 bg-light rounded-3 mb-3">
                                        <p class="mb-0 text-body small"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small">
                                            <i class="bi bi-journal-text me-2"></i>Post: <?= htmlspecialchars($c['publication_titre'] ?? 'Unmapped') ?>
                                        </span>
                                        <span class="text-muted small ms-md-3"><i class="bi bi-person-check me-1"></i>By: <?= htmlspecialchars($c['publication_auteur'] ?? 'Unknown') ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                                    <div class="d-md-flex justify-content-md-end">
                                        <a href="javascript:void(0)" onclick="confirmDeleteComment(<?= $c['id'] ?>)" class="btn btn-sm btn-link text-danger text-decoration-none px-4">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card py-5 text-center shadow-sm border-0">
                        <div class="icon-xl bg-light text-muted rounded-circle mx-auto mb-4"><i class="bi bi-chat-square-dots fs-1"></i></div>
                        <h3>No Discussions Found</h3>
                        <p class="text-muted mb-0">Try adjusting your filters or search keywords.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeleteComment(id) {
    Swal.fire({
        title: 'Delete Feedback?',
        text: "This citizen comment will be permanently removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        background: '#fff',
        customClass: { popup: 'rounded-4 shadow-lg border-0' },
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php?action=deleteComment&id=${id}&from=admin`;
        }
    });
}
</script>

<?php require_once __DIR__ . "/../footer.php"; ?>
