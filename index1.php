<?php
session_start();
include 'controller/PublicationC.php';
include 'controller/CommentC.php';

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
    case 'storeComment':
        $commentCtrl->store();
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
        $ctrl->frontIndex();
        break;
}
?>