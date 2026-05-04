<?php
class Materiel {
    private $id;
    private $nom;
    private $description;
    private $etat; // e.g., 'Disponible', 'En maintenance', 'En mission'

    public function __construct($id = null, $nom = "", $description = "", $etat = "Disponible") {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->etat = $etat;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNom() { return $this->nom; }
    public function setNom($nom) { $this->nom = $nom; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getEtat() { return $this->etat; }
    public function setEtat($etat) { $this->etat = $etat; }
}
?>
