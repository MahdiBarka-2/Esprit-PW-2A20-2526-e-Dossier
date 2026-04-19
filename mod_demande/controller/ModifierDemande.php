<?php
session_start();
include_once __DIR__ . '/demandeC.php';

$errors = [];
$id           = (int)($_POST['id'] ?? 0);
$utilisateur  = trim($_POST['utilisateur'] ?? '');
$email        = trim($_POST['email'] ?? '');
$categorie_id = (int)($_POST['categorie_id'] ?? 0);

if ($id <= 0)
    $errors[] = "Demande introuvable.";
if (strlen($utilisateur) < 3)
    $errors[] = "Le nom doit contenir au moins 3 caractères.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $errors[] = "Adresse e-mail invalide.";
if ($categorie_id === 0)
    $errors[] = "Veuillez sélectionner une catégorie.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../view/frontoffice/Liste.php');
    exit;
}

$dc = new demandeC();
$dc->updateDemande($id, $utilisateur, $email, $categorie_id);

$_SESSION['success'] = "Demande modifiée avec succès !";
header('Location: ../view/frontoffice/Liste.php');
exit;
