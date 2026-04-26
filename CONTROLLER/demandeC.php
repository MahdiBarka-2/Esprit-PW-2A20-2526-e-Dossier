<?php
require_once __DIR__ . '/../MODEL/Database.php';

class demandeC {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function listeDemandes() {
        try {
            $sql = "SELECT demande.*, categorie.nom AS categorie_nom 
                    FROM demande, categorie 
                    WHERE demande.categorie_id = categorie.id 
                    ORDER BY demande.created_at DESC";
            return $this->db->query($sql);
        } catch (Exception $e) {
            die('Erreur Liste: ' . $e->getMessage());
        }
    }

    public function addDemande($data, $fichier, $description) {
        try {
            $sql = "INSERT INTO demande (utilisateur, email, categorie_id) VALUES (:u, :e, :c)";
            $req = $this->db->prepare($sql);
            $req->execute([
                'u' => $data['utilisateur'],
                'e' => $data['email'],
                'c' => $data['categorie_id']
            ]);
            
            $demande_id = $this->db->lastInsertId();

            $uploadDir = '../assets/uploads/demandes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $nouveauNom = "doc_" . time() . "_" . $fichier['name'];
            move_uploaded_file($fichier['tmp_name'], $uploadDir . $nouveauNom);

            $sql2 = "INSERT INTO justification (demande_id, document, description) VALUES (:id, :doc, :desc)";
            $req2 = $this->db->prepare($sql2);
            $req2->execute([
                'id'   => $demande_id,
                'doc'  => $nouveauNom,
                'desc' => $description
            ]);
            
            return $demande_id;
        } catch (Exception $e) {
            die('Erreur Ajout Demande: ' . $e->getMessage());
        }
    }

    public function getDemande($id) {
        try {
            $sql = "SELECT demande.*, categorie.nom AS categorie_nom 
                    FROM demande, categorie 
                    WHERE demande.categorie_id = categorie.id AND demande.id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur Get: ' . $e->getMessage());
        }
    }

    public function updateStatut($id, $statut) {
        try {
            $sql = "UPDATE demande SET statut = :s WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['s' => $statut, 'id' => $id]);
        } catch (Exception $e) {
            die('Erreur Statut: ' . $e->getMessage());
        }
    }

    public function getJustifications($id) {
        try {
            $sql = "SELECT * FROM justification WHERE demande_id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id]);
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur Justif: ' . $e->getMessage());
        }
    }

    public function deleteDemande($id) {
        try {
            $sql = "DELETE FROM demande WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur Delete: ' . $e->getMessage());
        }
    }

    public function updateDemande($id, $data) {
        try {
            $sql = "UPDATE demande SET utilisateur = :u, email = :e, categorie_id = :c WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute([
                'id' => $id,
                'u'  => $data['utilisateur'],
                'e'  => $data['email'],
                'c'  => $data['categorie_id']
            ]);
        } catch (Exception $e) {
            die('Erreur Update: ' . $e->getMessage());
        }
    }

    public function logHistorique($demande_id, $user, $mail, $act, $det = '', $src = 'utilisateur') {
        try {
            // Si l'id est 0 ou vide, on met NULL pour la base de donnees
            $id_val = null;
            if ($demande_id > 0) {
                $id_val = $demande_id;
            }
            
            $sql = "INSERT INTO historique (demande_id, utilisateur, email, action, details, source) 
                    VALUES (:id, :u, :e, :a, :d, :s)";
            $req = $this->db->prepare($sql);
            $req->execute([
                'id' => $id_val,
                'u'  => $user,
                'e'  => $mail,
                'a'  => $act,
                'd'  => $det,
                's'  => $src
            ]);
        } catch (Exception $e) {
            die('Erreur Historique: ' . $e->getMessage());
        }
    }

    public function getHistorique() {
        try {
            $sql = "SELECT * FROM historique ORDER BY created_at DESC";
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            die('Erreur Get Historique: ' . $e->getMessage());
        }
    }
}
?>
