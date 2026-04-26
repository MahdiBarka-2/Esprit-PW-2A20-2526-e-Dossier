<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!-- Top bar START -->
<nav class="navbar top-bar navbar-light py-0 py-xl-3">
    <div class="container-fluid p-0">
        <div class="d-flex align-items-center w-100">
            <!-- Logo for mobile -->
            <div class="d-flex align-items-center d-xl-none">
                <a class="navbar-brand d-flex align-items-center" href="../Frontoffice/index.php">
                    <img class="navbar-brand-item h-40px" src="../../assets/images/e_dossier.png" alt="">
                    <span class="ms-1 fw-bold text-primary" style="font-size: 1.2rem;">E-Dossier</span>
                </a>
            </div>

            <!-- Offcanvas menu button -->
            <div class="navbar-expand-xl sidebar-offcanvas-menu">
                <button class="navbar-toggler me-auto p-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar"
                    aria-expanded="false">
                    <i class="bi bi-list text-primary fa-fw"></i>
                </button>
            </div>

            <!-- Search bar -->
            <div class="navbar-expand-lg ms-auto ms-xl-0">
                <div class="nav my-3 my-xl-0 flex-nowrap align-items-center">
                    <div class="nav-item w-100">
                        <form class="position-relative">
                            <input class="form-control bg-light pe-5" type="search"
                                placeholder="Search dossier, user..." aria-label="Search">
                            <button
                                class="bg-transparent px-2 py-0 border-0 position-absolute top-50 end-0 translate-middle-y"
                                type="submit"><i class="fas fa-search fs-6 text-primary"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right side items -->
            <ul class="nav flex-row align-items-center list-unstyled ms-xl-auto">
                <!-- Language Switcher -->
                <li class="nav-item dropdown ms-3">
                    <button class="btn btn-light btn-sm mb-0 p-2" id="languageDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-globe me-1"></i> <?php echo strtoupper($lang ?? 'en'); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end min-w-auto shadow" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item <?php echo ($lang ?? 'en') === 'en' ? 'active' : ''; ?>" href="?lang=en">EN English</a></li>
                        <li><a class="dropdown-item <?php echo ($lang ?? 'en') === 'fr' ? 'active' : ''; ?>" href="?lang=fr">FR French</a></li>
                        <li><a class="dropdown-item <?php echo ($lang ?? 'en') === 'ar' ? 'active' : ''; ?>" href="?lang=ar">AR Arabic</a></li>
                    </ul>
                </li>

                <!-- Theme Switcher -->
                <li class="nav-item dropdown ms-3">
                    <button class="nav-notification lh-0 btn btn-light p-0 mb-0" id="bd-theme" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-circle-half fa-fw theme-icon-active" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z" />
                            <use href="#"></use>
                        </svg>
                    </button>
                    <ul class="dropdown-menu min-w-auto dropdown-menu-end shadow" aria-labelledby="bd-theme">
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"><i class="bi bi-brightness-high-fill me-2"></i>Light</button></li>
                        <li class="mb-1"><button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"><i class="bi bi-moon-stars-fill me-2"></i>Dark</button></li>
                        <li><button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"><i class="bi bi-circle-half me-2"></i>Auto</button></li>
                    </ul>
                </li>

                <!-- Profile dropdown START -->
                <li class="nav-item ms-3 dropdown">
                    <a class="avatar avatar-sm p-0" href="#" id="profileDropdown" role="button"
                        data-bs-auto-close="outside" data-bs-display="static" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <?php 
                        $profile_img = (isset($_SESSION['profile_image_url']) && !empty($_SESSION['profile_image_url'])) 
                                        ? $_SESSION['profile_image_url'] 
                                        : '../../assets/images/avatar/01.jpg';
                        ?>
                        <img class="avatar-img rounded-circle" src="<?php echo $profile_img; ?>" alt="avatar">
                    </a>
                    <ul class="dropdown-menu dropdown-animation dropdown-menu-end shadow pt-3" aria-labelledby="profileDropdown">
                        <li class="px-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <img class="avatar-img rounded-circle shadow" src="<?php echo $profile_img; ?>" alt="avatar">
                                </div>
                                <div>
                                    <a class="h6 mt-2 mt-sm-0" href="#"><?php echo $_SESSION['name'] ?? 'Guest'; ?></a>
                                    <p class="small m-0 text-truncate" style="max-width: 150px;"><?php echo $_SESSION['email'] ?? 'guest@example.com'; ?></p>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="account-settings.php"><i class="bi bi-person fa-fw me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear fa-fw me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item bg-danger-soft-hover" href="logout.php"><i class="bi bi-power fa-fw me-2"></i>Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Top bar END -->
