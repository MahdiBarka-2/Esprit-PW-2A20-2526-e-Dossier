<?php 
require_once '../../CONTROLLER/LanguageController.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['pending_2fa_user_id'])) {
    header("Location: sign-in.php");
    exit();
}

require_once '../../CONTROLLER/UserController.php';
$pendingUser = findUserById($_SESSION['pending_2fa_user_id']);
$emailParts = explode('@', $pendingUser['email']);
$maskedEmail = substr($emailParts[0], 0, 2) . '******@' . $emailParts[1];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>
<head>
    <title>e_dossier - 2FA Verification</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <style>
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 5px;
            border-radius: 10px;
            border: 2px solid #ddd;
        }
        .otp-input:focus {
            border-color: #066ac9;
            outline: none;
        }
    </style>
</head>
<body class="bg-light">
    <main>
        <section class="vh-100 d-flex align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="card shadow-lg border-0 rounded-4 p-4 p-sm-5">
                            <div class="text-center mb-4">
                                <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items:center; justify-content:center; font-size: 2rem;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h2 class="h3">Two-Factor Authentication</h2>
                                <p class="text-muted">Enter the 6-digit code sent to <span class="fw-bold text-dark"><?php echo $maskedEmail; ?></span></p>
                            </div>

                            <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger py-2 small text-center">
                                    Invalid or expired code. Please try again.
                                </div>
                            <?php endif; ?>

                            <form action="../../CONTROLLER/UserController.php?action=verify_2fa" method="POST">
                                <div class="d-flex justify-content-center mb-4">
                                    <input type="text" name="code" class="form-control text-center fw-bold" maxlength="6" placeholder="000000" style="font-size: 2rem; letter-spacing: 10px;" required autofocus>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">Verify & Login</button>
                                
                                <div class="text-center mt-4">
                                    <p class="mb-0">Didn't receive a code? <a href="sign-in.php" class="text-primary fw-bold">Try again</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
