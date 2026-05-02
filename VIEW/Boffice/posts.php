<?php
session_start();
$_SESSION["role"] = "administrator";
include_once '../../CONTROLLER/PublicationC.php';
include_once '../../CONTROLLER/CommentC.php';

$ctrl = new PublicationC();
$commentCtrl = new CommentC();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        $ctrl->index();
        break;
    case 'create':
        $ctrl->create();
        break;

    case 'edit':
        $ctrl->edit();
        break;

    case 'delete':
        $ctrl->delete();
        break;
    case 'comments':
        $commentCtrl->adminIndex();
        break;
    case 'editComment':
        $commentCtrl->edit();
        break;

    case 'deleteComment':
        $commentCtrl->delete();
        break;
    case 'approveComment':
        $commentCtrl->approve();
        break;
    case 'dashboard':
        $ctrl->index(); // Assuming dashboard is the main index for now
        break;
    default:
        $ctrl->index();
        break;
}
?>
