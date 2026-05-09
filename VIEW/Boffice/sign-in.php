<?php 
require_once '../../CONTROLLER/LanguageController.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, skip the sign-in page and go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
	<title>e_dossier - <?php echo __('sign_in'); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
</head>

<body>
	<main>
		<section class="vh-xxl-100">
			<div class="container h-100 d-flex px-0 px-sm-4">
				<div class="row justify-content-center align-items-center m-auto">
					<div class="col-12">
						<div class="bg-mode shadow rounded-3 overflow-hidden">
							<div class="row g-0">
								<!-- Vector Image -->
								<div class="col-lg-6 d-md-flex align-items-center order-2 order-lg-1 bg-primary">
									<div class="p-3 p-lg-5">
										<img src="../../assets/images/element/signin.svg" alt="">
									</div>
									<div class="vr opacity-1 d-none d-lg-block"></div>
								</div>

								<!-- Information -->
								<div class="col-lg-6 order-1">
									<div class="p-4 p-sm-7">
										<a href="index.php">
											<img class="h-50px mb-4" src="../../assets/images/e_dossier.png" alt="logo">
											<span class="h4 ms-2 fw-bold text-primary align-middle">E-Dossier</span>
										</a>
										<h1 class="mb-2 h3"><?php echo __('welcome_back'); ?></h1>
										<p class="mb-0">New here?<a href="../Frontoffice/sign-up.php"> <?php echo __('sign_up'); ?></a></p>

										<?php if (isset($_GET['error'])): ?>
											<div class="alert alert-danger mt-3 py-2 small">
												Invalid credentials. Please try again.
											</div>
										<?php endif; ?>

										<form class="mt-4 text-start" method="POST" action="../../CONTROLLER/UserController.php?action=login" onsubmit="return validateSignInForm()">
											<div class="mb-3">
												<label class="form-label"><?php echo __('email_label'); ?></label>
												<input type="email" name="email" class="form-control">
											</div>
											<div class="mb-3 position-relative">
												<label class="form-label"><?php echo __('password_label'); ?></label>
												<input class="form-control fakepassword" name="password" type="password" id="psw-input">
												<span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
													<i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
												</span>
											</div>
											<div class="mb-3 d-sm-flex justify-content-between">
												<div>
													<input type="checkbox" class="form-check-input" id="rememberCheck">
													<label class="form-check-label" for="rememberCheck">Remember me?</label>
												</div>
												<a href="forgot-password.html">Forgot password?</a>
											</div>
											<div><button type="submit" class="btn btn-primary w-100 mb-0"><?php echo __('login_button'); ?></button></div>

											<div class="position-relative my-4">
												<hr>
												<p class="small bg-mode position-absolute top-50 start-50 translate-middle px-2">Or sign in with</p>
											</div>

											<!-- Google and facebook button -->
											<div class="vstack gap-3">
												<a href="../../CONTROLLER/SocialAuthController.php?platform=google" class="btn btn-light mb-0"><i
														class="fab fa-fw fa-google text-google-icon me-2"></i>Sign in
													with Google</a>
												<a href="../../CONTROLLER/SocialAuthController.php?platform=facebook" class="btn btn-light mb-0"><i
														class="fab fa-fw fa-facebook-f text-facebook me-2"></i>Sign in
													with Facebook</a>
                                                
                                                <button type="button" id="face-id-btn" class="btn btn-dark mb-0">
                                                    <i class="fas fa-user-shield me-2"></i>Sign in with Face ID
                                                </button>
											</div>

                                            <!-- Face ID UI Overlay -->
                                            <div id="face-id-container" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:9999; flex-direction:column; align-items:center; justify-content:center;">
                                                <div style="position:relative; width:320px; height:240px; border-radius:15px; overflow:hidden; border:4px solid #066ac9;">
                                                    <video id="face-video" width="320" height="240" autoplay muted style="transform: scaleX(-1);"></video>
                                                    <canvas id="face-canvas" style="position:absolute; top:0; left:0;"></canvas>
                                                </div>
                                                <p id="face-status" class="text-white mt-3">Initializing camera...</p>
                                                <button type="button" onclick="closeFaceID()" class="btn btn-outline-light btn-sm mt-2">Cancel</button>
                                            </div>

                                            <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
                                            <script>
                                                const faceBtn = document.getElementById('face-id-btn');
                                                const faceContainer = document.getElementById('face-id-container');
                                                const faceVideo = document.getElementById('face-video');
                                                const faceStatus = document.getElementById('face-status');
                                                let modelsLoaded = false;

                                                async function loadFaceModels() {
                                                    const MODEL_URL = 'https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights';
                                                    await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
                                                    await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                                                    await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                                                    modelsLoaded = true;
                                                }

                                                faceBtn.addEventListener('click', async () => {
                                                    faceContainer.style.display = 'flex';
                                                    if (!modelsLoaded) {
                                                        faceStatus.innerText = 'Loading AI Models...';
                                                        await loadFaceModels();
                                                    }
                                                    startFaceID();
                                                });

                                                async function startFaceID() {
                                                    try {
                                                        const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                                                        faceVideo.srcObject = stream;
                                                        faceStatus.innerText = 'Scanning face...';
                                                        
                                                        // Wait for video to play
                                                        faceVideo.onplay = async () => {
                                                            const interval = setInterval(async () => {
                                                                const detection = await faceapi.detectSingleFace(faceVideo)
                                                                    .withFaceLandmarks()
                                                                    .withFaceDescriptor();
                                                                
                                                                if (detection) {
                                                                    faceStatus.innerText = 'Verifying...';
                                                                    const descriptor = Array.from(detection.descriptor);
                                                                    
                                                                    const response = await fetch('../../CONTROLLER/FacialRecognitionController.php?action=login', {
                                                                        method: 'POST',
                                                                        body: JSON.stringify({ descriptor })
                                                                    });
                                                                    const result = await response.json();
                                                                    
                                                                    if (result.status === 'success') {
                                                                        clearInterval(interval);
                                                                        faceStatus.innerHTML = `<span class="text-success">Welcome, ${result.user.name}!</span>`;
                                                                        setTimeout(() => window.location.href = 'index.php', 1000);
                                                                    } else {
                                                                        faceStatus.innerText = 'No match found. Keep scanning...';
                                                                    }
                                                                }
                                                            }, 1000);
                                                        };
                                                    } catch (err) {
                                                        faceStatus.innerText = 'Camera access denied.';
                                                    }
                                                }

                                                function closeFaceID() {
                                                    if (faceVideo.srcObject) {
                                                        faceVideo.srcObject.getTracks().forEach(track => track.stop());
                                                    }
                                                    faceContainer.style.display = 'none';
                                                }
                                            </script>
										</form>
										<!-- Form END -->
                                        <script src="../../CONTROLLER/validation.js"></script>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

    <!-- Vocal Assistant -->
    <?php
    require_once '../../CONTROLLER/VoiceController.php';
    echo renderVocalAssistant($lang ?? 'en');

    ?>

	<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>