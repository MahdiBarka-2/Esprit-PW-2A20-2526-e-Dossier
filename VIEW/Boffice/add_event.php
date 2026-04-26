<?php
include_once '../../CONTROLLER/EvenementController.php';

$controller = new EvenementC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->addEvenement($_POST);

    header("Location: Evenement.php");
    exit;
}

echo "Invalid request";