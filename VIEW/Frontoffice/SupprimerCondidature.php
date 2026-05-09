<?php
require_once(__DIR__ . "/../../CONTROLLER/Candidature.php");

$id = (int) $_POST["id"];
$c = new CandidatureC();
$c->supprimer($id);

header("Location: ../../View/BackOffice/index.php?msg=deleted");
?>
