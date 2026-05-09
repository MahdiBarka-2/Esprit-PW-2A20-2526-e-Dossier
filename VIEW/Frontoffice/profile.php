<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../../CONTROLLER/LanguageCONTROLLER.php';
require_once '../../CONTROLLER/UserCONTROLLER.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Boffice/sign-in.php");
    exit();
}

$user = findUserById($_SESSION['user_id']);
if (!$user) {
    echo "User not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <title>e_dossier - My Profile</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
    
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-blur: 15px;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #f5f5dc;
            min-height: 100vh;
        }

        .profile-container {
            padding: 80px 0;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .profile-avatar-container {
            position: relative;
            width: 180px;
            height: 180px;
            margin: -130px auto 30px;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid var(--glass-border);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .badge-role {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--bs-primary);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        .info-label {
            color: rgba(245, 245, 220, 0.6);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .gradient-text {
            background: linear-gradient(90deg, #376cbe, #6c5ce7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-premium {
            background: linear-gradient(90deg, #376cbe, #6c5ce7);
            border: none;
            color: white !important;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
        }

        .cv-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px dashed var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-link-custom {
            color: #f5f5dc !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-avatar-container {
                width: 140px;
                height: 140px;
                margin-top: -110px;
            }
        }
    </style>
</head>

<body>
    <main class="profile-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="glass-card mt-5">
                        <div class="profile-avatar-container">
                            <?php 
                            $profile_img = (!empty($user['profile_image_url'])) 
                                            ? $user['profile_image_url'] 
                                            : '../../assets/images/avatar/01.jpg';
                            ?>
                            <img src="<?php echo $profile_img; ?>" class="profile-avatar" alt="User Avatar">
                            <span class="badge-role"><?php echo ucfirst($user['role']); ?></span>
                        </div>

                        <div class="text-center mb-5">
                            <h2 class="display-6 fw-bold mb-1 text-white"><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p class="opacity-75 mb-0"><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="row g-4 mt-2">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div>
                                    <p class="info-label">Full Name</p>
                                    <p class="info-value"><?php echo htmlspecialchars($user['name']); ?></p>
                                </div>
                                <div>
                                    <p class="info-label">Phone Number</p>
                                    <p class="info-value"><?php echo htmlspecialchars($user['phone'] ?: 'Not Provided'); ?></p>
                                </div>
                                <div>
                                    <p class="info-label">Account Status</p>
                                    <p class="info-value">
                                        <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?> bg-opacity-10 <?php echo $user['status'] === 'active' ? 'text-success' : 'text-danger'; ?> px-3">
                                            <?php echo ucfirst($user['status']); ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div>
                                    <p class="info-label">Age</p>
                                    <p class="info-value"><?php echo htmlspecialchars($user['age'] ?: 'N/A'); ?> Years Old</p>
                                </div>
                                <div>
                                    <p class="info-label">Member Since</p>
                                    <p class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                                </div>
                                <div>
                                    <p class="info-label">Two-Factor Auth</p>
                                    <p class="info-value">Enabled <i class="bi bi-shield-check text-success ms-1"></i></p>
                                </div>
                            </div>
                        </div>

                        <!-- CV Section -->
                        <div class="mt-4">
                            <p class="info-label">Documents & CV</p>
                            <div class="cv-card">
                                <div class="d-flex align-items-center">
                                    <div class="icon-lg bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-file-earmark-pdf fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-white">Curriculum Vitae</h6>
                                        <p class="small opacity-50 mb-0">Professional Background</p>
                                    </div>
                                </div>
                                <?php if (!empty($user['cv_file_path'])): ?>
                                    <a href="<?php echo $user['cv_file_path']; ?>" target="_blank" class="btn btn-sm btn-outline-light">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">No document uploaded</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mt-5 pt-3 border-top border-white border-opacity-10">
                            <a href="index.php" class="btn btn-premium flex-grow-1">
                                <i class="bi bi-house-door me-2"></i>Back to Home
                            </a>
                            <a href="../Boffice/logout.php" class="btn btn-outline-danger px-4">
                                <i class="bi bi-power me-2"></i>Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5 border-top border-white border-opacity-10">
        <div class="container text-center">
            <p class="mb-0 opacity-50 small">&copy; 2026 e_dossier. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
