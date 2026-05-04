<?php require_once '../../CONTROLLER/LanguageController.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
	<title>e_dossier - <?php echo __('sign_up'); ?></title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="e_dossier">
	<meta name="description" content="e_dossier - Professional Document Management">

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')
 
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
		}

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
		    var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})

				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})

			showActiveTheme(getPreferredTheme())

			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})

			}
		})
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="../../assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Poppins:wght@400;500;700&amp;display=swap">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">

	<!-- Theme CSS -->
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
							<div class="p-3 p-lg-5 text-center">
								<img src="../../assets/images/element/signin.svg" alt="">
							</div>
							<div class="vr opacity-1 d-none d-lg-block"></div>
						</div>
		
						<!-- Information -->
						<div class="col-lg-6 order-1">
							<div class="p-4 p-sm-6">
								<!-- Logo -->
								<a href="index.php">
									<img class="h-50px mb-4" src="../../assets/images/e_dossier.png" alt="logo">
                                    <span class="h4 ms-2 fw-bold text-primary align-middle">E-Dossier</span>
								</a>
								<!-- Title -->
								<h1 class="mb-2 h3"><?php echo __('sign_up'); ?></h1>
								<p class="mb-0">Already a member?<a href="../Boffice/sign-in.php"> <?php echo __('sign_in'); ?></a></p>
		
                                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'duplicate'): ?>
                                    <div class="alert alert-danger mt-3 py-2 small">
                                        This email is already registered. Please use another or sign in.
                                    </div>
                                <?php endif; ?>

								<!-- Form START -->
								<form class="mt-4 text-start" method="POST" action="../../CONTROLLER/UserController.php?action=add" enctype="multipart/form-data">
                                    <input type="hidden" name="role" value="client">
                                    <input type="hidden" name="status" value="active">
                                    <input type="hidden" name="source" value="frontoffice">

									<!-- Full Name -->
									<div class="mb-3">
										<label class="form-label">Full Name</label>
										<input type="text" name="name" class="form-control" placeholder="John Doe" required>
									</div>
									<!-- Email -->
									<div class="mb-3">
										<label class="form-label">Enter email id</label>
										<input type="email" name="email" class="form-control" placeholder="name@example.com" required>
									</div>
                                    <!-- Phone -->
									<div class="mb-3">
										<label class="form-label">Phone Number</label>
										<input type="text" name="phone" class="form-control" placeholder="+216 ...">
									</div>
									<!-- Password -->
									<div class="mb-3 position-relative">
										<label class="form-label">Enter password</label>
										<input class="form-control fakepassword" name="password" type="password" id="psw-input" required>
										<span class="position-absolute top-50 end-0 translate-middle-y p-0 mt-3">
											<i class="fakepasswordicon fas fa-eye-slash cursor-pointer p-2"></i>
										</span>
									</div>
									<!-- Confirm Password -->
									<div class="mb-3">
										<label class="form-label">Confirm Password</label>
										<input type="password" class="form-control" id="confirm-password" required>
									</div>

                                    <!-- Profile Image -->
                                    <div class="mb-3">
                                        <label class="form-label">Profile Image</label>
                                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                                    </div>

                                    <!-- CV -->
                                    <div class="mb-3">
                                        <label class="form-label">CV Document (PDF)</label>
                                        <input type="file" name="cv_file" class="form-control" accept=".pdf">
                                    </div>

									<!-- Button -->
									<div><button type="submit" class="btn btn-primary w-100 mb-0"><?php echo __('sign_up'); ?></button></div>
		
									<!-- Copyright -->
									<div class="text-primary-hover text-body mt-3 text-center small"> Copyrights ©2026 E-Dossier. </div>
								</form>
								<!-- Form END -->
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

<!-- Bootstrap JS -->
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<!-- ThemeFunctions -->
<script src="../../assets/js/functions.js"></script>

<!-- Password Match Validation -->
<script>
    const password = document.getElementById("psw-input");
    const confirm_password = document.getElementById("confirm-password");

    function validatePassword(){
      if(password.value != confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
      } else {
        confirm_password.setCustomValidity('');
      }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>



</body>
</html>
