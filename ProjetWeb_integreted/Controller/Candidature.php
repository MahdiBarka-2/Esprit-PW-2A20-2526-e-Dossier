<?php
require_once __DIR__ . '/../Config/database.php';

class CandidatureC {
    public function getAll() {
        $db = Database::getConnection();
        try {
            $req = $db->query("SELECT c.*, j.titre FROM candidatures c
                               LEFT JOIN jobs j ON c.job_id = j.id
                               ORDER BY c.id DESC");
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function getByRef($ref) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("SELECT * FROM candidatures WHERE reference = :ref");
            $req->execute(['ref' => $ref]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function ajouter($cand) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("INSERT INTO candidatures (nom, email, job_id, reference, message)
                                 VALUES (:nom, :email, :job_id, :ref, :message)");
            $req->execute([
                'nom'     => $cand['nom'],
                'email'   => $cand['email'],
                'job_id'  => $cand['job_id'],
                'ref'     => $cand['reference'],
                'message' => $cand['message']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function modifier($ref, $cand) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("UPDATE candidatures
                                 SET nom=:nom, email=:email, job_id=:job_id, message=:message
                                 WHERE reference=:ref");
            $req->execute([
                'nom'     => $cand['nom'],
                'email'   => $cand['email'],
                'job_id'  => $cand['job_id'],
                'message' => $cand['message'],
                'ref'     => $ref
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function supprimer($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("DELETE FROM candidatures WHERE id = :id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function getById($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("SELECT * FROM candidatures WHERE id = :id");
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}

$action = isset($_POST["action"]) ? $_POST["action"] : "";
if ($action == "ajouter") {
    $c = new CandidatureC();
    $ref = "CAND-" . date("Y") . "-" . rand(1000, 9999);
    $c->ajouter([
        'nom'       => $_POST["nom"],
        'email'     => $_POST["email"],
        'job_id'    => (int) $_POST["job_id"],
        'reference' => $ref,
        'message'   => $_POST["message"]
    ]);
    echo "Candidature enregistrée. Référence : " . $ref;
} else if ($action == "modifier") {
    $c = new CandidatureC();
    $c->modifier($_POST["ref"], [
        'nom'     => $_POST["nom"],
        'email'   => $_POST["email"],
        'job_id'  => (int) $_POST["job_id"],
        'message' => $_POST["message"]
    ]);
    echo "Candidature mise à jour.";
}
?>