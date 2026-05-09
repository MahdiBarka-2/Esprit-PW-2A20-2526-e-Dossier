<?php
include_once '../../CONTROLLER/EvenementCONTROLLER.php';

$CONTROLLER = new EvenementC();

$CONTROLLER->deleteEvenement($_GET['id']);

header("Location: Evenement.php");
exit;
?>
