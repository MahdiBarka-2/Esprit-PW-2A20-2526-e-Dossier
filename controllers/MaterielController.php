<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Materiel.php';

class MaterielController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create() {
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $etat = trim($_POST['etat']);

            // Validation without HTML5
            if (empty($nom)) { $errors[] = "Le nom du matériel est obligatoire."; }
            if (empty($etat)) { $errors[] = "L'état du matériel est obligatoire."; }

            if (empty($errors)) {
                $query = "INSERT INTO materiels (nom, description, etat) VALUES (:nom, :description, :etat)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":nom", $nom);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":etat", $etat);
                
                if ($stmt->execute()) {
                    header("Location: index.php?action=materiel_list");
                    exit();
                } else {
                    $errors[] = "Erreur lors de l'ajout.";
                }
            }
        }
        require_once __DIR__ . '/../views/backoffice/materiel/create.php';
    }

    public function read() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $etat_filter = isset($_GET['etat']) ? trim($_GET['etat']) : '';

        $query = "SELECT * FROM materiels WHERE 1=1";
        
        if (!empty($search)) {
            $query .= " AND nom LIKE :search";
        }
        if (!empty($etat_filter)) {
            $query .= " AND etat = :etat";
        }

        $query .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(":search", $searchParam);
        }
        if (!empty($etat_filter)) {
            $stmt->bindParam(":etat", $etat_filter);
        }

        $stmt->execute();
        $materiels = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $materiels[] = new Materiel($row['id'], $row['nom'], $row['description'], $row['etat']);
        }
        require_once __DIR__ . '/../views/backoffice/materiel/list.php';
    }

    public function update($id) {
        $errors = [];
        $query = "SELECT * FROM materiels WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            die("Matériel non trouvé.");
        }

        $materiel = new Materiel($row['id'], $row['nom'], $row['description'], $row['etat']);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $etat = trim($_POST['etat']);

            if (empty($nom)) { $errors[] = "Le nom est obligatoire."; }
            if (empty($etat)) { $errors[] = "L'état est obligatoire."; }

            if (empty($errors)) {
                $materiel->setNom($nom);
                $materiel->setDescription($description);
                $materiel->setEtat($etat);

                $query = "UPDATE materiels SET nom = :nom, description = :description, etat = :etat WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                
                $n = $materiel->getNom();
                $d = $materiel->getDescription();
                $e = $materiel->getEtat();
                $i = $materiel->getId();

                $stmt->bindParam(":nom", $n);
                $stmt->bindParam(":description", $d);
                $stmt->bindParam(":etat", $e);
                $stmt->bindParam(":id", $i);
                
                if ($stmt->execute()) {
                    header("Location: index.php?action=materiel_list");
                    exit();
                } else {
                    $errors[] = "Erreur lors de la modification.";
                }
            }
        }
        require_once __DIR__ . '/../views/backoffice/materiel/update.php';
    }

    public function delete($id) {
        $query = "DELETE FROM materiels WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        header("Location: index.php?action=materiel_list");
        exit();
    }
}
?>
