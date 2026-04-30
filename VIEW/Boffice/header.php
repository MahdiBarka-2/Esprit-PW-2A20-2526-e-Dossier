<?php
require_once '../../CONTROLLER/LanguageController.php';

// If no session exists, start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" <?php echo ($lang === 'ar' ? 'dir="rtl"' : ''); ?>>

<head>
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
            require_once '../../CONTROLLER/ChatController.php';
            echo renderChatAssistant();
            ?>
