<?php 
// VIEW/Frontoffice/publications/show.php - Ported from projetweb
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$root = dirname(dirname(dirname(__DIR__)));
$controllerPath = $root . '/CONTROLLER/LanguageController.php';
if (file_exists($controllerPath)) {
    require_once $controllerPath;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang ?? 'en'; ?>" <?php echo (($lang ?? '') === 'ar' ? 'dir="rtl"' : ''); ?>>
<head>
    <title>e_dossier - <?= htmlspecialchars($publication['titre'] ?? 'Document') ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="/integration/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/integration/assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/integration/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const storedTheme = localStorage.getItem('theme')
        const getPreferredTheme = () => {
            if (storedTheme) return storedTheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }
        const setTheme = function (theme) {
            if (theme === 'auto') {
                document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }
        setTheme(getPreferredTheme())
    </script>
</head>
<body>
    <header class="navbar-light py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="/integration/VIEW/Frontoffice/index.php">
                <img src="/integration/assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="/integration/VIEW/Frontoffice/index.php"><?php echo __('home'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="/integration/VIEW/Boffice/index.php"><?php echo __('dashboard'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="/integration/VIEW/Frontoffice/Events.php"><?php echo __('Events'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="/integration/VIEW/Frontoffice/demandes.php"><?php echo __('demand'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="/integration/index1.php"><?php echo __('posts'); ?></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

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
            <a href="/integration/index1.php" class="btn btn-sm btn-outline-light rounded-pill px-4 py-2 fw-semibold transition-all hover-white"><i class="bi bi-arrow-left me-2"></i>Return to Repository</a>
            <div class="d-flex gap-2">
                <?php 
                    $isBookmarked = isset($_SESSION['saved_publications']) && in_array($publication['id'], $_SESSION['saved_publications']);
                ?>
                <button class="btn btn-sm btn-light rounded-pill px-3 py-2 fw-semibold shadow-sm" onclick="toggleBookmark(this, <?= $publication['id'] ?>)">
                    <i class="bi <?= $isBookmarked ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-secondary' ?> me-2"></i><?= $isBookmarked ? 'Saved' : 'Save Document' ?>
                </button>

                <a href="/integration/index1.php?action=download&id=<?= $publication['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-2 fw-semibold shadow-sm"><i class="bi bi-file-earmark-pdf me-2"></i>Download PDF</a>
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

            fetch(`/integration/index1.php?action=toggleSave&id=${id}`)
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
                                <span class="text-white fw-semibold d-block"><i class="bi bi-calendar-event me-2 text-primary opacity-75"></i><?= date('M j, Y \a\t g:i A', strtotime($publication['date'])) ?></span>
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
                        <?php if(!empty($publication['document'])): ?>
                            <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 rounded-4 p-4 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-lg bg-primary text-white rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="bi bi-file-earmark-pdf fs-4"></i></div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Official Document Attached</h6>
                                        <p class="small text-secondary mb-0">Download the original file for verification.</p>
                                    </div>
                                </div>
                                <a href="/integration/uploads/<?= $publication['document'] ?>" target="_blank" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-cloud-download me-2"></i>Download File
                                </a>
                            </div>

                            <!-- Document Preview Section -->
                            <?php 
                                $ext = strtolower(pathinfo($publication['document'], PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $isPDF = ($ext === 'pdf');
                            ?>

                            <?php if($isImage || $isPDF): ?>
                                <div class="mb-5 rounded-4 overflow-hidden border shadow-sm">
                                    <?php if($isImage): ?>
                                        <img src="/integration/uploads/<?= $publication['document'] ?>" class="img-fluid w-100" alt="Document Preview">
                                    <?php elseif($isPDF): ?>
                                        <div class="ratio ratio-16x9" style="min-height: 600px;">
                                            <iframe src="/integration/uploads/<?= $publication['document'] ?>" title="PDF Preview"></iframe>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <p class="fs-5 mb-0" style="white-space: pre-line; line-height: 1.9; text-align: justify; letter-spacing: 0.2px; color: var(--bs-body-color);">
                            <?= htmlspecialchars($publication['contenu']) ?>
                        </p>
                    </div>
                </div>

                <!-- Add Comment Form Moved Back to Top -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mt-5">
                    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">Join the Discussion</h5>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="/integration/index1.php?action=addComment" method="POST">
                                <input type="hidden" name="publication_id" value="<?= $publication['id'] ?>">
                                <input type="hidden" name="utilisateur" value="<?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>">
                                
                                <div class="d-flex align-items-center mb-4">
                                    <div class="avatar avatar-sm me-3">
                                        <div class="avatar-img rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold">
                                            <?= strtoupper(substr($_SESSION['name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-0 small text-muted">Posting as</p>
                                        <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></h6>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase" style="letter-spacing: 1px;">Your Message</label>
                                    <textarea name="contenu" class="form-control bg-light border-0 p-4 rounded-4 shadow-none" rows="5" placeholder="Share your thoughts or ask a question about this official document..." required style="resize: none;"></textarea>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Your comment will be visible to all citizens.</p>
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm transition-all hover-translate-up">
                                        <i class="bi bi-send-fill me-2"></i>Post Comment
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-4 bg-light rounded-4 border border-dashed border-2">
                                <div class="icon-lg bg-white rounded-circle shadow-sm mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-lock-fill text-muted fs-4"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Sign in to Participate</h6>
                                <p class="text-muted small mb-3">You must be logged in to post feedback on official publications.</p>
                                <a href="/integration/VIEW/Boffice/sign-in.php" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Log In Now
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom mt-5">
                    <h3 class="mb-0 fw-bold"><i class="bi bi-chat-square-text me-2 text-primary"></i>Citizen Discussion <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-circle ms-2 fs-6"><?= count($comments) ?></span></h3>
                </div>

                <div class="vstack gap-4 mt-4">
                    <?php if(count($comments) > 0): ?>
                        <?php foreach($comments as $c): ?>
                        <div class="d-flex p-4 rounded-4 shadow-sm bg-body-tertiary border border-secondary border-opacity-10 transition-all hover-shadow">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 me-3 me-md-4">
                                <div class="avatar avatar-md">
                                    <div class="avatar-img rounded-circle border border-2 border-secondary border-opacity-25 shadow-sm d-flex justify-content-center align-items-center" style="background: rgba(var(--bs-primary-rgb), 0.1);">
                                        <b class="text-primary fs-5"><?= strtoupper(substr($c['utilisateur'] ?? 'C', 0, 1)) ?></b>
                                    </div>
                                </div>
                            </div>
                            <!-- Comment body -->
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1 fw-bold fs-5"><?= htmlspecialchars($c['utilisateur'] ?? 'Citizen') ?></h6>
                                        <span class="text-muted small"><i class="bi bi-clock me-1"></i><?= date('M j, Y \a\t g:i A', strtotime($c['date'])) ?></span>
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

</main>

<style>
    .dropdown-toggle.no-caret::after {
        display: none;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .dropdown-menu {
        z-index: 1050 !important;
    }
</style>

<footer class="bg-dark text-white py-5">
    <div class="container text-center">
        <p class="mb-0 opacity-50">&copy; <?= date('Y') ?> E-Dossier Administrative Portal. All Rights Reserved.</p>
    </div>
</footer>

<script src="/integration/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDeleteFront(id, pubId) {
    Swal.fire({
        title: 'Remove Comment?',
        text: "This will permanently delete your feedback from the discussion.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        background: '#fff',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/integration/index1.php?action=deleteComment&id=${id}&publication_id=${pubId}`;
        }
    });
}
</script>
</body>
</html>
