<?php
session_start();
include_once __DIR__ . '/categorieC.php';

$cc = new categorieC();
$cc->deleteCategorie($_GET['id']);

$_SESSION['success'] = "Catégorie supprimée.";
header('Location: ../view/backoffice/Categories.php');
exit;
