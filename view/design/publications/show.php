<?php 
// view/design/publications/show.php - Institutional Design Layer
include __DIR__ . '/../../layouts/header.php'; 
?>

<!-- **************** MAIN CONTENT START **************** -->
<main>

<section class="pt-4 pb-5 bg-dark position-relative" style="padding-bottom: 5rem !important;">
    <!-- Background pattern/gradient -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top right, rgba(var(--bs-primary-rgb), 0.2), transparent 50%);"></div>
    
    <div class="container position-relative z-index-1">
        <?php
            $wordCount = str_word_count(strip_tags($publication['contenu']));
            $readTime = ceil($wordCount / 200);
            $readTimeStr = $readTime > 1 ? $readTime . ' min read' : '< 1 min read';
        ?>
        <!-- Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/projetweb/index1.php" class="btn btn-sm btn-outline-light rounded-pill px-4 py-2 fw-semibold transition-all hover-white"><i class="bi bi-arrow-left me-2"></i>Return to Repository</a>
            <div class="d-flex gap-2">
                <?php 
                    $isBookmarked = isset($_SESSION['saved_publications']) && in_array($publication['id'], $_SESSION['saved_publications']);
                ?>
                <button class="btn btn-sm btn-light rounded-pill px-3 py-2 fw-semibold shadow-sm" onclick="toggleBookmark(this, <?= $publication['id'] ?>)">
                    <i class="bi <?= $isBookmarked ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-secondary' ?> me-2"></i><?= $isBookmarked ? 'Saved' : 'Save Document' ?>
                </button>
                <button class="btn btn-sm btn-light rounded-pill px-4 py-2 fw-semibold shadow-sm" onclick="window.print()"><i class="bi bi-printer me-2 text-primary"></i>Print Document</button>
            </div>
        </div>

        <script>
        function toggleBookmark(btn, id) {
            const icon = btn.querySelector('i');
            const textSpan = btn.childNodes[2]; // Target the text node
            
            if (icon.classList.contains('bi-bookmark')) {
                icon.classList.remove('bi-bookmark', 'text-secondary');
                icon.classList.add('bi-bookmark-fill', 'text-primary');
                if(textSpan) textSpan.textContent = ' Saved';
            } else {
                icon.classList.remove('bi-bookmark-fill', 'text-primary');
                icon.classList.add('bi-bookmark', 'text-secondary');
                if(textSpan) textSpan.textContent = ' Save Document';
            }

            fetch(`/projetweb/index1.php?action=toggleSave&id=${id}`)
                .then(response => response.json())
                .then(data => {
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
                });
        }
        </script>
        
        <div class="row g-4 align-items-end mb-4">
            <!-- Left Side: Title and Badges -->
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-hash me-1"></i>ED-<?= str_pad($publication['id'], 5, '0', STR_PAD_LEFT) ?></span>
                    <?php 
                        $catName = htmlspecialchars($publication['categorie']);
                        $catLower = strtolower(trim($catName));
                        $bClass = 'bg-primary bg-opacity-25 text-white border-primary';
                        if (strpos($catLower, 'law') !== false) $bClass = 'bg-danger bg-opacity-25 text-white border-danger';
                        elseif (strpos($catLower, 'general') !== false) $bClass = 'bg-success bg-opacity-25 text-white border-success';
                        elseif (strpos($catLower, 'announcement') !== false) $bClass = 'bg-warning bg-opacity-25 text-white border-warning';
                        elseif (strpos($catLower, 'report') !== false) $bClass = 'bg-info bg-opacity-25 text-white border-info';
                        else {
                            $palette = ['bg-secondary bg-opacity-25 text-white border-secondary', 'bg-light bg-opacity-25 text-white border-light', 'bg-primary bg-opacity-25 text-white border-primary'];
                            $bClass = $palette[abs(crc32($catLower)) % count($palette)];
                        }
                    ?>
                    <span class="badge <?= $bClass ?> px-3 py-2 rounded-pill border border-opacity-25"><i class="bi bi-tag-fill me-1"></i><?= $catName ?></span>
                    
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25"><i class="bi bi-patch-check-fill me-1"></i>Verified Official</span>
                </div>
                
                <h1 class="display-4 fw-bold text-white mb-0 pe-lg-4" style="line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"><?= htmlspecialchars($publication['titre']) ?></h1>
            </div>
            
            <!-- Right Side: Details Grid -->
            <div class="col-lg-5">
                <div class="card bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 shadow-lg" style="backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-white-50 small d-block mb-1">Author / Issuer</span>
                                <span class="text-white fw-semibold d-block text-truncate" title="<?= htmlspecialchars($publication['auteur']) ?>"><i class="bi bi-person-badge me-2 text-primary opacity-75"></i><?= htmlspecialchars($publication['auteur']) ?></span>
                            </div>
                            <div class="col-6">
                                <span class="text-white-50 small d-block mb-1">Date Published</span>
                                <span class="text-white fw-semibold d-block"><i class="bi bi-calendar-event me-2 text-primary opacity-75"></i><?= date('M j, Y', strtotime($publication['date'])) ?></span>
                            </div>
                            
                            <div class="col-6 mt-3 pt-3 border-top border-white border-opacity-10">
                                <span class="text-white-50 small d-block mb-1">Document Length</span>
                                <span class="text-white fw-semibold d-block"><i class="bi bi-file-text me-2 text-primary opacity-75"></i><?= $wordCount ?> words</span>
                            </div>
                            <div class="col-6 mt-3 pt-3 border-top border-white border-opacity-10">
                                <span class="text-white-50 small d-block mb-1">Est. Read Time</span>
                                <span class="text-white fw-semibold d-block"><i class="bi bi-clock-history me-2 text-primary opacity-75"></i><?= $readTimeStr ?></span>
                            </div>

                            <div class="col-6 mt-3 pt-3 border-top border-white border-opacity-10">
                                <span class="text-white-50 small d-block mb-1">Status</span>
                                <span class="text-success fw-semibold d-block"><i class="bi bi-record-circle-fill me-2" style="font-size: 0.8rem;"></i>Active File</span>
                            </div>
                            <div class="col-6 mt-3 pt-3 border-top border-white border-opacity-10">
                                <span class="text-white-50 small d-block mb-1">Citizen Input</span>
                                <span class="text-white fw-semibold d-block"><i class="bi bi-chat-square-text me-2 text-primary opacity-75"></i><?= count($comments) ?> Responses</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-0 pb-5" style="margin-top: -40px; position: relative; z-index: 2;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <!-- Document Content -->
                <div class="card shadow-lg border-0 rounded-4 mb-5 position-relative overflow-hidden">
                    <!-- Top accent line -->
                    <div class="position-absolute top-0 start-0 w-100 bg-primary" style="height: 4px;"></div>
                    
                    <div class="card-header border-bottom-0 bg-transparent pt-4 pb-0 text-center">
                        <i class="bi bi-quote display-4 text-primary opacity-25"></i>
                    </div>
                    <div class="card-body px-4 px-md-5 pb-5 pt-2">
                        <p class="fs-5 mb-0" style="white-space: pre-line; line-height: 1.9; text-align: justify; letter-spacing: 0.2px; color: var(--bs-body-color);">
                            <?= htmlspecialchars($publication['contenu']) ?>
                        </p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-chat-square-text me-2 text-primary"></i>Citizen Discussion <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle ms-2 fs-6"><?= count($comments) ?></span></h3>
                    <a href="/projetweb/index1.php?action=addComment&publication_id=<?= $publication['id'] ?>" class="btn btn-primary rounded-pill shadow-sm px-4"><i class="bi bi-pencil-square me-2"></i>Post Comment</a>
                </div>
                
                <div class="vstack gap-4">
                    <?php if(count($comments) > 0): ?>
                        <?php foreach($comments as $c): ?>
                        <div class="d-flex p-4 rounded-4 shadow-sm bg-body-tertiary border border-secondary border-opacity-10 transition-all hover-shadow">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 me-3 me-md-4">
                                <div class="avatar avatar-md">
                                    <div class="avatar-img rounded-circle border border-2 border-secondary border-opacity-25 shadow-sm d-flex justify-content-center align-items-center" style="background: rgba(var(--bs-primary-rgb), 0.1);">
                                        <b class="text-primary fs-5"><?= strtoupper(substr($c['auteur'], 0, 1)) ?></b>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment body -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 fw-bold fs-5"><?= htmlspecialchars($c['auteur']) ?></h6>
                                        <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('M j, Y \a\t g:i A', strtotime($c['date'])) ?></span>
                                    </div>
                                    <div class="dropdown">
                                        <a href="#" class="text-muted p-2" id="commentDropdown<?= $c['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="commentDropdown<?= $c['id'] ?>">
                                            <li><a class="dropdown-item text-primary" href="/projetweb/index1.php?action=editComment&id=<?= $c['id'] ?>"><i class="bi bi-pencil me-2"></i>Edit Comment</a></li>
                                            <li><a class="dropdown-item text-danger" href="/projetweb/view/comments/delete.php?id=<?= $c['id'] ?>&publication_id=<?= $publication['id'] ?>" onclick="return confirm('Remove this comment?')"><i class="bi bi-trash me-2"></i>Delete Comment</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="p-3 rounded-3 mt-2" style="background: rgba(var(--bs-body-color-rgb), 0.05);">
                                    <p class="mb-0" style="line-height: 1.6; color: var(--bs-body-color);"><?= nl2br(htmlspecialchars($c['contenu'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5 rounded-4 border border-secondary border-opacity-10" style="background: rgba(var(--bs-body-color-rgb), 0.03); border-style: dashed !important;">
                            <i class="bi bi-chat-dots display-3 text-muted opacity-25 d-block mb-3"></i>
                            <h4 class="fw-bold text-body-emphasis mb-2">No Discussion Yet</h4>
                            <p class="text-muted mb-4 fs-6">Be the first citizen to share your thoughts on this official document.</p>
                            <a href="/projetweb/index1.php?action=addComment&publication_id=<?= $publication['id'] ?>" class="btn btn-outline-primary rounded-pill px-4">Start the Discussion</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</section>

</main>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
