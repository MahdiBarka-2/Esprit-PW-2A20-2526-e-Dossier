<?php
session_start();
require_once __DIR__ . '/categorieC.php';

$cc = new categorieC();

$id   = (int)($_POST['id'] ?? 0);
$data = [
    'nom'         => trim($_POST['nom'] ?? ''),
    'description' => trim($_POST['description'] ?? '')
];
$redirect = '../VIEW/Boffice/categories.php';

$errors = [];
if ($id <= 0)               $errors[] = "Catégorie introuvable.";
if (strlen($data['nom']) < 2) $errors[] = "Le nom doit contenir au moins 2 caractères.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ' . $redirect);
    exit;
}

$cc->updateCategorie($id, $data);

// Log historique
require_once __DIR__ . '/demandeC.php';
$dc = new demandeC();
$dc->logHistorique(null, 'Admin', 'admin@admin.com', 'Modification', 'Catégorie modifiée : ' . $data['nom'], 'admin');

$_SESSION['success'] = "Catégorie modifiée avec succès.";
header('Location: ' . $redirect);
exit;
