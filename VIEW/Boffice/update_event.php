<?php
include_once '../../CONTROLLER/EvenementController.php';

$controller = new EvenementC();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
$controller-> updateEvenement($_POST['id'],$_POST);
header("Location: Evenement.php");
    exit;
}
?>