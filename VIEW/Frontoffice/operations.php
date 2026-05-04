<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageController.php'; 
require_once '../../config/Database.php';

// Fetch active operations (missions) - Filter by "En cours" and current date
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM missions WHERE etat = 'En cours' OR (CURDATE() BETWEEN date_debut AND date_fin AND (etat IS NULL OR etat = '')) ORDER BY date_debut DESC");
$active_operations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier - Opérations de la Municipalité</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <!-- Dark mode script -->
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
    </style>
</head>

<body>
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
                        <li class="nav-item"><a class="nav-link nav-link-custom" href="../Frontoffice/operations.php">Opérations</a></li>
                    </ul>
                </nav>

                <!-- Language/Theme switchers removed for brevity, same as index.php -->
            </div>
        </div>
    </header>

    <main>
        <section class="py-5" style="background-color: var(--bs-light);">
            <div class="container text-center pt-5 pb-4">
                <h1 class="display-5 fw-bold text-primary mb-3">Opérations en Cours</h1>
                <p class="lead mb-0">Découvrez les projets actifs de votre municipalité.</p>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <?php if (empty($active_operations)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-info-circle text-muted" style="font-size: 4rem;"></i>
                        <h3 class="mt-4">Aucune opération signalée</h3>
                        <p class="text-muted">Toutes les missions planifiées sont terminées ou n'ont pas encore commencé.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($active_operations as $op): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm border-0 border-start border-4 border-info hover-shadow transition-all">
                                    <div class="card-body p-4 d-flex align-items-center">
                                        <div class="icon-lg bg-info bg-opacity-10 text-info rounded-circle me-3">
                                            <i class="bi bi-cone-striped"></i>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($op['titre']); ?></h5>
                                            <span class="badge bg-info bg-opacity-10 text-info small mt-1">Actif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="py-5 mt-5" style="background-color: #1d3b53; color: white;">
        <div class="container text-center">
            <p class="mb-0 small opacity-50">&copy; 2026 e_dossier. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
