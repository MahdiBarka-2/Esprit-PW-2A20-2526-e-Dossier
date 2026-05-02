<?php
// VIEW/Boffice/publications/show.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../CONTROLLER/PublicationC.php';
$pubCtrl = new PublicationC();

$id = $_GET['id'] ?? '';
$publication = $pubCtrl->getOnePublication($id);

if (!$publication) {
    header("Location: /integration/VIEW/Boffice/posts.php");
    exit();
}

$comments = $pubCtrl->commentCtrl->getCommentsByPublication($id, true);

require_once __DIR__ . "/../header.php"; 
?>

<div class="page-content-wrapper p-xxl-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1">
                <i class="bi bi-file-earmark-richtext me-2 text-primary"></i>Publication Insight
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-dots mb-0">
                    <li class="breadcrumb-item"><a href="posts.php">Publications</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View #<?= $publication['id'] ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="/integration/VIEW/Boffice/posts.php?action=edit&id=<?= $publication['id'] ?>" class="btn btn-primary shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Modify
            </a>
            <a href="/integration/index1.php?action=download&id=<?= $publication['id'] ?>" class="btn btn-primary text-white shadow-sm rounded-pill px-4">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Export Strategic PDF
            </a>
            <a href="/integration/VIEW/Boffice/posts.php" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Return
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <!-- Cover Image / Banner Placeholder if needed, otherwise just header -->
                <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 rounded-2 small fw-bold shadow-sm" style="letter-spacing: 1px; border: 1px solid rgba(255,255,255,0.1);">
                                    <i class="bi bi-hash me-1"></i>PUB-<?= str_pad($publication['id'], 5, '0', STR_PAD_LEFT) ?>
                                </span>
                                <span class="badge bg-primary bg-opacity-25 text-primary px-3 py-2 rounded-pill small fw-bold border border-primary border-opacity-25">
                                    <i class="bi bi-bookmark-star-fill me-1"></i> <?= htmlspecialchars($publication['categorie']) ?>
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold border border-success border-opacity-25">
                                    <i class="bi bi-patch-check-fill me-1"></i> Verified Official
                                </span>
                            </div>
                            <h2 class="display-6 fw-bold text-dark mb-2"><?= htmlspecialchars($publication['titre']) ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <!-- Author & Date Info Bar -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5 p-3 bg-light rounded-4 border">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-md">
                                <div class="avatar-img rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold">
                                    <?= strtoupper(substr($publication['auteur'], 0, 1)) ?>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($publication['auteur']) ?></h6>
                                <p class="text-muted small mb-0">Issuing Authority</p>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <p class="mb-0 fw-bold text-dark"><i class="bi bi-calendar-check me-2"></i><?= date('d M Y', strtotime($publication['date'])) ?></p>
                            <p class="text-muted small mb-0"><?= date('H:i A', strtotime($publication['date'])) ?></p>
                        </div>
                    </div>

                    <!-- Main Article Body -->
                    <div class="publication-content mb-5">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Document Content</h5>
                        <div class="fs-5 text-secondary lh-lg" style="white-space: pre-line; text-align: justify;">
                            <?= htmlspecialchars($publication['contenu']) ?>
                        </div>
                    </div>

                    <!-- Attachment Section -->
                    <?php if(!empty($publication['document'])): ?>
                    <div class="card bg-primary bg-opacity-10 border border-primary border-opacity-25 p-4 rounded-4">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="icon-xl bg-white rounded-circle shadow-sm text-primary">
                                    <i class="bi bi-file-earmark-pdf-fill fs-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Digital Reference File</h6>
                                    <small class="text-muted"><?= htmlspecialchars($publication['document']) ?></small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/integration/uploads/<?= $publication['document'] ?>" target="_blank" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-download me-2"></i>Download / Save
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Comments Moderation Area -->
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom bg-transparent p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-chat-left-dots-fill me-2 text-primary"></i>Citizen Feedback Hub 
                        <span class="badge bg-primary rounded-pill ms-2"><?= count($comments) ?></span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if(empty($comments)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-square-dots fs-1 text-muted opacity-25"></i>
                            <p class="text-muted mt-3">No engagement data recorded for this publication.</p>
                        </div>
                    <?php else: ?>
                        <div class="vstack gap-3">
                            <?php foreach($comments as $c): ?>
                                <div class="p-3 bg-light rounded-4 border border-opacity-50 position-relative transition-all hover-shadow">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-xs bg-dark rounded-circle text-white text-center fs-6 fw-bold">
                                                <?= strtoupper(substr($c['utilisateur'], 0, 1)) ?>
                                            </span>
                                            <h6 class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($c['utilisateur']) ?></h6>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i><?= date('d M, H:i', strtotime($c['date'])) ?></small>
                                    </div>
                                    <p class="mb-0 small text-secondary ps-4 border-start border-2 border-primary-soft ms-2"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if(($c['status'] ?? 'Approved') === 'Flagged'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger small"><i class="bi bi-exclamation-triangle-fill me-1"></i>AI Flagged</span>
                                                <a href="/integration/VIEW/Boffice/posts.php?action=approveComment&id=<?= $c['id'] ?>&publication_id=<?= $publication['id'] ?>" class="btn btn-sm btn-success rounded-pill ms-2">
                                                    <i class="bi bi-check-circle me-1"></i>Approve
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-success bg-opacity-10 text-success small"><i class="bi bi-shield-check me-1"></i>Approved</span>
                                            <?php endif; ?>
                                        </div>
                                        <button onclick="confirmDeleteComment(<?= $c['id'] ?>)" class="btn btn-sm btn-link p-0 text-danger small fw-bold text-decoration-none">
                                            <i class="bi bi-trash3 me-1"></i>Delete Feedback
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Details & Actions -->
        <div class="col-lg-4">
            <!-- Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom bg-transparent p-4">
                    <h5 class="fw-bold mb-0">Document Details</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-0 px-4">
                            <span class="text-muted small">Global ID</span>
                            <span class="fw-bold text-dark">#<?= $publication['id'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-0 px-4">
                            <span class="text-muted small">Category</span>
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded"><?= $publication['categorie'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-0 px-4">
                            <span class="text-muted small">Word Count</span>
                            <span class="fw-bold"><?= str_word_count($publication['contenu']) ?> words</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3 border-0 px-4">
                            <span class="text-muted small">Reading Time</span>
                            <span class="fw-bold">~<?= ceil(str_word_count($publication['contenu'])/200) ?> min</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Engagement Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-bottom bg-transparent p-4">
                    <h5 class="fw-bold mb-0">Engagement Pulse</h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 bg-primary bg-opacity-10 rounded-4">
                                <h3 class="mb-0 text-primary"><?= count($comments) ?></h3>
                                <p class="text-primary small mb-0 fw-bold">Feedback</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <?php 
                                $pos = 0;
                                foreach($comments as $cm) if($pubCtrl->commentCtrl->analyzeSentiment($cm['contenu']) === 'Positive') $pos++;
                            ?>
                            <div class="p-3 bg-success bg-opacity-10 rounded-4">
                                <h3 class="mb-0 text-success"><?= $pos ?></h3>
                                <p class="text-success small mb-0 fw-bold">Positive</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm sticky-top" style="top: 2rem;">
                <div class="card-header border-bottom bg-transparent p-4">
                    <h5 class="fw-bold mb-0">Quick Operations</h5>
                </div>
                <div class="card-body p-4">
                    <div class="vstack gap-2">
                        <a href="/integration/index1.php?action=show&id=<?= $publication['id'] ?>" target="_blank" class="btn btn-light-soft w-100 text-start py-2">
                            <i class="bi bi-box-arrow-up-right me-3 text-primary"></i>View in Portal
                        </a>
                        <button onclick="window.print()" class="btn btn-light-soft w-100 text-start py-2">
                            <i class="bi bi-printer me-3 text-primary"></i>Generate Archive (Print)
                        </button>
                        <a href="/integration/index1.php?action=download&id=<?= $publication['id'] ?>" class="btn btn-light-soft w-100 text-start py-2">
                            <i class="bi bi-file-earmark-pdf me-3 text-primary"></i>Download PDF Fact Sheet
                        </a>
                        <a href="/integration/uploads/<?= $publication['document'] ?>" download class="btn btn-light-soft w-100 text-start py-2">
                            <i class="bi bi-cloud-arrow-down me-3 text-primary"></i>Save to Drive
                        </a>
                        <hr class="my-2">
                        <button onclick="confirmDelete(<?= $publication['id'] ?>)" class="btn btn-danger-soft w-100 text-start py-2">
                            <i class="bi bi-trash3 me-3 text-danger"></i>Delete Permanently
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Delete Publication?',
        text: "This will remove the publication and all associated feedback forever.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        background: '#fff',
        customClass: { popup: 'rounded-4 shadow-lg border-0' },
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/integration/VIEW/Boffice/posts.php?action=delete&id=${id}`;
        }
    });
}

function confirmDeleteComment(id) {
    Swal.fire({
        title: 'Remove Feedback?',
        text: "Are you sure you want to delete this citizen comment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        background: '#fff',
        customClass: { popup: 'rounded-4 shadow-lg border-0' },
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/integration/VIEW/Boffice/posts.php?action=deleteComment&id=${id}&from=admin`;
        }
    });
}
</script>

<?php require_once __DIR__ . "/../footer.php"; ?>
