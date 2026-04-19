<?php
include_once __DIR__ . '/../config/config.php';

class demandeC {

    public function listeDemandes() {
        $db = config::getConnexion();
        try {
            $liste = $db->query(
                'SELECT d.*, c.nom AS categorie_nom
                 FROM demande d
                 JOIN categorie c ON d.categorie_id = c.id
                 ORDER BY d.created_at DESC'
            );
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addDemande($demande, $fichier, $description) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare(
                'INSERT INTO demande (utilisateur, email, categorie_id)
                 VALUES (:utilisateur, :email, :categorie_id)'
            );
            $req->execute([
                'utilisateur'  => $demande->getUtilisateur(),
                'email'        => $demande->getEmail(),
                'categorie_id' => $demande->getCategorieId()
            ]);
            $demande_id = $db->lastInsertId();

            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext      = pathinfo($fichier['name'], PATHINFO_EXTENSION);
            $filename = uniqid('doc_') . '.' . $ext;
            move_uploaded_file($fichier['tmp_name'], $uploadDir . $filename);

            $req2 = $db->prepare(
                'INSERT INTO justification (demande_id, document, description)
                 VALUES (:demande_id, :document, :description)'
            );
            $req2->execute([
                'demande_id'  => $demande_id,
                'document'    => $filename,
                'description' => $description
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteDemande($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM demande WHERE id = :id');
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getDemande($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare(
                'SELECT d.*, c.nom AS categorie_nom
                 FROM demande d
                 JOIN categorie c ON d.categorie_id = c.id
                 WHERE d.id = :id'
            );
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateStatut($id, $statut) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('UPDATE demande SET statut = :statut WHERE id = :id');
            $req->execute(['statut' => $statut, 'id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function getJustifications($demande_id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('SELECT * FROM justification WHERE demande_id = :demande_id');
            $req->execute(['demande_id' => $demande_id]);
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
// ADDED BELOW - do not duplicate class closing brace