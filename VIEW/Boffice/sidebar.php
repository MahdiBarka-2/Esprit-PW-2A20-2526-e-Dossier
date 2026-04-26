<!-- Sidebar START -->
<nav class="navbar sidebar navbar-expand-xl navbar-light">
    <!-- Navbar brand for mobile -->
    <div class="d-flex align-items-center d-xl-none py-3 px-4">
        <a class="navbar-brand d-flex align-items-center" href="../Frontoffice/index.php">
            <img class="navbar-brand-item h-40px" src="../../assets/images/e_dossier.png" alt="">
            <span class="ms-1 fw-bold text-primary">E-Dossier</span>
        </a>
    </div>

    <!-- Main Sidebar Content -->
    <div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100" data-bs-backdrop="true" tabindex="-1"
        id="offcanvasSidebar">
        <div class="offcanvas-body sidebar-content d-flex flex-column pt-4">
            
            <!-- Sidebar Brand (Desktop) -->
            <div class="d-none d-xl-flex align-items-center mb-4 ps-2">
                <a class="navbar-brand d-flex align-items-center" href="../Frontoffice/index.php">
                    <img class="navbar-brand-item logo-animated h-50px" src="../../assets/images/e_dossier.png" alt="logo">
                    <span class="ms-1 fw-bold brand-text" style="font-size: 1.5rem;">E-Dossier</span>
                </a>
            </div>

            <style>
                @keyframes pulse-logo {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                    100% { transform: scale(1); }
                }
                .logo-animated {
                    animation: pulse-logo 2s infinite ease-in-out;
                    transition: all 0.3s ease;
                }
                .brand-text {
                    letter-spacing: 0.5px;
                    transition: color 0.3s ease;
                }
                /* Light Mode Color: Dark Navy */
                [data-bs-theme='light'] .brand-text {
                    color: #0b0a12 !important;
                }
                /* Dark Mode Color: Beige/Cream */
                [data-bs-theme='dark'] .brand-text {
                    color: #f5f5dc !important; /* Beige */
                }
            </style>
            <ul class="navbar-nav flex-column" id="navbar-sidebar">
                <?php 
                $currentPage = basename($_SERVER['PHP_SELF']); 
                ?>
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-grid-fill me-2"></i><?php echo __('dashboard'); ?>
                    </a>
                </li>
                
                <li class="nav-item ms-2 my-2 text-primary font-weight-bold"><?php echo __('dossier_management'); ?></li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'posts.php' ? 'active' : ''; ?>" href="posts.php">
                        <i class="bi bi-file-post me-2"></i><?php echo __('posts'); ?>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'materiels.php' ? 'active' : ''; ?>" href="materiels.php">
                        <i class="bi bi-tools me-2"></i><?php echo __('materiels'); ?>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'job-saisonnier.php' ? 'active' : ''; ?>" href="job-saisonnier.php">
                        <i class="bi bi-briefcase me-2"></i><?php echo __('job_saisonnier'); ?>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'demands.php' ? 'active' : ''; ?>" href="demands.php">
                        <i class="bi bi-clipboard-check me-2"></i><?php echo __('demand'); ?>
                    </a>
                </li>

                <li class="nav-item ms-2 my-2 text-primary"><?php echo __('users'); ?></li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'agents.php' ? 'active' : ''; ?>" href="agents.php">
                        <i class="bi bi-people-fill me-2"></i><?php echo __('agents'); ?>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'clients.php' ? 'active' : ''; ?>" href="clients.php">
                        <i class="bi bi-person-heart me-2"></i><?php echo __('guests'); ?>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link <?php echo $currentPage === 'earnings.php' ? 'active' : ''; ?>" href="earnings.php">
                        <i class="bi bi-graph-up-arrow me-2"></i><?php echo __('earnings'); ?>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- Sidebar END -->
