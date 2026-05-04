<?php ob_start(); ?>
<h1>Bienvenue sur le portail de la municipalité</h1>
<div class="card">
    <p>Ceci est l'espace citoyen (FrontOffice) pour consulter les ressources et les missions de la municipalité.</p>
</div>
<?php $content = ob_get_clean(); require 'layout.php'; ?>
