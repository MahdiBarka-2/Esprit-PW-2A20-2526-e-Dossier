<?php
include_once '../../CONTROLLER/EvenementCONTROLLER.php';

$CONTROLLER = new EvenementC();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
$CONTROLLER-> updateEvenement($_POST['id'],$_POST);
header("Location: Evenement.php");
    exit;
}
?>
