<?php
session_start();
require_once __DIR__ . '/demandeC.php';

$dc     = new demandeC();
$id     = (int)($_GET['id'] ?? 0);
$statut = $_GET['statut'] ?? '';

if (($statut !== 'approuvee' && $statut !== 'rejetee') || $id <= 0) {
    header('Location: ../VIEW/Boffice/demands.php');
    exit;
}

// Récupérer les infos AVANT de changer quoi que ce soit
$demande = $dc->getDemande($id);

if (!$demande) {
    $_SESSION['errors'] = ["Demande introuvable."];
    header('Location: ../VIEW/Boffice/demands.php');
    exit;
}

// Mettre à jour le statut
$dc->updateStatut($id, $statut);

// Logger dans l'historique
$action  = $statut === 'approuvee' ? 'Approbation' : 'Rejet';
$details = 'Statut changé en : ' . $statut . ' par l\'administrateur.';
$dc->logHistorique($id, $demande['utilisateur'], $demande['email'], $action, $details, 'admin');

$_SESSION['success'] = $statut === 'approuvee' ? "Demande approuvée." : "Demande rejetée.";
header('Location: ../VIEW/Boffice/demand-detail.php?id=' . $id);
exit;
