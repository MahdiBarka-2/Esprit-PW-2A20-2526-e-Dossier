<?php
/**
 * UserController - Integrated Functional Layer for User Management
 * This file handles all business logic and data operations for users directly.
 */

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../MODEL/Database.php';
require_once __DIR__ . '/../MODEL/User.php';

/**
 * Returns a database connection
 */
function getDbConnection() {
    $database = new Database();
    return $database->getConnection();
}

/**
 * Helper: Processes file uploads
 */
function handleFileUpload($file, $targetDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = time() . '_' . uniqid() . '.' . $extension;
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Correct path for project views
        return '../../assets/uploads/' . basename($targetDir) . '/' . $fileName;
    }

    return null;
}

/**
 * Helper: Check if current user is admin
 */
function isAdminUser() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator');
}

/**
 * Validates and logs in a user
 */
function processUserLogin($email, $password) {
    $db = getDbConnection();
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && (password_verify($password, $user['password_hash']) || $password === $user['password_plain'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_image_url'] = $user['profile_image_url'];
        return $user;
    }
    return false;
}

/**
 * Adds a new user to the system
 */
function processUserAdd($data, $files = []) {
    if (isset($data['role']) && $data['role'] !== 'client') {
        if (!isAdminUser()) return false;
    }

    $db = getDbConnection();
    
    // Check duplicate
    $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $data['email']);
    $stmt->execute();
    if ($stmt->fetch()) {
        return "duplicate";
    }

    $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
    $password_plain = $data['password'];
    
    $profile_image_url = handleFileUpload($files['profile_image'] ?? null, __DIR__ . '/../assets/uploads/profiles/');
    $cv_file_path = handleFileUpload($files['cv_file'] ?? null, __DIR__ . '/../assets/uploads/cvs/');

    $query = "INSERT INTO users (name, email, password_hash, password_plain, role, profile_image_url, cv_file_path, phone, status) 
              VALUES (:name, :email, :password_hash, :password_plain, :role, :profile_image_url, :cv_file_path, :phone, :status)";

    $stmt = $db->prepare($query);
    
    // Sanitize and bind
    $stmt->bindValue(":name", htmlspecialchars(strip_tags($data['name'])));
    $stmt->bindValue(":email", htmlspecialchars(strip_tags($data['email'])));
    $stmt->bindValue(":password_hash", $password_hash);
    $stmt->bindValue(":password_plain", htmlspecialchars(strip_tags($password_plain)));
    $stmt->bindValue(":role", htmlspecialchars(strip_tags($data['role'])));
    $stmt->bindValue(":profile_image_url", $profile_image_url);
    $stmt->bindValue(":cv_file_path", $cv_file_path);
    $stmt->bindValue(":phone", htmlspecialchars(strip_tags($data['phone'])));
    $stmt->bindValue(":status", htmlspecialchars(strip_tags($data['status'] ?? 'active')));

    try {
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Updates an existing user
 */
function processUserUpdate($data, $files = []) {
    if (!isAdminUser() && (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $data['id'])) {
        return false;
    }

    $db = getDbConnection();
    $existing = findUserById($data['id']);
    if (!$existing) return false;

    $password_plain = !empty($data['password']) ? $data['password'] : $existing['password_plain'];
    $password_hash = !empty($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : $existing['password_hash'];

    $newImage = handleFileUpload($files['profile_image'] ?? null, __DIR__ . '/../assets/uploads/profiles/');
    $profile_image_url = $newImage ?: ($data['existing_profile_image'] ?? $existing['profile_image_url']);

    $newCV = handleFileUpload($files['cv_file'] ?? null, __DIR__ . '/../assets/uploads/cvs/');
    $cv_file_path = $newCV ?: ($data['existing_cv_file'] ?? $existing['cv_file_path']);

    $query = "UPDATE users SET
                name = :name,
                email = :email,
                role = :role,
                profile_image_url = :profile_image_url,
                cv_file_path = :cv_file_path,
                phone = :phone,
                status = :status,
                password_plain = :password_plain,
                password_hash = :password_hash,
                updated_at = NOW()
              WHERE id = :id";

    $stmt = $db->prepare($query);
    
    $stmt->bindValue(":name", htmlspecialchars(strip_tags($data['name'])));
    $stmt->bindValue(":email", htmlspecialchars(strip_tags($data['email'])));
    $stmt->bindValue(":role", htmlspecialchars(strip_tags($data['role'])));
    $stmt->bindValue(":profile_image_url", $profile_image_url);
    $stmt->bindValue(":cv_file_path", $cv_file_path);
    $stmt->bindValue(":phone", htmlspecialchars(strip_tags($data['phone'])));
    $stmt->bindValue(":status", htmlspecialchars(strip_tags($data['status'])));
    $stmt->bindValue(":password_plain", htmlspecialchars(strip_tags($password_plain)));
    $stmt->bindValue(":password_hash", $password_hash);
    $stmt->bindValue(":id", $data['id']);

    if ($stmt->execute()) {
        // Refresh session if the current user updated their own profile
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['id']) {
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['profile_image_url'] = $profile_image_url;
        }
        return true;
    }
    return false;
}

/**
 * Deletes a user by ID
 */
function processUserDelete($id) {
    if (!isAdminUser()) return false;
    $db = getDbConnection();
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

/**
 * Fetches a single user's data
 */
function findUserById($id) {
    $db = getDbConnection();
    $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Fetches users by their role
 */
function findUsersByRole($role) {
    $db = getDbConnection();
    if ($role === 'client') {
        $query = "SELECT * FROM users WHERE role = 'client' ORDER BY created_at DESC";
    } else {
        $query = "SELECT * FROM users WHERE role IN ('administrator', 'employee') ORDER BY created_at DESC";
    }
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt;
}

/**
 * Returns the count of users by role
 */
function countUsersByRole($role = null) {
    $db = getDbConnection();
    if ($role === 'client') {
        $query = "SELECT COUNT(*) as total FROM users WHERE role = 'client'";
    } elseif ($role === 'agent') {
        $query = "SELECT COUNT(*) as total FROM users WHERE role IN ('administrator', 'employee')";
    } else {
        $query = "SELECT COUNT(*) as total FROM users";
    }
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
}

/**
 * Returns the most recent user registrations
 */
function findRecentUsers($limit = 5) {
    $db = getDbConnection();
    $query = "SELECT * FROM users ORDER BY created_at DESC LIMIT :limit";
    $stmt = $db->prepare($query);
    $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
}

// Global Logic / Request Handling
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = processUserLogin($_POST['email'], $_POST['password']);
        if ($user) {
            if ($user['role'] === 'client') {
                header("Location: ../VIEW/Frontoffice/index.php");
            } else {
                header("Location: ../VIEW/Boffice/index.php");
            }
        } else {
            header("Location: ../VIEW/Boffice/sign-in.php?error=invalid_credentials");
        }
        exit();
    }

    if ($_GET['action'] === 'fetch' && isset($_GET['id'])) {
        $data = findUserById($_GET['id']);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    if ($_GET['action'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = processUserAdd($_POST, $_FILES);
        if ($result === true) {
            if (isset($_POST['source']) && $_POST['source'] === 'frontoffice') {
                header("Location: ../VIEW/Frontoffice/index.php?msg=welcome");
            } else {
                $role = $_POST['role'];
                if ($role === 'client') {
                    header("Location: ../VIEW/Boffice/clients.php?msg=success");
                } else {
                    header("Location: ../VIEW/Boffice/agents.php?msg=success");
                }
            }
        } elseif ($result === "duplicate") {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=duplicate");
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=error");
        }
        exit();
    }

    if ($_GET['action'] === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        if (processUserUpdate($_POST, $_FILES)) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=updated");
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=error");
        }
        exit();
    }

    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        if (processUserDelete($_GET['id'])) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=deleted");
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . "msg=error");
        }
        exit();
    }
}