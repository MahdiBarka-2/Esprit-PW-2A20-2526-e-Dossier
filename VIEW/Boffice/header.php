<?php
require_once '../../CONTROLLER/LanguageCONTROLLER.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. AUTHENTICATION CHECK: If not logged in, redirect to sign-in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

// 1.5 ROLE CHECK: Guests (clients) cannot access Backoffice
if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
    header("Location: ../Frontoffice/index.php");
    exit();
}

// 2. CACHE CONTROL: Prevent "Back" button from showing authenticated content after logout
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
    <!-- SECURITY SCRIPT: Prevents using the "Back" button to see the dashboard after logout -->
    <script>
        window.addEventListener("pageshow", function (event) {
            // event.persisted is true if the page is loaded from the back-forward cache
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
	<title>e_dossier - Management System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Dark mode script -->
	<script>
		function getStoredTheme() {
			return localStorage.getItem('theme');
		}
		function setStoredTheme(theme) {
			localStorage.setItem('theme', theme);
		}

		function getPreferredTheme() {
			var storedTheme = getStoredTheme();
			if (storedTheme) {
				return storedTheme;
			}
			if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
				return 'dark';
			}
			return 'light';
		}

		function setTheme(theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark');
			} else {
				if (theme === 'auto') {
					document.documentElement.setAttribute('data-bs-theme', 'light');
				} else {
					document.documentElement.setAttribute('data-bs-theme', theme);
				}
			}
		}

		setTheme(getPreferredTheme());

		window.addEventListener('DOMContentLoaded', function() {
			function showActiveTheme(theme) {
				var themeSwitcher = document.querySelector('#bd-theme');
				if (!themeSwitcher) return;

				var btnToActive = document.querySelector('[data-bs-theme-value="' + theme + '"]');
				
				var allToggles = document.querySelectorAll('[data-bs-theme-value]');
				for (var i = 0; i < allToggles.length; i++) {
					allToggles[i].classList.remove('active');
					allToggles[i].setAttribute('aria-pressed', 'false');
				}

				if (btnToActive) {
					btnToActive.classList.add('active');
					btnToActive.setAttribute('aria-pressed', 'true');
				}
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
				var storedTheme = getStoredTheme();
				if (storedTheme !== 'light' && storedTheme !== 'dark') {
					setTheme(getPreferredTheme());
				}
			});

			showActiveTheme(getPreferredTheme());

			var toggles = document.querySelectorAll('[data-bs-theme-value]');
			for (var j = 0; j < toggles.length; j++) {
				toggles[j].addEventListener('click', function() {
					var theme = this.getAttribute('data-bs-theme-value');
					setStoredTheme(theme);
					setTheme(theme);
					showActiveTheme(theme);
				});
			}
		});
	</script>

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="../../assets/vendor/overlay-scrollbar/css/overlayscrollbars.min.css">
	
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    
</head>

<body>
	<main>
         <!-- Sidebar and Topbar integrated here -->
		<?php include 'sidebar.php'; ?>
		<div class="page-content">
			<?php 
            include 'topbar.php'; 
            ?>
