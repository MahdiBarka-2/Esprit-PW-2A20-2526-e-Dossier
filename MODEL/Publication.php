<?php
class Publication {
    private $id;
    private $titre;
    private $contenu;
    private $auteur;
    private $date;
    private $categorie;
    private $document;

    public function __construct($id=null, $titre=null, $contenu=null, $auteur=null, $date=null, $categorie=null, $document=null) {
        $this->id        = $id;
        $this->titre     = $titre;
        $this->contenu   = $contenu;
        $this->auteur    = $auteur;
        $this->date      = $date;
        $this->categorie = $categorie;
        $this->document  = $document;
    }

    // GETTERS
    public function getId()        { return $this->id; }
    public function getTitre()     { return $this->titre; }
    public function getContenu()   { return $this->contenu; }
    public function getAuteur()    { return $this->auteur; }
    public function getDate()      { return $this->date; }
    public function getCategorie() { return $this->categorie; }
    public function getDocument()  { return $this->document; }

    // SETTERS
    public function setId($id)               { $this->id = $id; }
    public function setTitre($titre)         { $this->titre = $titre; }
    public function setContenu($contenu)     { $this->contenu = $contenu; }
    public function setAuteur($auteur)       { $this->auteur = $auteur; }
    public function setDate($date)           { $this->date = $date; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setDocument($document)   { $this->document = $document; }
}
?>
