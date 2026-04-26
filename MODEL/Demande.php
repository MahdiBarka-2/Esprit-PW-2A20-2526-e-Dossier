<?php
class Demande {
    private int $id;
    private string $utilisateur;
    private string $email;
    private int $categorie_id;
    private string $statut;

    public function __construct($utilisateur, $email, $categorie_id) {
        $this->utilisateur  = $utilisateur;
        $this->email        = $email;
        $this->categorie_id = $categorie_id;
        $this->statut       = 'en_attente';
    }

    // Getters
    public function getUtilisateur() { return $this->utilisateur; }
    public function getEmail()        { return $this->email; }
    public function getCategorieId()  { return $this->categorie_id; }
    public function getStatut()       { return $this->statut; }

    // Setters
    public function setId(int $id)              { $this->id = $id; }
    public function setUtilisateur(string $u)   { $this->utilisateur = $u; }
    public function setEmail(string $e)         { $this->email = $e; }
    public function setCategorieId(int $c)      { $this->categorie_id = $c; }
    public function setStatut(string $s)        { $this->statut = $s; }
}
