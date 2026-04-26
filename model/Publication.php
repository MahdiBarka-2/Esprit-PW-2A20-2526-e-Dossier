<<<<<<< HEAD
<?php
class Publication {
    private $id;
    private $titre;
    private $contenu;
    private $auteur;
    private $date;
    private $categorie;

    public function __construct($id=null, $titre=null, $contenu=null, $auteur=null, $date=null, $categorie=null) {
        $this->id        = $id;
        $this->titre     = $titre;
        $this->contenu   = $contenu;
        $this->auteur    = $auteur;
        $this->date      = $date;
        $this->categorie = $categorie;
    }

    // GETTERS
    public function getId()        { return $this->id; }
    public function getTitre()     { return $this->titre; }
    public function getContenu()   { return $this->contenu; }
    public function getAuteur()    { return $this->auteur; }
    public function getDate()      { return $this->date; }
    public function getCategorie() { return $this->categorie; }

    // SETTERS
    public function setId($id)               { $this->id = $id; }
    public function setTitre($titre)         { $this->titre = $titre; }
    public function setContenu($contenu)     { $this->contenu = $contenu; }
    public function setAuteur($auteur)       { $this->auteur = $auteur; }
    public function setDate($date)           { $this->date = $date; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
}
?>
=======
<?php
include_once __DIR__ . '/../config.php';

class Publication {
    // Private properties
    private $id;
    private $titre;
    private $contenu;
    private $auteur;
    private $date;
    private $categorie;
    private $db;

    public function __construct() {
        $this->db = Config::getConnexion();
    }

    // GETTERS
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getContenu() { return $this->contenu; }
    public function getAuteur() { return $this->auteur; }
    public function getDate() { return $this->date; }
    public function getCategorie() { return $this->categorie; }

    // SETTERS
    public function setId($id) { $this->id = $id; }
    public function setTitre($titre) { $this->titre = $titre; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
    public function setAuteur($auteur) { $this->auteur = $auteur; }
    public function setDate($date) { $this->date = $date; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }

    // DATABASE METHODS
    public function listePublication() {
        $req = $this->db->query('SELECT * FROM publication ORDER BY id DESC');
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPublication($titre, $contenu, $auteur, $date, $categorie) {
        $this->setTitre($titre);
        $this->setContenu($contenu);
        $this->setAuteur($auteur);
        $this->setDate($date);
        $this->setCategorie($categorie);

        $req = $this->db->prepare('INSERT INTO publication (titre, contenu, auteur, date, categorie) VALUES (:titre, :contenu, :auteur, :date, :categorie)');
        $req->execute([
            ':titre'     => $this->getTitre(),
            ':contenu'   => $this->getContenu(),
            ':auteur'    => $this->getAuteur(),
            ':date'      => $this->getDate(),
            ':categorie' => $this->getCategorie()
        ]);
    }

    public function getOnePublication($id) {
        $this->setId($id);
        $req = $this->db->prepare('SELECT * FROM publication WHERE id = :id');
        $req->execute([':id' => $this->getId()]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePublication($id, $titre, $contenu, $auteur, $date, $categorie) {
        $this->setId($id);
        $this->setTitre($titre);
        $this->setContenu($contenu);
        $this->setAuteur($auteur);
        $this->setDate($date);
        $this->setCategorie($categorie);

        $req = $this->db->prepare('UPDATE publication SET titre=:titre, contenu=:contenu, auteur=:auteur, date=:date, categorie=:categorie WHERE id=:id');
        $req->execute([
            ':id'        => $this->getId(),
            ':titre'     => $this->getTitre(),
            ':contenu'   => $this->getContenu(),
            ':auteur'    => $this->getAuteur(),
            ':date'      => $this->getDate(),
            ':categorie' => $this->getCategorie()
        ]);
    }

    public function deletePublication($id) {
        $this->setId($id);
        $req = $this->db->prepare('DELETE FROM publication WHERE id = :id');
        $req->execute([':id' => $this->getId()]);
    }
}
?>
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
