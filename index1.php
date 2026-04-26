<?php
session_start();
include_once 'controller/PublicationC.php';
include_once 'controller/CommentC.php';

$ctrl = new PublicationC();
$commentCtrl = new CommentC();

$action = $_GET['action'] ?? 'frontIndex';

switch($action) {
    case 'show':
        $ctrl->show();
        break;
    case 'addComment':
        $commentCtrl->create();
        break;

    case 'editComment':
        $commentCtrl->edit();
        break;

    case 'deleteComment':
        $commentCtrl->delete();
        break;
    case 'saved':
        $ctrl->saved();
        break;
    case 'toggleSave':
        $ctrl->toggleSaveAction();
        break;
    default:
        $ctrl->frontIndex();
        break;
}
?>
