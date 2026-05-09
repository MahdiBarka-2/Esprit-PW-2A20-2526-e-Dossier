<?php
/**
 * SocialAuthCONTROLLER - Handles Google and Facebook OAuth Logic
 */
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../MODEL/Database.php';

// --- CONFIGURATION (PASTE YOUR KEYS HERE) ---
define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URL', 'http://localhost/E_Dossier/CONTROLLER/SocialAuthCONTROLLER.php?platform=google');

define('FACEBOOK_APP_ID', 'YOUR_FACEBOOK_APP_ID');
define('FACEBOOK_APP_SECRET', 'YOUR_FACEBOOK_APP_SECRET');
define('FACEBOOK_REDIRECT_URL', 'http://localhost/E_Dossier/CONTROLLER/SocialAuthCONTROLLER.php?platform=facebook');

$db = (new Database())->getConnection();

// --- PLATFORM ROUTING ---
$platform = $_GET['platform'] ?? '';

if ($platform === 'google') {
    handleGoogleAuth($db);
} elseif ($platform === 'facebook') {
    handleFacebookAuth($db);
}

/**
 * Handle Google OAuth Flow
 */
function handleGoogleAuth($db) {
    if (!isset($_GET['code'])) {
        // Step 1: Redirect to Google
        $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URL,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online'
        ]);
        header("Location: $url");
        exit();
    } else {
        // Step 2: Exchange Code for Access Token
        $response = curlPost("https://oauth2.googleapis.com/token", [
            'code' => $_GET['code'],
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URL,
            'grant_type' => 'authorization_code'
        ]);
        
        $tokenData = json_decode($response, true);
        if (isset($tokenData['access_token'])) {
            // Step 3: Get User Info
            $userInfo = json_decode(file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . $tokenData['access_token']), true);
            authenticateSocialUser($db, $userInfo['email'], $userInfo['name'], $userInfo['id'], 'google', $userInfo['picture']);
        }
    }
}

/**
 * Handle Facebook OAuth Flow
 */
function handleFacebookAuth($db) {
    if (!isset($_GET['code'])) {
        // Step 1: Redirect to Facebook
        $url = "https://www.facebook.com/v12.0/dialog/oauth?" . http_build_query([
            'client_id' => FACEBOOK_APP_ID,
            'redirect_uri' => FACEBOOK_REDIRECT_URL,
            'scope' => 'email'
        ]);
        header("Location: $url");
        exit();
    } else {
        // Step 2: Exchange Code for Access Token
        $response = file_get_contents("https://graph.facebook.com/v12.0/oauth/access_token?" . http_build_query([
            'client_id' => FACEBOOK_APP_ID,
            'client_secret' => FACEBOOK_APP_SECRET,
            'redirect_uri' => FACEBOOK_REDIRECT_URL,
            'code' => $_GET['code']
        ]));
        
        $tokenData = json_decode($response, true);
        if (isset($tokenData['access_token'])) {
            // Step 3: Get User Info
            $userInfo = json_decode(file_get_contents("https://graph.facebook.com/me?fields=id,name,email,picture&access_token=" . $tokenData['access_token']), true);
            authenticateSocialUser($db, $userInfo['email'] ?? ($userInfo['id']."@facebook.com"), $userInfo['name'], $userInfo['id'], 'facebook', $userInfo['picture']['data']['url'] ?? null);
        }
    }
}

/**
 * Login or Register the Social User
 */
function authenticateSocialUser($db, $email, $name, $socialId, $platform, $avatar) {
    $column = ($platform === 'google') ? 'google_id' : 'facebook_id';
    
    // Check if user exists by email or social ID
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR $column = ? LIMIT 1");
    $stmt->execute([$email, $socialId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Register new user
        $stmt = $db->prepare("INSERT INTO users (name, email, role, $column, profile_image_url, status) VALUES (?, ?, 'client', ?, ?, 'active')");
        $stmt->execute([$name, $email, $socialId, $avatar]);
        
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$db->lastInsertId()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Update social ID if not set
        $db->prepare("UPDATE users SET $column = ? WHERE id = ?")->execute([$socialId, $user['id']]);
    }

    // Set Session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['profile_image_url'] = $user['profile_image_url'];

    header("Location: ../VIEW/" . ($user['role'] === 'client' ? 'Frontoffice' : 'Boffice') . "/index.php");
    exit();
}

/**
 * Helper for POST requests
 */
function curlPost($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    return curl_exec($ch);
}
