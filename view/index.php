<?php include 'layouts/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Official Publications</h1>
        <p class="lead text-muted">Browse the latest government announcements and documents.</p>
    </div>

    <div class="row g-4">
        <?php if(isset($list) && count($list) > 0): ?>
            <?php foreach($list as $p): ?>
            <div class="col-md-4">
                <a href="index1.php?action=show&id=<?= $p['id'] ?>" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 bg-dark text-white hover-card">
                        <div class="card-body">
                            <!-- Category badge top -->
                            <span class="badge mb-2
                                <?= $p['categorie'] == 'Law' ? 'bg-danger' : ($p['categorie'] == 'Announcement' ? 'bg-warning text-dark' : 'bg-info text-dark') ?>">
                                <i class="bi bi-tag me-1"></i><?= htmlspecialchars($p['categorie']) ?>
                            </span>

                            <h5 class="card-title fw-bold text-primary">
                                <?= htmlspecialchars($p['titre']) ?>
                            </h5>
                            <p class="card-text text-muted">
                                <?= htmlspecialchars(substr($p['contenu'], 0, 100)) ?>...
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-info">
                                    <i class="bi bi-calendar-event"></i> <?= date('M d, Y', strtotime($p['date'])) ?>
                                </small>
                                <span class="badge bg-secondary"><?= htmlspecialchars($p['auteur']) ?></span>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <span class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i> Read More
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No publications available yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
}
</style>

<?php include 'layouts/footer.php'; ?>