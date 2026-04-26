<?php
session_start();
require_once __DIR__ . '/demandeC.php';

$dc = new demandeC();
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // 1. Recupere les infos avant la suppression pour le log
    $demande = $dc->getDemande($id);
    
    if ($demande) {
        // 2. Log dans l'historique
        $dc->logHistorique($id, $demande['utilisateur'], $demande['email'], 'Suppression', 'Demande supprimée par l\'administrateur.', 'admin');
        
        // 3. Suppression reelle
        $dc->deleteDemande($id);
        $_SESSION['success'] = "Demande supprimée avec succès.";
    }
}

header('Location: ../VIEW/Boffice/demands.php');
exit;
