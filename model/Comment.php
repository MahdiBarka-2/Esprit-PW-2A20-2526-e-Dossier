<?php
include_once __DIR__ . '/../config.php';

class Comment {
    private $id;
    private $contenu;
    private $auteur;
    private $date;
    private $publication_id;
    private $db;

    public function __construct() {
        $this->db = Config::getConnexion();
    }

    // GETTERS
    public function getId() { return $this->id; }
    public function getContenu() { return $this->contenu; }
    public function getAuteur() { return $this->auteur; }
    public function getDate() { return $this->date; }
    public function getPublicationId() { return $this->publication_id; }

    // SETTERS
    public function setId($id) { $this->id = $id; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
    public function setAuteur($auteur) { $this->auteur = $auteur; }
    public function setDate($date) { $this->date = $date; }
    public function setPublicationId($publication_id) { $this->publication_id = $publication_id; }

    // Get all comments for a publication
    public function getCommentsByPublication($publication_id) {
        $this->setPublicationId($publication_id);
        $req = $this->db->prepare('SELECT * FROM comment WHERE publication_id = :publication_id ORDER BY date DESC');
        $req->execute([':publication_id' => $this->getPublicationId()]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all comments (admin)
    public function getAllComments() {
        $req = $this->db->query('SELECT c.*, p.titre as publication_titre FROM comment c JOIN publication p ON c.publication_id = p.id ORDER BY c.date DESC');
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one comment
    public function getOneComment($id) {
        $this->setId($id);
        $req = $this->db->prepare('SELECT * FROM comment WHERE id = :id');
        $req->execute([':id' => $this->getId()]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // Add comment
    public function addComment($contenu, $auteur, $publication_id) {
        $this->setContenu($contenu);
        $this->setAuteur($auteur);
        $this->setPublicationId($publication_id);

        $req = $this->db->prepare('INSERT INTO comment (contenu, auteur, publication_id) VALUES (:contenu, :auteur, :publication_id)');
        $req->execute([
            ':contenu'        => $this->getContenu(),
            ':auteur'         => $this->getAuteur(),
            ':publication_id' => $this->getPublicationId()
        ]);
    }

    // Update comment
    public function updateComment($id, $contenu, $auteur) {
        $this->setId($id);
        $this->setContenu($contenu);
        $this->setAuteur($auteur);

        $req = $this->db->prepare('UPDATE comment SET contenu=:contenu, auteur=:auteur WHERE id=:id');
        $req->execute([
            ':id'      => $this->getId(),
            ':contenu' => $this->getContenu(),
            ':auteur'  => $this->getAuteur()
        ]);
    }

    // Delete comment
    public function deleteComment($id) {
        $this->setId($id);
        $req = $this->db->prepare('DELETE FROM comment WHERE id = :id');
        $req->execute([':id' => $this->getId()]);
    }
}
?>