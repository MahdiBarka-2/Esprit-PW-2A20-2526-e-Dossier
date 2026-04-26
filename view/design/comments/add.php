<?php 
// view/design/comments/add.php - Institutional Design Layer
include __DIR__ . '/../../layouts/header.php'; 
?>

<!-- **************** MAIN CONTENT START **************** -->
<main>

<section class="pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mt-sm-8 mb-4">
                <div class="d-flex align-items-center">
                    <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="bi bi-chat-left-dots-fill"></i></div>
                    <div>
                        <h1 class="h3 mb-1">Add a Comment</h1>
                        <p class="mb-0 text-muted">Share your thoughts on this publication.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 mx-auto">
                <!-- Card START -->
                <div class="card shadow-lg rounded-4 border-0 p-4 p-md-5">
                    
                    <?php if(isset($_SESSION['errors'])): ?>
                        <div class="alert alert-danger p-3 rounded-3 mb-4">
                            <ul class="mb-0 small">
                                <?php foreach($_SESSION['errors'] as $e): ?>
                                    <li><?= htmlspecialchars($e) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <input type="hidden" name="publication_id" value="<?= htmlspecialchars($_GET['publication_id'] ?? $_SESSION['old']['publication_id'] ?? '') ?>">

                        <div class="row g-4">
                            <!-- Author -->
                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Full Name (Display Name) *</label>
                                <div class="input-group drop-shadow-sm">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="auteur" id="auteur" class="form-control bg-light border-0 py-2" placeholder="e.g., Jane Doe" 
                                           value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? '') ?>">
                                </div>
                                <small class="text-muted tiny">Your name will be visible to all readers of this post.</small>
                            </div>

                            <!-- Content -->
                            <div class="col-12">
                                <label class="form-label h6 fw-bold">Your Comment *</label>
                                <textarea name="contenu" id="contenu" class="form-control bg-light border-0 p-4" rows="6" placeholder="Share your professional observations or questions..."><?= htmlspecialchars($_SESSION['old']['contenu'] ?? '') ?></textarea>
                            </div>

                            <?php unset($_SESSION['old']); ?>

                            <!-- Controls -->
                            <div class="col-12 d-sm-flex justify-content-between align-items-center mt-4">
                                <a href="/projetweb/index1.php?action=show&id=<?= $_GET['publication_id'] ?? '' ?>" class="btn btn-link text-muted px-0 fw-bold">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Post Comment</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Card END -->
            </div>
        </div>
    </div>
</section>

</main>

<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
