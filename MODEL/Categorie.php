<?php
class Categorie {
    private int $id;
    private string $nom;
    private string $description;

    public function __construct($nom, $description) {
        $this->nom         = $nom;
        $this->description = $description;
    }

    // Getters
    public function getNom()         { return $this->nom; }
    public function getDescription() { return $this->description; }

    // Setters
    public function setId(int $id)              { $this->id = $id; }
    public function setNom(string $n)           { $this->nom = $n; }
    public function setDescription(string $d)   { $this->description = $d; }
}
