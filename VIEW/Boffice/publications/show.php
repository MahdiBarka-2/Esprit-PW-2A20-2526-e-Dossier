<?php
require_once __DIR__ . "/../../../CONTROLLER/PublicationC.php";
require_once __DIR__ . "/../../../CONTROLLER/CommentC.php";

$pubCtrl = new PublicationC();
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: posts.php");
    exit;
}

$publication = $pubCtrl->getOnePublication($id);
if (!$publication) {
    header("Location: posts.php");
    exit;
}

$comments = $pubCtrl->commentCtrl->getCommentsByPublication($id, true);

require_once __DIR__ . "/../header.php"; 
?>

<style>
    /* Premium Midnight Body */
    body, .page-content-wrapper {
        background-color: #0f172a !important;
        color: #f8fafc !important;
    }

    /* Original Premium Card Style but Dark */
    .dark-card {
        background: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-radius: 1rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    }

    .dark-card-header {
        background: rgba(255, 255, 255, 0.02) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .text-muted-slate {
        color: #94a3b8 !important;
    }

    .content-body {
        color: #e2e8f0 !important;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .btn-generate-insight {
        background: #3b82f6 !important;
        color: white !important;
        border: none !important;
        transition: all 0.3s ease;
    }

    .btn-generate-insight:hover {
        background: #2563eb !important;
        transform: translateY(-2px);
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }

    /* Modal Dark Theme */
    .modal-content {
        background-color: #1e293b !important;
        color: #f8fafc !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
</style>

<div class="page-content-wrapper p-xxl-4">

    <!-- Header Section (Original Structure) -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary bg-opacity-20 text-primary px-3 rounded-pill">Back-office Portal</span>
                <span class="text-muted-slate small">/ Posts</span>
            </div>
            <h1 class="h3 mb-0 fw-bold text-white">Post Details</h1>
        </div>
        <div class="d-flex gap-3">
            <a href="posts.php" class="btn btn-outline-light rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Go Back
            </a>
            <a href="posts.php?action=edit&id=<?= $id ?>" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <div class="card dark-card mb-4 overflow-hidden">
                <!-- Header with Gradient (Re-tinted for Dark) -->
                <div class="card-header dark-card-header p-4" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(6, 182, 212, 0.05) 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 rounded-2 small fw-bold">
                                    <i class="bi bi-hash me-1"></i>UID-<?= str_pad($publication['id'], 5, '0', STR_PAD_LEFT) ?>
                                </span>
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill small fw-bold border border-info border-opacity-25">
                                    <i class="bi bi-bookmark-star-fill me-1"></i> <?= htmlspecialchars($publication['categorie']) ?>
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold border border-success border-opacity-25">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified
                                </span>
                            </div>
                            <h2 class="display-6 fw-bold mb-2 text-white" style="letter-spacing: -1px;"><?= htmlspecialchars($publication['titre']) ?></h2>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Main Article Body -->
                    <div class="publication-content mb-5">
                        <h5 class="fw-bold mb-3 border-bottom border-secondary pb-2 text-white">Content</h5>
                        <div class="fs-5 text-muted-slate lh-lg" style="white-space: pre-line; text-align: justify;">
                            <?= htmlspecialchars($publication['contenu']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizen Feedback Section -->
            <div class="card dark-card">
                <div class="card-header dark-card-header p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-chat-left-text me-2"></i>Comments</h5>
                    <span class="badge bg-primary rounded-pill px-3"><?= count($comments) ?> Total</span>
                </div>
                <div class="card-body p-4">
                    <?php if(empty($comments)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots display-1 text-secondary opacity-25"></i>
                            <p class="text-muted-slate mt-3">No feedback received for this post yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="vstack gap-3">
                            <?php foreach($comments as $c): ?>
                                <div class="p-4 rounded-4 shadow-sm transition-all hover-shadow-lg mb-2" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar avatar-sm bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                                                <?= strtoupper(substr($c['utilisateur'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-white"><?= htmlspecialchars($c['utilisateur']) ?></h6>
                                                <small class="text-muted-slate"><i class="bi bi-clock me-1"></i><?= date('d M Y, H:i', strtotime($c['date'])) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-1 ps-4 border-start border-3 border-primary border-opacity-50">
                                        <p class="mb-0 text-white" style="font-size: 1.05rem; line-height: 1.6; opacity: 0.9;"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if(($c['status'] ?? 'Approved') === 'Flagged'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill small border border-danger border-opacity-20"><i class="bi bi-exclamation-triangle-fill me-1"></i>AI Flagged</span>
                                                <a href="posts.php?action=approveComment&id=<?= $c['id'] ?>&publication_id=<?= $publication['id'] ?>" class="btn btn-sm btn-success rounded-pill ms-2 px-3">
                                                    <i class="bi bi-check-circle me-1"></i>Approve
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small border border-success border-opacity-20"><i class="bi bi-shield-check me-1"></i>Approved Community Feedback</span>
                                            <?php endif; ?>
                                        </div>
                                        <button onclick="confirmDeleteComment(<?= $c['id'] ?>)" class="btn btn-sm btn-outline-danger border-0 rounded-pill px-3">
                                            <i class="bi bi-trash3 me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card dark-card mb-4 overflow-hidden">
                <div class="card-header bg-primary bg-opacity-10 p-4 border-bottom border-primary border-opacity-10">
                    <h5 class="mb-0 text-white fw-bold"><i class="bi bi-robot me-2 text-primary"></i>AI Tool</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted-slate small mb-4">Click below to see what AI thinks about this post and its comments.</p>
                    <button id="btn-generate-insight" class="btn btn-generate-insight w-100 rounded-pill py-2 mb-3">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Get AI Insight
                    </button>
                    <a href="posts.php?action=exportPDF&id=<?= $id ?>" class="btn btn-outline-light w-100 rounded-pill py-2">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Export as PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Modal -->
<div class="modal fade" id="aiInsightModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-robot me-2 text-primary"></i>AI Result</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="ai-loading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary mb-3"></div>
                    <p class="text-muted-slate">Thinking...</p>
                </div>
                <div id="insight-content" class="d-none">
                    <div class="p-4 rounded-4 border border-secondary mb-4" id="ai-response-container" style="background: rgba(255,255,255,0.02); line-height: 1.8;">
                        <!-- AI Content will be injected here -->
                    </div>
                    <button class="btn btn-primary w-100 rounded-pill py-2" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print Report</button>
                </div>
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
        background: '#1e293b',
        color: '#fff',
        customClass: { popup: 'rounded-4 shadow-lg border-0' },
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'posts.php?action=deleteComment&id=' + id + '&publication_id=<?= $publication['id'] ?>';
        }
    });
}

document.getElementById('btn-generate-insight').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('aiInsightModal'));
    modal.show();
    
    const loading = document.getElementById('ai-loading');
    const content = document.getElementById('insight-content');
    const container = document.getElementById('ai-response-container');
    
    loading.classList.remove('d-none');
    content.classList.add('d-none');
    
    fetch('posts.php?action=strategicInsight&id=<?= $publication['id'] ?>')
        .then(response => response.json())
        .then(data => {
            loading.classList.add('d-none');
            content.classList.remove('d-none');
            
            if (data.error) {
                container.innerHTML = '<div class="alert alert-danger bg-opacity-10 border-0 text-danger">' + data.error + '</div>';
            } else {
                let formatted = data.insight
                    .replace(/\n/g, '<br>')
                    .replace(/(🎯|⚠️|💡) (.*?):/g, '<h6 class="fw-bold mt-4 mb-2 text-primary">$1 $2:</h6>')
                    .replace(/\* (.*?)(<br>|$)/g, '<div class="d-flex align-items-start mb-2"><i class="bi bi-check-circle-fill me-2 text-primary small mt-1"></i><span>$1</span></div>');
                container.innerHTML = formatted;
            }
        })
        .catch(err => {
            loading.classList.add('d-none');
            content.classList.remove('d-none');
            container.innerHTML = '<div class="alert alert-danger bg-opacity-10 border-0 text-danger">Connection failed.</div>';
        });
});
</script>

<?php require_once __DIR__ . "/../footer.php"; ?>
