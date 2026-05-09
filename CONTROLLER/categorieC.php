<?php
require_once __DIR__ . '/../MODEL/Database.php';

class categorieC
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listeCategories()
    {
        try {
            $sql = "SELECT * FROM categorie ORDER BY nom ASC";
            return $this->db->query($sql);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addCategorie($data)
    {
        try {
            $sql = "INSERT INTO categorie (nom, description) VALUES (:n, :d)";
            $req = $this->db->prepare($sql);
            $req->execute([
                'n' => $data['nom'],
                'd' => $data['description']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateCategorie($id, $data)
    {
        try {
            $sql = "UPDATE categorie SET nom = :n, description = :d WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute([
                'id' => $id,
                'n'  => $data['nom'],
                'd'  => $data['description']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteCategorie($id)
    {
        try {
            $sql = "DELETE FROM categorie WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getCategorie($id)
    {
        try {
            $sql = "SELECT * FROM categorie WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
?>
