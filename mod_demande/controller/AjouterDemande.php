<?php
session_start();
include_once __DIR__ . '/demandeC.php';
include_once __DIR__ . '/../model/Demande.php';

// Validation PHP
$errors = [];
$utilisateur  = trim($_POST['utilisateur'] ?? '');
$email        = trim($_POST['email'] ?? '');
$categorie_id = (int)($_POST['categorie_id'] ?? 0);
$description  = trim($_POST['description'] ?? '');

if (strlen($utilisateur) < 3)
    $errors[] = "Le nom doit contenir au moins 3 caractères.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Adresse e-mail invalide.";
if ($categorie_id === 0)
    $errors[] = "Veuillez sélectionner une catégorie.";
if (empty($_FILES['document']['name']))
    $errors[] = "Veuillez joindre un document justificatif.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $_POST;
    header('Location: ../view/frontoffice/Liste.php');
    exit;
}

$dc = new demandeC();
$d  = new Demande($utilisateur, $email, $categorie_id);
$dc->addDemande($d, $_FILES['document'], $description);

$_SESSION['success'] = "Demande soumise avec succès !";
header('Location: ../view/frontoffice/Liste.php');
exit;
