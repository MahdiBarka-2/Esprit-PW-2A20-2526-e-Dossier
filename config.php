<?php
class config {
    public static function getConnexion() {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=projetweb', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>