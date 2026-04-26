<?php
session_start();
include_once '../../controller/PublicationC.php';
include_once '../../controller/CommentC.php';

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
    default:
        $ctrl->index();
        break;
}
?>