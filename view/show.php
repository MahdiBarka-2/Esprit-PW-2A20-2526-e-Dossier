<?php 
include 'layouts/header.php';
include_once __DIR__ . '/../model/Comment.php';
$commentModel = new Comment();
$comments = $commentModel->getCommentsByPublication($publication['id']);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <a href="index1.php" class="btn btn-outline-light mb-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Publications
            </a>

            <!-- Publication Card -->
            <div class="card shadow-lg border-0 bg-dark text-white">
                <div class="card-body p-5">
                    
                    <div class="mb-4">
                        <h1 class="display-5 fw-bold text-primary mb-3">
                            <?= htmlspecialchars($publication['titre']) ?>
                        </h1>
                        
                        <div class="d-flex gap-3 flex-wrap">
                            <span class="badge bg-info">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?= date('F d, Y', strtotime($publication['date'])) ?>
                            </span>
                            <span class="badge bg-secondary">
                                <i class="bi bi-person me-1"></i>
                                <?= htmlspecialchars($publication['auteur']) ?>
                            </span>
                            <span class="badge <?= $publication['categorie'] == 'Law' ? 'bg-danger' : ($publication['categorie'] == 'Announcement' ? 'bg-warning text-dark' : 'bg-info text-dark') ?>">
                                <i class="bi bi-tag me-1"></i>
                                <?= htmlspecialchars($publication['categorie']) ?>
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>
                                Published
                            </span>
                        </div>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="content mb-4">
                        <h5 class="text-warning mb-3">
                            <i class="fas fa-file-alt me-2"></i>Full Content
                        </h5>
                        <div class="p-4 bg-secondary bg-opacity-10 rounded">
                            <p class="lead" style="white-space: pre-line; line-height: 1.8;">
                                <?= nl2br(htmlspecialchars($publication['contenu'])) ?>
                            </p>
                        </div>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="d-flex justify-content-between">
                        <a href="index1.php" class="btn btn-outline-light">
                            <i class="fas fa-list me-2"></i> All Publications
                        </a>
                        <a href="index1.php?action=addComment&publication_id=<?= $publication['id'] ?>" class="btn btn-warning">
                            <i class="fas fa-comment me-2"></i> Comment
                        </a>
                    </div>

                </div>
            </div>

            <!-- Comments Section -->
            <div class="mt-5">
                <h4 class="text-white mb-4">
                    <i class="fas fa-comments me-2"></i>
                    Comments (<?= count($comments) ?>)
                </h4>

                <?php if(count($comments) > 0): ?>
                    <?php foreach($comments as $c): ?>
                    <div class="card bg-dark text-white border-secondary mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="text-primary mb-1">
                                        <i class="bi bi-person-circle me-2"></i>
                                        <?= htmlspecialchars($c['auteur']) ?>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?= date('M d, Y H:i', strtotime($c['date'])) ?>
                                    </small>
                                </div>
                                <div>
                                    <a href="index1.php?action=editComment&id=<?= $c['id'] ?>" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index1.php?action=deleteComment&id=<?= $c['id'] ?>&publication_id=<?= $publication['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this comment?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="mt-3 mb-0"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card bg-dark text-white border-secondary">
                        <div class="card-body text-center py-4">
                            <p class="text-muted mb-0">No comments yet. Be the first to comment!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>