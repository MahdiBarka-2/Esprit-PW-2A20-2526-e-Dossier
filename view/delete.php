<?php
include 'controller/PublicationC.php';
$ctrl = new PublicationC();
$ctrl->deletePublication($_GET['id']);
?>