<?php
session_start();
session_unset();
session_destroy();

// Clear remember-me cookies if any
if (isset($_COOKIE['user_login'])) {
    setcookie('user_login', '', time() - 3600, "/");
}

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

header("Location: sign-in.php?msg=logged_out");
exit();
?>
