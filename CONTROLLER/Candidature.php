<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Config/database.php';

class CandidatureC {

    public function getAll() {
        $db = Database::getConnection();
        try {
            $req = $db->query("SELECT c.*, j.titre FROM candidatures c
                               LEFT JOIN jobs j ON c.job_id = j.id
                               ORDER BY c.id DESC");
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur getAll: ' . $e->getMessage());
        }
    }

    public function getByRef($ref) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("SELECT * FROM candidatures WHERE reference = :ref");
            $req->execute(['ref' => $ref]);
            return $req->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur getByRef: ' . $e->getMessage());
        }
    }

    public function ajouter($cand) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("INSERT INTO candidatures (nom, email, job_id, reference, message)
                                 VALUES (:nom, :email, :job_id, :ref, :message)");
            $result = $req->execute([
                'nom'     => $cand['nom'],
                'email'   => $cand['email'],
                'job_id'  => $cand['job_id'],
                'ref'     => $cand['reference'],
                'message' => $cand['message']
            ]);
            return $result;
        } catch (Exception $e) {
            die('Erreur ajouter: ' . $e->getMessage());
        }
    }

    public function modifier($ref, $cand) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("UPDATE candidatures
                                 SET nom=:nom, email=:email, job_id=:job_id, message=:message
                                 WHERE reference=:ref");
            return $req->execute([
                'nom'     => $cand['nom'],
                'email'   => $cand['email'],
                'job_id'  => $cand['job_id'],
                'message' => $cand['message'],
                'ref'     => $ref
            ]);
        } catch (Exception $e) {
            die('Erreur modifier: ' . $e->getMessage());
        }
    }

    public function supprimer($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("DELETE FROM candidatures WHERE id = :id");
            return $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur supprimer: ' . $e->getMessage());
        }
    }

    public function getById($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("SELECT * FROM candidatures WHERE id = :id");
            $req->execute(['id' => $id]);
            return $req->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur getById: ' . $e->getMessage());
        }
    }
}

// ── Router ──
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// DEBUG — remove these lines once everything works
error_log("Candidature.php called | action='$action' | POST=" . json_encode($_POST));

if ($action === 'ajouter') {
    $c   = new CandidatureC();
    $ref = 'CAND-' . date('Y') . '-' . rand(1000, 9999);
    $ok  = $c->ajouter([
        'nom'       => $_POST['nom']     ?? '',
        'email'     => $_POST['email']   ?? '',
        'job_id'    => (int)($_POST['job_id'] ?? 0),
        'reference' => $ref,
        'message'   => $_POST['message'] ?? ''
    ]);
    if ($ok) {
        echo 'Candidature enregistrée. Référence : ' . $ref;
    } else {
        http_response_code(500);
        echo 'Erreur : impossible d\'enregistrer la candidature.';
    }

} elseif ($action === 'modifier') {
    $c  = new CandidatureC();
    $ok = $c->modifier($_POST['ref'] ?? '', [
        'nom'     => $_POST['nom']     ?? '',
        'email'   => $_POST['email']   ?? '',
        'job_id'  => (int)($_POST['job_id'] ?? 0),
        'message' => $_POST['message'] ?? ''
    ]);
    echo $ok ? 'Candidature mise à jour.' : 'Erreur : mise à jour échouée.';

} elseif ($action === 'supprimer') {
    $c  = new CandidatureC();
    $ok = $c->supprimer((int)($_POST['id'] ?? 0));
    echo $ok ? 'Supprimé.' : 'Erreur : suppression échouée.';

} elseif ($action === 'getAll') {
    header('Content-Type: application/json');
    $c = new CandidatureC();
    echo json_encode($c->getAll());
    exit;

} else {
    http_response_code(400);
    echo 'Action inconnue : "' . htmlspecialchars($action) . '" — POST reçu: ' . json_encode($_POST);
}
?>
