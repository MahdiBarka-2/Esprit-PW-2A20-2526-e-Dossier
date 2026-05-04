<?php
class Mission {
    private $id;
    private $titre;
    private $description;
    private $date_debut;
    private $date_fin;

    public function __construct($id = null, $titre = "", $description = "", $date_debut = "", $date_fin = "") {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getTitre() { return $this->titre; }
    public function setTitre($titre) { $this->titre = $titre; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getDateDebut() { return $this->date_debut; }
    public function setDateDebut($date_debut) { $this->date_debut = $date_debut; }

    public function getDateFin() { return $this->date_fin; }
    public function setDateFin($date_fin) { $this->date_fin = $date_fin; }
}
?>
