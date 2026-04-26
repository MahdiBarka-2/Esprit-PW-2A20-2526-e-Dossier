<?php
session_start();
include '../controller/PublicationC.php';
include '../controller/CommentC.php';

$ctrl = new PublicationC();
$commentCtrl = new CommentC();

$action = $_GET['action'] ?? 'index';

switch($action) {
    case 'index':
        $ctrl->index();
        break;
    case 'create':
        $ctrl->create();
        break;
    case 'store':
        $ctrl->store();
        break;
    case 'edit':
        $ctrl->edit();
        break;
    case 'update':
        $ctrl->update();
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
    case 'updateComment':
        $commentCtrl->update();
        break;
    case 'deleteComment':
        $commentCtrl->delete();
        break;
    default:
        $ctrl->index();
        break;
}
?>