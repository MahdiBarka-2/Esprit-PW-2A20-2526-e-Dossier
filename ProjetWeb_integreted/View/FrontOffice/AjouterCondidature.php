<?php
include("../../Controller/Candidature.php");

$nom     = $_POST["nom"];
$email   = $_POST["email"];
$job_id  = (int) $_POST["job_id"];
$ref     = $_POST["ref"];
$message = $_POST["message"];

$candidature = new Candidature();
$candidature->nom       = $nom;
$candidature->email     = $email;
$candidature->job_id    = $job_id;
$candidature->reference = $ref;
$candidature->message   = $message;

if ($candidature->ajouter()) {
    echo "candidature enregistrée.";
} else {
    echo "Erreur.";
}
?>