<?php
require_once __DIR__ . '/../MODEL/Database.php';
require_once __DIR__ . '/AiService.php';


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
            $sql = "INSERT INTO demande (utilisateur, email, categorie_id, description) VALUES (:u, :e, :c, :d)";
            $req = $this->db->prepare($sql);
            $req->execute([
                'u' => $data['utilisateur'],
                'e' => $data['email'],
                'c' => $data['categorie_id'],
                'd' => $description
            ]);
            
            $demande_id = $this->db->lastInsertId();

            $uploadDir = '../assets/uploads/demandes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $nouveauNom = "doc_" . time() . "_" . $fichier['name'];
            move_uploaded_file($fichier['tmp_name'], $uploadDir . $nouveauNom);

            $sql2 = "INSERT INTO justification (demande_id, document) VALUES (:id, :doc)";
            $req2 = $this->db->prepare($sql2);
            $req2->execute([
                'id'   => $demande_id,
                'doc'  => $nouveauNom
            ]);
            
            // Send Confirmation Email
            $subject = "Réception de votre demande";
            $body = "Bonjour " . $data['utilisateur'] . ",\n\n";
            $body .= "Nous avons reçu votre demande. Elle sera traitée dans les plus brefs délais.\n\n";
            $body .= "Cordialement,\nLa Municipalité.";
            AiService::sendEmail($data['email'], $subject, $body);
            
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

            // Send Decision Email
            $demande = $this->getDemande($id);
            $subject = "Décision concernant votre demande #" . $id;
            $statusLabel = ($statut === 'approuvee') ? 'Approuvée' : 'Rejetée';
            
            $body = "Bonjour " . $demande['utilisateur'] . ",\n\n";
            $body .= "Votre demande a été " . $statusLabel . ".\n\n";
            $body .= "Cordialement,\nLa Municipalité.";
            
            AiService::sendEmail($demande['email'], $subject, $body);

        } catch (Exception $e) {
            die('Erreur Statut: ' . $e->getMessage());
        }
    }



    public function getAiSupportOnTheFly($id) {
        try {
            $demande = $this->getDemande($id);
            if (!$demande) return null;

            // Analyse de base (type, completude...)
            $aiAnalysis = AiService::analyzeRequest($demande['description'], $demande['categorie_nom'] ?? 'Inconnue');
            
            // Analyse de decision
            return AiService::getDecisionSupport($aiAnalysis['type'], $aiAnalysis['is_complete'], $demande['description']);
        } catch (Exception $e) {
            return null;
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
            $sql = "UPDATE demande SET utilisateur = :u, email = :e, categorie_id = :c, description = :d WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->execute([
                'id' => $id,
                'u'  => $data['utilisateur'],
                'e'  => $data['email'],
                'c'  => $data['categorie_id'],
                'd'  => $data['description']
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
