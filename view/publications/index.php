<?php
// view/publications/index.php - Unified Command Center (Controlle de Saisie)
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../controller/PublicationC.php';
$pubCtrl = new PublicationC();

// Handle "Add Publication" POST logic (Merged from add.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_publication'])) {
    $titre     = trim($_POST['titre'] ?? '');
    $contenu   = trim($_POST['contenu'] ?? '');
    $auteur    = trim($_POST['auteur'] ?? '');
    $date      = $_POST['date'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    $errors = [];
    if (empty($titre)) $errors[] = "Title is required for synchronization.";
    if (empty($contenu)) $errors[] = "Document content cannot be empty.";
    if (empty($auteur)) $errors[] = "Issuing authority identification is missing.";
    if (empty($date)) $errors[] = "Timestamp is mandatory.";
    if (empty($categorie)) $categorie = "General";

    if (empty($errors)) {
        $pubCtrl->addPublication($titre, $contenu, $auteur, $date, $categorie);
        header("Location: " . $_SERVER['REQUEST_URI']); // Refresh to show new publication
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_pub'] = $_POST;
    }
}

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'date_desc';

// Fetch data logic for rendering
$list = $pubCtrl->listePublication();

// Delegate to Unified Design Layer
include __DIR__ . '/../design/publications/index.php';
?>
