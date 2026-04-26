<?php
session_start();
require_once __DIR__ . '/demandeC.php';

$dc = new demandeC();

$id   = (int)($_POST['id'] ?? 0);
$data = [
    'utilisateur'  => trim($_POST['utilisateur'] ?? ''),
    'email'        => trim($_POST['email'] ?? ''),
    'categorie_id' => (int)($_POST['categorie_id'] ?? 0)
];

$errors = [];
if ($id <= 0)                      $errors[] = "Demande introuvable.";
if (strlen($data['utilisateur']) < 3) $errors[] = "Le nom doit contenir au moins 3 caractères.";
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Adresse e-mail invalide.";
if ($data['categorie_id'] === 0)   $errors[] = "Veuillez sélectionner une catégorie.";

$r = $_POST['redirect'] ?? '';
$redirect = ($r === 'backoffice_new') ? '../VIEW/Boffice/demands.php' : '../VIEW/Frontoffice/demandes.php';

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ' . $redirect);
    exit;
}

// Mise a jour
$dc->updateDemande($id, $data);

// Log historique
$dc->logHistorique($id, $data['utilisateur'], $data['email'], 'Modification', 'Demande modifiée par l\'utilisateur.');

$_SESSION['success'] = "Demande modifiée avec succès !";
header('Location: ' . $redirect);
exit;
