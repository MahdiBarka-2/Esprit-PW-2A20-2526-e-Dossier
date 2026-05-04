<?php
session_start();
require_once __DIR__ . '/demandeC.php';

$dc = new demandeC();

$data = [
    'utilisateur'  => trim($_POST['utilisateur'] ?? ''),
    'email'        => trim($_POST['email'] ?? ''),
    'categorie_id' => (int)($_POST['categorie_id'] ?? 0)
];
$description = trim($_POST['description'] ?? '');
$source      = $_POST['source'] ?? 'utilisateur';
$r           = $_POST['redirect'] ?? '';
$redirect    = ($r === 'backoffice_new')
    ? '../VIEW/Boffice/demands.php'
    : '../VIEW/Frontoffice/demandes.php';

// Validation
$errors = [];
if (strlen($data['utilisateur']) < 3)                     $errors[] = "Le nom doit contenir au moins 3 caractères.";
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))    $errors[] = "Adresse e-mail invalide.";
if ($data['categorie_id'] === 0)                           $errors[] = "Veuillez sélectionner une catégorie.";
if (empty($_FILES['document']['name']))                    $errors[] = "Veuillez joindre un document justificatif.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ' . $redirect);
    exit;
}

$demande_id = $dc->addDemande($data, $_FILES['document'], $description);

$details = $source === 'admin'
    ? 'Demande créée par l\'administrateur pour ' . $data['utilisateur'] . '.'
    : 'Nouvelle demande soumise par ' . $data['utilisateur'] . '.';
$dc->logHistorique($demande_id, $data['utilisateur'], $data['email'], 'Création', $details, $source);

$_SESSION['success'] = "Demande soumise avec succès !";
header('Location: ' . $redirect);
exit;
