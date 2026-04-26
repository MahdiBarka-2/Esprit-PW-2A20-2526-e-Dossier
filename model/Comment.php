<?php
class Comment {
    private $id;
    private $contenu;
    private $auteur;
    private $date;
    private $publication_id;

    public function __construct($id=null, $contenu=null, $auteur=null, $date=null, $publication_id=null) {
        $this->id             = $id;
        $this->contenu        = $contenu;
        $this->auteur         = $auteur;
        $this->date           = $date;
        $this->publication_id = $publication_id;
    }

    // GETTERS
    public function getId()            { return $this->id; }
    public function getContenu()       { return $this->contenu; }
    public function getAuteur()        { return $this->auteur; }
    public function getDate()          { return $this->date; }
    public function getPublicationId() { return $this->publication_id; }

    // SETTERS
    public function setId($id)                         { $this->id = $id; }
    public function setContenu($contenu)               { $this->contenu = $contenu; }
    public function setAuteur($auteur)                 { $this->auteur = $auteur; }
    public function setDate($date)                     { $this->date = $date; }
    public function setPublicationId($publication_id)  { $this->publication_id = $publication_id; }
}
?>