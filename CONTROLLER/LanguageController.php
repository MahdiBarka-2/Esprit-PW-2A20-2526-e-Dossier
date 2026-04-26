<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set language based on query parameter or session
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Default language is English
$lang = $_SESSION['lang'] ?? 'en';

// Load translations
$translations = require_once __DIR__ . '/translations.php';

/**
 * Global translation helper function
 * @param string $key The key to translate
 * @return string The translated string or the key if not found
 */
function __($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? ($translations['en'][$key] ?? $key);
}
