<?php
session_start();
include_once __DIR__ . '/demandeC.php';

$dc = new demandeC();
$dc->deleteDemande($_GET['id']);

$_SESSION['success'] = "Demande supprimée avec succès.";
header('Location: ../view/backoffice/Liste.php');
exit;
