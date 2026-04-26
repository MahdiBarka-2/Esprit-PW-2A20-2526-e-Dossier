<?php
session_start();
require_once __DIR__ . '/categorieC.php';
require_once __DIR__ . '/demandeC.php';

$cc = new categorieC();
$dc = new demandeC();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // 1. Recupere les infos avant suppression
    $cat = $cc->getCategorie($id);
    
    if ($cat) {
        // 2. Log historique
        $dc->logHistorique(null, 'Admin', 'admin@admin.com', 'Suppression', 'Catégorie supprimée : ' . $cat['nom'], 'admin');
        
        // 3. Suppression
        $cc->deleteCategorie($id);
        $_SESSION['success'] = "Catégorie supprimée.";
    }
}

header('Location: ../VIEW/Boffice/categories.php');
exit;
