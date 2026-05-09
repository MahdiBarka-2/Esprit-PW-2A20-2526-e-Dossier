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

    // Feature 5 : $fichiers est maintenant $_FILES['documents'] (multi-fichiers)
    // Feature 7 : $data['priorite'] est inclus
    public function addDemande($data, $fichiers, $description) {
        try {
            $priorite = $data['priorite'] ?? 'normale';

            $sql = "INSERT INTO demande (utilisateur, email, categorie_id, description, priorite)
                    VALUES (:u, :e, :c, :d, :p)";
            $req = $this->db->prepare($sql);
            $req->execute([
                'u' => $data['utilisateur'],
                'e' => $data['email'],
                'c' => $data['categorie_id'],
                'd' => $description,
                'p' => $priorite
            ]);

            $demande_id = $this->db->lastInsertId();

            // Dossier d'upload
            $uploadDir = '../assets/uploads/demandes/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Feature 5 : on boucle sur tous les fichiers envoyés
            $nbFichiers = count($fichiers['name']);
            for ($i = 0; $i < $nbFichiers; $i++) {
                if (empty($fichiers['name'][$i]) || $fichiers['error'][$i] !== 0) continue;

                $nouveauNom = "doc_" . time() . "_" . $i . "_" . basename($fichiers['name'][$i]);
                move_uploaded_file($fichiers['tmp_name'][$i], $uploadDir . $nouveauNom);

                $sql2 = "INSERT INTO justification (demande_id, document, label) VALUES (:id, :doc, :label)";
                $req2 = $this->db->prepare($sql2);
                $req2->execute([
                    'id'    => $demande_id,
                    'doc'   => $nouveauNom,
                    'label' => $fichiers['name'][$i]
                ]);
            }

            // Email de confirmation
            $subject = "Réception de votre demande #" . $demande_id;
            $body    = "Bonjour " . $data['utilisateur'] . ",\n\n";
            $body   .= "Nous avons bien reçu votre demande (Priorité : " . ucfirst($priorite) . ").\n";
            $body   .= "Elle sera traitée dans les plus brefs délais.\n\nCordialement,\nLa Municipalité.";
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

    // ── Feature 2 : Nombre de demandes en attente (pour le badge notification) ──
    public function getCountEnAttente() {
        try {
            return (int)$this->db->query("SELECT COUNT(*) FROM demande WHERE statut = 'en_attente'")->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }

    // ── Feature 6 : Demandes en attente depuis plus de $jours jours ──
    public function getOverdueDemandes($jours = 3) {
        try {
            $sql = "SELECT demande.*, categorie.nom AS categorie_nom
                    FROM demande, categorie
                    WHERE demande.categorie_id = categorie.id
                    AND demande.statut = 'en_attente'
                    AND DATEDIFF(NOW(), demande.created_at) >= :jours
                    ORDER BY demande.created_at ASC";
            $req = $this->db->prepare($sql);
            $req->execute(['jours' => $jours]);
            return $req->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // ── Feature 4 : Retrouver une demande par son ID et l'email du citoyen ──
    public function getDemandeByIdAndEmail($id, $email) {
        try {
            $sql = "SELECT demande.*, categorie.nom AS categorie_nom
                    FROM demande, categorie
                    WHERE demande.categorie_id = categorie.id
                    AND demande.id = :id
                    AND demande.email = :email";
            $req = $this->db->prepare($sql);
            $req->execute(['id' => $id, 'email' => $email]);
            return $req->fetch();
        } catch (Exception $e) {
            return null;
        }
    }

    public function countDemandes($statut = null) {
        try {
            if ($statut) {
                $sql = "SELECT COUNT(*) FROM demande WHERE statut = :s";
                $req = $this->db->prepare($sql);
                $req->execute(['s' => $statut]);
                return (int)$req->fetchColumn();
            } else {
                return (int)$this->db->query("SELECT COUNT(*) FROM demande")->fetchColumn();
            }
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>
