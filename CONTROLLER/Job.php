<?php
require_once __DIR__ . '/../Config/database.php';

class JobC {
    public function getAll() {
        $db = Database::getConnection();
        try {
            $req = $db->query("SELECT * FROM jobs ORDER BY id DESC");
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function getById($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("SELECT * FROM jobs WHERE id = :id");
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function ajouter($job) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("INSERT INTO jobs (titre, reference, lieu, type, description, date_limite, statut)
                                VALUES (:titre, :reference, :lieu, :type, :description, :date_limite, :statut)");
            $req->execute([
                'titre'       => $job['titre'],
                'reference'   => $job['reference'],
                'lieu'        => $job['lieu'],
                'type'        => $job['type'],
                'description' => $job['description'],
                'date_limite' => $job['date_limite'],
                'statut'      => $job['statut']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function modifier($id, $job) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("UPDATE jobs
                                SET titre=:titre, reference=:reference, lieu=:lieu, type=:type,
                                    description=:description, date_limite=:date_limite, statut=:statut
                                WHERE id=:id");
            $req->execute([
                'id'          => $id,
                'titre'       => $job['titre'],
                'reference'   => $job['reference'],
                'lieu'        => $job['lieu'],
                'type'        => $job['type'],
                'description' => $job['description'],
                'date_limite' => $job['date_limite'],
                'statut'      => $job['statut']
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    public function supprimer($id) {
        $db = Database::getConnection();
        try {
            $req = $db->prepare("DELETE FROM jobs WHERE id = :id");
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}

$action = isset($_POST["action"]) ? $_POST["action"] : "";
if ($action == "ajouter") {
    $j = new JobC();
    $j->ajouter([
        'titre'       => $_POST["titre"],
        'reference'   => $_POST["reference"],
        'lieu'        => $_POST["lieu"],
        'type'        => $_POST["type"],
        'description' => $_POST["description"],
        'date_limite' => $_POST["date_limite"],
        'statut'      => $_POST["statut"]
    ]);
    echo "Job ajouté avec succès.";
} else if ($action == "modifier") {
    $j = new JobC();
    $j->modifier((int) $_POST["id"], [
        'titre'       => $_POST["titre"],
        'reference'   => $_POST["reference"],
        'lieu'        => $_POST["lieu"],
        'type'        => $_POST["type"],
        'description' => $_POST["description"],
        'date_limite' => $_POST["date_limite"],
        'statut'      => $_POST["statut"]
    ]);
    echo "Job mis à jour.";
} else if ($action == "supprimer") {
    $j = new JobC();
    $j->supprimer((int) $_POST["id"]);
    echo "Job supprimé.";
}
if ($action == "getAll") {
    $j = new JobC();
    $jobs = $j->getAll();
    echo json_encode($jobs);
}
?>