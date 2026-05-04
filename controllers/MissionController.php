<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Mission.php';

class MissionController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $date_debut = trim($_POST['date_debut']);
            $date_fin = trim($_POST['date_fin']);
            $etat = trim($_POST['etat']);

            // Validation PHP
            if (empty($titre)) { $errors[] = "Le titre est obligatoire."; }
            if (empty($date_debut)) { $errors[] = "La date de début est obligatoire."; }
            if (empty($date_fin)) { $errors[] = "La date de fin est obligatoire."; }
            if (!empty($date_debut) && !empty($date_fin) && strtotime($date_debut) > strtotime($date_fin)) {
                $errors[] = "La date de fin doit être postérieure à la date de début.";
            }

            if (empty($errors)) {
                $query = "INSERT INTO missions (titre, description, date_debut, date_fin, etat) VALUES (:titre, :description, :date_debut, :date_fin, :etat)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":titre", $titre);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":date_debut", $date_debut);
                $stmt->bindParam(":date_fin", $date_fin);
                $stmt->bindParam(":etat", $etat);
                
                if ($stmt->execute()) {
                    header("Location: missions.php?action=list");
                    exit();
                } else {
                    $errors[] = "Erreur lors de l'ajout.";
                }
            }
        }
        require_once __DIR__ . '/../views/backoffice/mission/create.php';
    }

    public function read() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        $query = "SELECT * FROM missions WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND titre LIKE :search";
        }
        
        $query .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(":search", $searchParam);
        }

        $stmt->execute();
        $missions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $missions[] = new Mission($row['id'], $row['titre'], $row['description'], $row['date_debut'], $row['date_fin'], $row['etat']);
        }
        require_once __DIR__ . '/../views/backoffice/mission/list.php';
    }

    public function update($id) {
        $errors = [];
        $query = "SELECT * FROM missions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            die("Mission non trouvée.");
        }

        $mission = new Mission($row['id'], $row['titre'], $row['description'], $row['date_debut'], $row['date_fin'], $row['etat']);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $titre = trim($_POST['titre']);
            $description = trim($_POST['description']);
            $date_debut = trim($_POST['date_debut']);
            $date_fin = trim($_POST['date_fin']);
            $etat = trim($_POST['etat']);

            if (empty($titre)) { $errors[] = "Le titre est obligatoire."; }
            if (empty($date_debut)) { $errors[] = "La date de début est obligatoire."; }
            if (empty($date_fin)) { $errors[] = "La date de fin est obligatoire."; }
            if (!empty($date_debut) && !empty($date_fin) && strtotime($date_debut) > strtotime($date_fin)) {
                $errors[] = "La date de fin doit être postérieure à la date de début.";
            }

            if (empty($errors)) {
                $mission->setTitre($titre);
                $mission->setDescription($description);
                $mission->setDateDebut($date_debut);
                $mission->setDateFin($date_fin);
                $mission->setEtat($etat);

                $query = "UPDATE missions SET titre = :titre, description = :description, date_debut = :date_debut, date_fin = :date_fin, etat = :etat WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                
                $t = $mission->getTitre();
                $d = $mission->getDescription();
                $db = $mission->getDateDebut();
                $df = $mission->getDateFin();
                $e = $mission->getEtat();
                $i = $mission->getId();

                $stmt->bindParam(":titre", $t);
                $stmt->bindParam(":description", $d);
                $stmt->bindParam(":date_debut", $db);
                $stmt->bindParam(":date_fin", $df);
                $stmt->bindParam(":etat", $e);
                $stmt->bindParam(":id", $i);
                
                if ($stmt->execute()) {
                    header("Location: missions.php?action=list");
                    exit();
                } else {
                    $errors[] = "Erreur lors de la modification.";
                }
            }
        }
        require_once __DIR__ . '/../views/backoffice/mission/update.php';
    }

    public function delete($id) {
        $query = "DELETE FROM missions WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        header("Location: missions.php?action=list");
        exit();
    }
}
?>
