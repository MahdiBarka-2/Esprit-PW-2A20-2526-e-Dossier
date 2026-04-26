<?php
// view/publications/edit.php - CONTROLLE DE SAISIE (Logic Layer)
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../controller/PublicationC.php';
$pubCtrl = new PublicationC();

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$publication = $pubCtrl->getOnePublication($id);

if (!$publication) {
    header("Location: /projetweb/view/back-office/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre     = trim($_POST['titre'] ?? '');
    $contenu   = trim($_POST['contenu'] ?? '');
    $auteur    = trim($_POST['auteur'] ?? '');
    $date      = $_POST['date'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    $errors = [];
    if (empty($titre)) $errors[] = "Title is required.";
    elseif (strlen($titre) < 3) $errors[] = "Title must be at least 3 characters.";
    if (empty($contenu)) $errors[] = "Content is required.";
    elseif (strlen($contenu) < 10) $errors[] = "Content must be at least 10 characters.";
    if (empty($auteur)) $errors[] = "Author is required.";
    elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) $errors[] = "Author name can only contain letters and spaces.";
    if (empty($date)) $errors[] = "Date is required.";
    if (empty($categorie)) $categorie = "General";

    if (empty($errors)) {
        $pubCtrl->updatePublication($id, $titre, $contenu, $auteur, $date, $categorie);
        header("Location: /projetweb/view/back-office/index.php");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
    }
}

// Delegate to Design Layer
include __DIR__ . '/../design/publications/edit.php';
?>
