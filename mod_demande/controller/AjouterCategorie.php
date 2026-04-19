<?php
session_start();
include_once __DIR__ . '/categorieC.php';
include_once __DIR__ . '/../model/Categorie.php';

$errors = [];
$nom         = trim($_POST['nom'] ?? '');
$description = trim($_POST['description'] ?? '');

if (strlen($nom) < 2)
    $errors[] = "Le nom doit contenir au moins 2 caractères.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../view/backoffice/Categories.php');
    exit;
}

$cc = new categorieC();
$c  = new Categorie($nom, $description);
$cc->addCategorie($c);

$_SESSION['success'] = "Catégorie ajoutée avec succès.";
header('Location: ../view/backoffice/Categories.php');
exit;
