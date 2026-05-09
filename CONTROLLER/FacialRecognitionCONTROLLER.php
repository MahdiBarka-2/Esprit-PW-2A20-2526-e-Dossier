<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . '/MODEL/Database.php';

$action = $_GET['action'] ?? '';

if ($action === 'register') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $descriptor = $input['descriptor'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($descriptor && $user_id) {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("UPDATE users SET face_descriptor = :desc, face_id_enabled = 1 WHERE id = :id");
        if ($stmt->execute(['desc' => json_encode($descriptor), 'id' => $user_id])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    }
    exit;
}

if ($action === 'login') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);
    $live_descriptor = $input['descriptor'] ?? null;

    if ($live_descriptor) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Fetch all users with Face ID enabled
        $stmt = $conn->query("SELECT id, name, email, role, profile_image_url, face_descriptor FROM users WHERE face_id_enabled = 1");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $stored_descriptor = json_decode($user['face_descriptor'], true);
            if (euclideanDistance($live_descriptor, $stored_descriptor) < 0.5) { // 0.5 is a standard threshold
                // Match found!
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['profile_image_url'] = $user['profile_image_url'];
                echo json_encode(['status' => 'success', 'user' => ['name' => $user['name'], 'role' => $user['role']]]);
                exit;
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'No match found']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No descriptor provided']);
    }
    exit;
}

/**
 * Calculate Euclidean Distance between two face descriptors
 */
function euclideanDistance($a, $b) {
    if (count($a) !== count($b)) return 1.0;
    $sum = 0;
    for ($i = 0; $i < count($a); $i++) {
        $sum += pow($a[$i] - $b[$i], 2);
    }
    return sqrt($sum);
}
