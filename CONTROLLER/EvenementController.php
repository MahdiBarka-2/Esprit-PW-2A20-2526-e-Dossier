<?php
require_once '../../MODEL/Database.php';
require_once '../../MODEL/EvenementModel.php';

class EvenementC
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listeEvenement()
    {
        try {
            return $this->db->query('SELECT * FROM evenements');
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addEvenement($event)
    {
        try {
            $req = $this->db->prepare('INSERT INTO evenements (titre, description, date_debut, date_fin, lieu, capacite_max, statut)
                                       VALUES (:titre, :description, :date_debut, :date_fin, :lieu, :capacite_max, :statut)');
            $req->execute([
                'titre'        => $event['titre'],
                'description'  => $event['description'],
                'date_debut'   => $event['date_debut'],
                'date_fin'     => $event['date_fin'],
                'lieu'         => $event['lieu'],
                'capacite_max' => $event['capacite_max'],
                'statut'       => $event['statut']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteEvenement($id)
    {
        try {
            $req = $this->db->prepare('DELETE FROM evenements WHERE id = :id');
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateEvenement($id, $event)
    {
        try {
            $req = $this->db->prepare('UPDATE evenements
                SET titre        = :titre,
                    description  = :description,
                    date_debut   = :date_debut,
                    date_fin     = :date_fin,
                    lieu         = :lieu,
                    capacite_max = :capacite_max,
                    statut       = :statut
                WHERE id = :id');
            $req->execute([
                'id'           => $id,
                'titre'        => $event['titre'],
                'description'  => $event['description'],
                'date_debut'   => $event['date_debut'],
                'date_fin'     => $event['date_fin'],
                'lieu'         => $event['lieu'],
                'capacite_max' => $event['capacite_max'],
                'statut'       => $event['statut']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getEvenement($id)
    {
        try {
            $req = $this->db->prepare('SELECT * FROM evenements WHERE id = :id');
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function findAll()
    {
        return $this->db->query('SELECT * FROM evenements')->fetchAll();
    }

    public function findActive()
    {
        $stmt = $this->db->prepare("SELECT * FROM evenements WHERE statut = 'active'");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM evenements WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}
