<?php
session_start();
include_once __DIR__ . '/demandeC.php';

$dc = new demandeC();
$dc->updateStatut($_GET['id'], $_GET['statut']);

$_SESSION['success'] = "Statut mis à jour.";
header('Location: ../view/backoffice/Detail.php?id=' . $_GET['id']);
exit;
