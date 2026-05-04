<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function index() {
        // 1. Total number of materiels
        $query1 = "SELECT COUNT(*) as total_materiels FROM materiels";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $total_materiels = $row1['total_materiels'];

        // 2. Materiels by status
        $query2 = "SELECT etat, COUNT(*) as count FROM materiels GROUP BY etat";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();
        $materiels_par_etat = [];
        while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
            $materiels_par_etat[$row2['etat']] = $row2['count'];
        }

        // 3. Ongoing missions
        $query3 = "SELECT COUNT(*) as ongoing_missions FROM missions WHERE CURDATE() BETWEEN date_debut AND date_fin";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->execute();
        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        $ongoing_missions = $row3['ongoing_missions'];

        require_once __DIR__ . '/../views/backoffice/dashboard.php';
    }
}
?>
