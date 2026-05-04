<?php
session_start();
require_once __DIR__ . '/categorieC.php';

$cc = new categorieC();

$data = [
    'nom'         => trim($_POST['nom'] ?? ''),
    'description' => trim($_POST['description'] ?? '')
];
$redirect = '../VIEW/Boffice/categories.php';

if (strlen($data['nom']) < 2) {
    $_SESSION['errors'] = ["Le nom doit contenir au moins 2 caractères."];
    header('Location: ' . $redirect);
    exit;
}

$cc->addCategorie($data);

// Log historique (Optionnel mais bien pour que ca "update")
require_once __DIR__ . '/demandeC.php';
$dc = new demandeC();
$dc->logHistorique(null, 'Admin', 'admin@admin.com', 'Création', 'Nouvelle catégorie créée : ' . $data['nom'], 'admin');

$_SESSION['success'] = "Catégorie ajoutée avec succès.";
header('Location: ' . $redirect);
exit;
