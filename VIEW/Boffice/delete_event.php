<?php
include_once '../../CONTROLLER/EvenementController.php';

$controller = new EvenementC();

$controller->deleteEvenement($_GET['id']);

header("Location: Evenement.php");
exit;
?>