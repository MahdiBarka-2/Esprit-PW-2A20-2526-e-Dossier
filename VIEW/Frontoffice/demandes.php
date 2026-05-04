<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageController.php';
require_once '../../CONTROLLER/demandeC.php';
require_once '../../CONTROLLER/categorieC.php';

$dc         = new demandeC();
$cc         = new categorieC();
$demandes   = $dc->listeDemandes()->fetchAll();
$categories = $cc->listeCategories()->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <title>Portail des Demandes – E-Dossier</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
    
    <!-- Dark mode script -->
    <script>
        const getStoredTheme = () => localStorage.getItem('theme')
        const setStoredTheme = theme => localStorage.setItem('theme', theme)
        const getPreferredTheme = () => {
            const storedTheme = getStoredTheme()
            if (storedTheme) return storedTheme
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        }
        const setTheme = function (theme) {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme === 'auto' ? 'light' : theme)
            }
        }
        setTheme(getPreferredTheme())

        window.addEventListener('DOMContentLoaded', () => {
            const showActiveTheme = theme => {
                document.querySelectorAll('[data-bs-theme-value]').forEach(el => {
                    el.classList.remove('active')
                })
                const activeBtn = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                if (activeBtn) activeBtn.classList.add('active')
            }

            document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    setStoredTheme(theme)
                    setTheme(theme)
                    showActiveTheme(theme)
                })
            })
            showActiveTheme(getPreferredTheme())
        })
    </script>
</head>
<body>

<!-- ===================== HEADER ===================== -->
<header class="navbar-light py-3 border-bottom shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../../assets/images/e_dossier.png" alt="logo" style="height: 60px;">
                <span class="ms-2 fw-bold text-primary brand-text" style="font-size: 1.5rem;">E-Dossier</span>
            </a>
            <div class="d-flex align-items-center">
                <nav class="navbar-expand-lg">
                    <ul class="nav">
                        <li class="nav-item"><a class="nav-link fw-bold nav-link-custom" href="index.php"><?php echo __('home'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Boffice/index.php"><?php echo __('dashboard'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/Events.php"><?php echo __('Events'); ?></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/demandes.php"><?php echo __('demand'); ?></a></li>
                    </ul>
                </nav>

                <style>
                    .nav-link-custom {
                        color: var(--bs-body-color);
                        transition: color 0.3s ease;
                    }
                    [data-bs-theme='light'] .nav-link-custom {
                        color: #0b0a12 !important;
                    }
                    [data-bs-theme='dark'] .nav-link-custom,
                    [data-bs-theme='dark'] h1, 
                    [data-bs-theme='dark'] h2, 
                    [data-bs-theme='dark'] h3, 
                    [data-bs-theme='dark'] h4, 
                    [data-bs-theme='dark'] h5, 
                    [data-bs-theme='dark'] h6,
                    [data-bs-theme='dark'] p,
                    [data-bs-theme='dark'] .lead,
                    [data-bs-theme='dark'] label {
                        color: #f5f5dc !important;
                    }
                    .nav-link-custom:hover {
                        color: var(--bs-primary) !important;
                    }
                    [data-bs-theme='light'] .brand-text {
                        color: #0b0a12 !important;
                    }
                    [data-bs-theme='dark'] .brand-text {
                        color: #f5f5dc !important;
                    }
                </style>

                <!-- Language Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm mb-0 px-2" id="languageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> <?php echo strtoupper($lang); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item <?php echo $lang === 'en' ? 'active' : ''; ?>" href="?lang=en">EN English</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'fr' ? 'active' : ''; ?>" href="?lang=fr">FR French</a></li>
                        <li><a class="dropdown-item <?php echo $lang === 'ar' ? 'active' : ''; ?>" href="?lang=ar">AR Arabic</a></li>
                    </ul>
                </div>

                <!-- Theme Switcher -->
                <div class="dropdown ms-3">
                    <button class="btn btn-light btn-sm lh-0 mb-0" id="bd-theme" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-circle-half theme-icon-active"></i>
                    </button>
                    <ul class="dropdown-menu min-w-auto dropdown-menu-end shadow" aria-labelledby="bd-theme">
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"><i class="bi bi-brightness-high-fill me-2"></i>Light</button></li>
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"><i class="bi bi-moon-stars-fill me-2"></i>Dark</button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"><i class="bi bi-circle-half me-2"></i>Auto</button></li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Profile dropdown START -->
                    <div class="dropdown ms-3">
                        <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button" data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            $profile_img = (isset($_SESSION['profile_image_url']) && !empty($_SESSION['profile_image_url'])) 
                                            ? $_SESSION['profile_image_url'] 
                                            : '../../assets/images/avatar/01.jpg';
                            ?>
                            <img class="avatar-img rounded-circle" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid var(--bs-primary);">
                        </a>
                        <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                            <li class="px-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <img class="avatar-img rounded-circle shadow" src="<?php echo $profile_img; ?>" alt="avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <a class="h6 mt-2 mt-sm-0" href="#"><?php echo $_SESSION['name'] ?? 'User'; ?></a>
                                        <p class="small m-0 text-truncate" style="max-width: 150px;"><?php echo $_SESSION['email'] ?? ''; ?></p>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../Boffice/account-settings.php"><i class="bi bi-person fa-fw me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="../Boffice/settings.php"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
                            <li><a class="dropdown-item bg-danger-soft-hover" href="../Boffice/logout.php"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="ms-3">
                        <a href="../Boffice/sign-in.php" class="btn btn-primary btn-sm mb-0 px-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%;" title="<?php echo __('sign_in'); ?>">
                            <i class="fa-solid fa-user fs-5 text-white"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
<!-- ===================== HEADER END ===================== -->

<div id="view-front">
  <div class="content">

    <div class="d-flex flex-column align-items-center text-center mb-5 mt-4">
      <div class="d-flex align-items-center justify-content-center mb-4">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--bs-primary)" stroke-width="2" class="me-3">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <h2 class="mb-0 fw-bold text-primary display-5">Portail des Demandes</h2>
      </div>
      <div>
        <button class="btn btn-primary btn-lg px-5 py-3 text-white fw-bold shadow-sm" style="font-size: 1.25rem; border-radius: 12px;" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-circle me-2"></i> Faire une demande
        </button>
      </div>
    </div>

<div class="container py-5">

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Steps -->
    <div class="row g-4 mb-5">
        <div class="col-12"><h5 class="fw-bold mb-3"><i class="bi bi-signpost-2 me-2 text-primary"></i>Comment soumettre ?</h5></div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-top:3px solid #376cbe!important;">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-tags fs-3 text-primary"></i></div>
                <h6 class="fw-bold mb-1">1. Choisir une catégorie</h6>
                <p class="text-muted small mb-0">Logement, Bourse, Certificat…</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-top:3px solid #ffc107!important;">
                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-paperclip fs-3 text-warning"></i></div>
                <h6 class="fw-bold mb-1">2. Joindre vos documents</h6>
                <p class="text-muted small mb-0">CIN, certificat d'inscription…</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4" style="border-top:3px solid #198754!important;">
                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width:56px;height:56px;"><i class="bi bi-send-check fs-3 text-success"></i></div>
                <h6 class="fw-bold mb-1">3. Soumettre</h6>
                <p class="text-muted small mb-0">Suivez votre statut en temps réel.</p>
            </div>
        </div>
    </div>

    <!-- Demandes list -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Mes demandes</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjouter">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle
        </button>
    </div>

    <?php if (empty($demandes)): ?>
        <div class="card border-0 shadow-sm text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <h5 class="fw-bold mt-3 mb-2">Aucune demande</h5>
            <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#modalAjouter">
                <i class="bi bi-plus-circle me-2"></i>Première demande
            </button>
        </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($demandes as $d): ?>
        <?php
        $badgeSoft = match($d['statut']) { 'approuvee'=>'bg-success bg-opacity-10 text-success','rejetee'=>'bg-danger bg-opacity-10 text-danger',default=>'bg-warning bg-opacity-10 text-warning'};
        $icon = match($d['statut']) { 'approuvee'=>'bi-check-circle-fill text-success','rejetee'=>'bi-x-circle-fill text-danger',default=>'bi-clock-fill text-warning'};
        $label = match($d['statut']) { 'approuvee'=>'Approuvée','rejetee'=>'Rejetée',default=>'En attente'};
        $pct = match($d['statut']) { 'approuvee'=>100,'rejetee'=>100,default=>50};
        $pcolor = match($d['statut']) { 'approuvee'=>'bg-success','rejetee'=>'bg-danger',default=>'bg-warning'};
        $canEdit = $d['statut'] === 'en_attente';
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="bi bi-tag me-1"></i><?= htmlspecialchars($d['categorie_nom']) ?></span>
                        <span class="badge <?= $badgeSoft ?> px-3 py-2 rounded-pill"><i class="bi <?= $icon ?> me-1"></i><?= $label ?></span>
                    </div>
                    <h6 class="fw-bold mb-1"><i class="bi bi-person-circle me-2 text-muted"></i><?= htmlspecialchars($d['utilisateur']) ?></h6>
                    <p class="text-muted small mb-1"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($d['email']) ?></p>
                    <p class="text-muted small mb-0"><i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($d['created_at'])) ?></p>
                    <div class="p-2 bg-light rounded-2 mb-0 mt-2">
                        <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2; height: 2.4em;">
                            <i class="bi bi-chat-left-text me-1"></i><?= htmlspecialchars($d['description'] ?? 'Aucune description.') ?>
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-transparent px-4 pb-3 pt-2">
                    <div class="progress mb-3" style="height:4px;">
                        <div class="progress-bar <?= $pcolor ?>" style="width:<?= $pct ?>%"></div>
                    </div>
                    <?php if ($canEdit): ?>
                    <button class="btn btn-outline-primary btn-sm w-100"
                        onclick="openEditModal(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['utilisateur'])) ?>', '<?= htmlspecialchars(addslashes($d['email'])) ?>', <?= $d['categorie_id'] ?>, '<?= htmlspecialchars(addslashes($d['description'] ?? '')) ?>')">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </button>
                    <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm w-100" disabled><i class="bi bi-lock me-1"></i>Non modifiable</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </div>
</div>

<!-- Modal Ajouter -->
<div class="modal fade" id="modalAjouter" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#1d3461,#376cbe);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-file-earmark-plus me-2"></i>Nouvelle Demande</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAjouter" action="../../CONTROLLER/AjouterDemande.php" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" name="source" value="utilisateur">
                <input type="hidden" name="redirect" value="frontoffice_new">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="utilisateur" id="val-nom" class="form-control" placeholder="Ahmed Ben Ali" required>
                            <small class="text-danger d-none" id="error-nom">Veuillez entrer votre nom complet (min 3 carac.).</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="val-email" class="form-control" placeholder="exemple@email.com" required>
                            <small class="text-danger d-none" id="error-email">Veuillez entrer une adresse email valide.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" id="selectCategorie" class="form-select" required onchange="updateTemplates()">
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger d-none" id="error-cat">Veuillez choisir une catégorie.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Document <span class="text-danger">*</span></label>
                            <input type="file" name="document" id="val-doc" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-danger d-none" id="error-doc">Veuillez joindre un document justificatif.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <div id="templateContainer" class="mb-2 d-none">
                                <small class="text-muted d-block mb-1">Modèle suggéré par l'IA :</small>
                                <div id="templateButtons"></div>
                            </div>
                            <textarea id="descAjouter" name="description" class="form-control" rows="3" placeholder="Détaillez votre demande..."></textarea>
                            <small class="text-danger d-none" id="error-desc">Veuillez donner un peu plus de détails (min 10 carac.).</small>
                        </div>


                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send me-2"></i>Soumettre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="modalModifier" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#1d5461,#37a0be);">
                <div class="text-white py-2">
                    <h5 class="modal-title fw-bold mb-1"><i class="bi bi-pencil-square me-2"></i>Modifier la Demande</h5>
                    <p class="small opacity-75 mb-0">Seules les demandes en attente peuvent être modifiées</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../../CONTROLLER/ModifierDemande.php" method="POST" novalidate>
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="redirect" value="frontoffice_new">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="utilisateur" id="edit_utilisateur" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                            <select name="categorie_id" id="edit_categorie_id" class="form-select" required>
                                <option value="">-- Sélectionner --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-info border-0 py-2 px-3 small mb-0">
                                <i class="bi bi-info-circle me-1"></i>Le document ne peut pas être modifié.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, utilisateur, email, categorieId, description) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_utilisateur').value = utilisateur;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_categorie_id').value = categorieId;
    document.getElementById('edit_description').value = description;
    new bootstrap.Modal(document.getElementById('modalModifier')).show();
}

// Validation du formulaire avec Popup
document.getElementById('formAjouter').addEventListener('submit', function(e) {
    let isValid = true;
    const errors = this.querySelectorAll('.text-danger');
    const inputs = this.querySelectorAll('.form-control, .form-select');
    
    // Reset
    errors.forEach(el => el.classList.add('d-none'));
    inputs.forEach(el => el.classList.remove('is-invalid'));

    const nom = document.getElementById('val-nom').value.trim();
    const email = document.getElementById('val-email').value.trim();
    const cat = document.getElementById('selectCategorie').value;
    const doc = document.getElementById('val-doc').files.length;
    const desc = document.getElementById('descAjouter').value.trim();

    if (nom === "" || nom.length < 3) {
        document.getElementById('error-nom').classList.remove('d-none');
        document.getElementById('val-nom').classList.add('is-invalid');
        isValid = false;
    }

    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        document.getElementById('error-email').classList.remove('d-none');
        document.getElementById('val-email').classList.add('is-invalid');
        isValid = false;
    }

    if (cat === "") {
        document.getElementById('error-cat').classList.remove('d-none');
        document.getElementById('selectCategorie').classList.add('is-invalid');
        isValid = false;
    }

    if (doc === 0) {
        document.getElementById('error-doc').classList.remove('d-none');
        document.getElementById('val-doc').classList.add('is-invalid');
        isValid = false;
    }

    if (desc.length < 10) {
        document.getElementById('error-desc').classList.remove('d-none');
        document.getElementById('descAjouter').classList.add('is-invalid');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});



function insertTemplate(text) {
    document.getElementById('descAjouter').value = text;
}

function updateTemplates() {
    const select = document.getElementById('selectCategorie');
    const selectedOption = select.options[select.selectedIndex];
    const catName = selectedOption.getAttribute('data-name') || '';
    const container = document.getElementById('templateContainer');
    const buttonsDiv = document.getElementById('templateButtons');
    
    buttonsDiv.innerHTML = '';
    
    const templates = {
        'Logement': 'Je sollicite l\'attribution d\'un logement social. Ma situation actuelle est : [DÉTAILS]. Revenu mensuel : [MONTANT].',
        'Bourse d\'Études': 'Demande de bourse d\'études pour l\'année universitaire. Inscrit en [FILIÈRE] à [ÉTABLISSEMENT].',
        'Carte Municipale': 'Je souhaite obtenir une carte municipale pour accéder aux services de la ville. Photo jointe.',
        'Certificat Administratif': 'Demande de certificat administratif concernant [OBJET]. J\'en ai besoin pour [RAISON].',
        'Extrait de Naissance': 'Je souhaite obtenir un extrait de naissance. Né le [DATE] à [LIEU]. Père: [NOM], Mère: [NOM].',
        'Certificat de Résidence': 'Demande de certificat de résidence. J\'habite au [ADRESSE] à [VILLE] depuis le [DATE].',
        'Extrait d\'acte de Marriage': 'Je demande un extrait d\'acte de mariage. Mariage célébré le [DATE] à [LIEU] entre [NOM1] et [NOM2].',
        'Acte de Décès': 'Demande d\'acte de décès pour [NOM_DEFUNT], décédé le [DATE] à [LIEU].',
        'Passeport / CIN': 'Demande de renouvellement de [DOCUMENT]. Mon ancien numéro est [NUMERO].'
    };

    if (templates[catName]) {
        container.classList.remove('d-none');
        // Use backticks and replace single quotes to avoid JS errors
        const safeText = templates[catName].replace(/'/g, "\\'");
        buttonsDiv.innerHTML = `<button type="button" class="btn btn-sm btn-primary py-1 px-3" onclick="insertTemplate('${safeText}')"><i class="bi bi-magic me-1"></i> Utiliser le modèle ${catName}</button>`;
    } else {
        container.classList.add('d-none');
    }
}


</script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/overlay-scrollbar/js/overlayscrollbars.min.js"></script>
<script src="../../assets/js/functions.js"></script>
</body>
</html>
