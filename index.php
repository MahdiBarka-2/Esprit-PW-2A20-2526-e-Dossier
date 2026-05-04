<?php
require_once 'controllers/MaterielController.php';
require_once 'controllers/MissionController.php';
require_once 'controllers/DashboardController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'front_home';

$materielController = new MaterielController();
$missionController = new MissionController();
$dashboardController = new DashboardController();

switch ($action) {
    // ---- FRONT OFFICE ----
    case 'front_home':
        require_once 'views/frontoffice/home.php';
        break;
    case 'front_materiels':
        // Reuse read logic but load front view
        $conn = (new Database())->getConnection();
        $stmt = $conn->query("SELECT * FROM materiels ORDER BY id DESC");
        $materiels = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $materiels[] = new Materiel($row['id'], $row['nom'], $row['description'], $row['etat']);
        }
        require_once 'views/frontoffice/materiel_list.php';
        break;
    case 'front_missions':
        $conn = (new Database())->getConnection();
        $stmt = $conn->query("SELECT * FROM missions ORDER BY id DESC");
        $missions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $missions[] = new Mission($row['id'], $row['titre'], $row['description'], $row['date_debut'], $row['date_fin']);
        }
        require_once 'views/frontoffice/mission_list.php';
        break;

    // ---- BACK OFFICE DASHBOARD ----
    case 'backoffice_dashboard':
        $dashboardController->index();
        break;

    // ---- BACK OFFICE MATERIEL ----
    case 'materiel_list':
        $materielController->read();
        break;
    case 'materiel_create':
        $materielController->create();
        break;
    case 'materiel_update':
        if (isset($_GET['id'])) {
            $materielController->update($_GET['id']);
        } else {
            header("Location: index.php?action=materiel_list");
        }
        break;
    case 'materiel_delete':
        if (isset($_GET['id'])) {
            $materielController->delete($_GET['id']);
        } else {
            header("Location: index.php?action=materiel_list");
        }
        break;

    // ---- BACK OFFICE MISSION ----
    case 'mission_list':
        $missionController->read();
        break;
    case 'mission_create':
        $missionController->create();
        break;
    case 'mission_update':
        if (isset($_GET['id'])) {
            $missionController->update($_GET['id']);
        } else {
            header("Location: index.php?action=mission_list");
        }
        break;
    case 'mission_delete':
        if (isset($_GET['id'])) {
            $missionController->delete($_GET['id']);
        } else {
            header("Location: index.php?action=mission_list");
        }
        break;
    
    default:
        require_once 'views/frontoffice/home.php';
        break;
}
?>
