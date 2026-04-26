<?php
session_start();
<<<<<<< HEAD
include_once 'controller/PublicationC.php';
include_once 'controller/CommentC.php';
=======
include 'controller/PublicationC.php';
include 'controller/CommentC.php';
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3

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
<<<<<<< HEAD

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
=======
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
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
    default:
        $ctrl->frontIndex();
        break;
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
