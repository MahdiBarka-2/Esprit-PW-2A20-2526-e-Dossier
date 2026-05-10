<?php
// ── Contrôleur : Ajouter une demande ──
// Appelé par les formulaires Front & Back office
session_start();
require_once __DIR__ . '/demandeC.php';

$dc = new demandeC();

// Feature 7 : récupérer la priorité (normale par défaut)
$data = [
    'utilisateur'  => trim($_POST['utilisateur'] ?? ''),
    'email'        => trim($_POST['email'] ?? ''),
    'categorie_id' => (int)($_POST['categorie_id'] ?? 0),
    'priorite'     => in_array($_POST['priorite'] ?? '', ['normale','urgente','critique'])
                      ? $_POST['priorite']
                      : 'normale'
];
$description = trim($_POST['description'] ?? '');
$source      = $_POST['source'] ?? 'utilisateur';
$r           = $_POST['redirect'] ?? '';
$redirect    = ($r === 'backoffice_new')
    ? '../VIEW/Boffice/demands.php'
    : '../VIEW/Frontoffice/demandes.php';

// Validation de base
$errors = [];
if (strlen($data['utilisateur']) < 3)                     $errors[] = "Le nom doit contenir au moins 3 caractères.";
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))    $errors[] = "Adresse e-mail invalide.";
if ($data['categorie_id'] === 0)                           $errors[] = "Veuillez sélectionner une catégorie.";

// Feature 5 : vérifier que au moins un fichier est envoyé
$fichiers = $_FILES['documents'] ?? null;
if (empty($fichiers['name'][0]))                           $errors[] = "Veuillez joindre au moins un document.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ' . $redirect);
    exit;
}

// Ajouter la demande (multi-fichiers + priorité)
$demande_id = $dc->addDemande($data, $fichiers, $description);

// Enregistrer dans l'historique
$details = $source === 'admin'
    ? 'Demande créée par l\'administrateur pour ' . $data['utilisateur'] . '.'
    : 'Nouvelle demande soumise par ' . $data['utilisateur'] . '.';
$dc->logHistorique($demande_id, $data['utilisateur'], $data['email'], 'Création', $details, $source);

$_SESSION['success'] = "Demande #$demande_id soumise avec succès !";
header('Location: ' . $redirect);
exit;
