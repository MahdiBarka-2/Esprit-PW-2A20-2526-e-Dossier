<?php
ob_start();
session_start();
$_SESSION["role"] = "administrator";

require_once '../../CONTROLLER/LanguageController.php';
require_once '../../controllers/MissionController.php';

require_once "header.php";

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$missionController = new MissionController();

// We inject the controller action inside the template
echo '<div class="page-content-wrapper p-xxl-4">';

switch ($action) {
    case 'list':
        $missionController->read();
        break;
    case 'create':
        $missionController->create();
        break;
    case 'update':
        if (isset($_GET['id'])) {
            $missionController->update($_GET['id']);
        } else {
            header("Location: missions.php?action=list");
        }
        break;
    case 'delete':
        if (isset($_GET['id'])) {
            $missionController->delete($_GET['id']);
        } else {
            header("Location: missions.php?action=list");
        }
        break;
    default:
        $missionController->read();
        break;
}

echo '</div>';

require_once "footer.php";
?>
