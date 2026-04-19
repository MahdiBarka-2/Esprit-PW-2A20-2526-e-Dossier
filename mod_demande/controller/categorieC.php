<?php
include_once __DIR__ . '/../config/config.php';

class categorieC {

    public function listeCategories() {
        $db = config::getConnexion();
        try {
            $liste = $db->query('SELECT * FROM categorie ORDER BY nom ASC');
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addCategorie($categorie) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare(
                'INSERT INTO categorie (nom, description) VALUES (:nom, :description)'
            );
            $req->execute([
                'nom'         => $categorie->getNom(),
                'description' => $categorie->getDescription()
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteCategorie($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM categorie WHERE id = :id');
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
