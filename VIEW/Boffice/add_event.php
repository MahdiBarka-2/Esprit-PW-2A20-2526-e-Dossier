<?php
include_once '../../CONTROLLER/EvenementCONTROLLER.php';

$CONTROLLER = new EvenementC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CONTROLLER->addEvenement($_POST);

    header("Location: Evenement.php");
    exit;
}

echo "Invalid request";
