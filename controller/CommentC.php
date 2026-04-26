<?php
include_once __DIR__ . '/../model/Comment.php';
include_once __DIR__ . '/../config.php';

class CommentC {

    public function __construct() {
        // No longer using model instance for database logic
    }

    // GET COMMENTS BY PUBLICATION
    public function getCommentsByPublication($publication_id) {
        $sql = "SELECT * FROM comment WHERE publication_id = :publication_id ORDER BY date DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['publication_id' => $publication_id]);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // ADD
    public function addComment($contenu, $auteur, $publication_id) {
        $sql = "INSERT INTO comment (contenu, auteur, publication_id, date) 
                VALUES (:contenu, :auteur, :publication_id, NOW())";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'contenu' => $contenu,
                'auteur' => $auteur,
                'publication_id' => $publication_id
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // UPDATE
    public function updateComment($id, $contenu, $auteur) {
        $sql = "UPDATE comment SET contenu=:contenu, auteur=:auteur WHERE id=:id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'contenu' => $contenu,
                'auteur' => $auteur
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DELETE
    public function deleteComment($id) {
        $sql = "DELETE FROM comment WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // GET ONE
    public function getOneComment($id) {
        $sql = "SELECT * FROM comment WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // GET ALL (JOINED)
    public function getAllComments($search = '', $sort = 'date_desc') {
        $sql = "SELECT c.*, p.titre as publication_titre, p.auteur as publication_auteur FROM comment c 
                JOIN publication p ON c.publication_id = p.id";
        
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE c.contenu LIKE :search OR c.auteur LIKE :search OR p.titre LIKE :search";
            $params['search'] = "%$search%";
        }

        switch($sort) {
            case 'date_asc': $sql .= " ORDER BY c.date ASC"; break;
            case 'auteur_asc': $sql .= " ORDER BY c.auteur ASC"; break;
            case 'auteur_desc': $sql .= " ORDER BY c.auteur DESC"; break;
            case 'date_desc': 
            default: $sql .= " ORDER BY c.date DESC"; break;
        }

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // STATS
    public function getCommentStats() {
        $all = $this->getAllComments(); // No filter
        $total = count($all);
        $positive = 0; $critical = 0; $neutral = 0;
        
        foreach($all as $c) {
            $sentiment = $this->analyzeSentiment($c['contenu']);
            if ($sentiment === 'Positive') $positive++;
            elseif ($sentiment === 'Critical') $critical++;
            else $neutral++;
        }

        return [
            'total' => $total,
            'positive' => $positive,
            'critical' => $critical,
            'neutral' => $neutral
        ];
    }

    // COUNT ALL
    public function countComments() {
        $sql = "SELECT COUNT(*) as total FROM comment";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetch()['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // COUNT BY PUB
    public function countCommentsByPublication($pub_id) {
        $sql = "SELECT COUNT(*) as total FROM comment WHERE publication_id = :pub_id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['pub_id' => $pub_id]);
            return $query->fetch()['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }


    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

            $this->addComment($contenu, $auteur, $publication_id);
            header("Location: /projetweb/index1.php?action=show&id=$publication_id");
            exit();
        } else {
            $publication_id = $_GET['publication_id'] ?? '';
            if (empty($publication_id)) {
                header("Location: /projetweb/index1.php");
                exit();
            }
            $commentCtrl = $this;
            include __DIR__ . '/../view/comments/add.php';
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id             = $_POST['id'] ?? '';
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

            $this->updateComment($id, $contenu, $auteur);
            header("Location: /projetweb/index1.php?action=show&id=$publication_id");
            exit();
        } else {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header("Location: /projetweb/index1.php");
                exit();
            }
            $comment = $this->getOneComment($id);
            if (!$comment) {
                header("Location: /projetweb/index1.php");
                exit();
            }
            $commentCtrl = $this;
            include __DIR__ . '/../view/comments/edit.php';
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? '';
        $pub_id = $_GET['publication_id'] ?? '';
        if (!empty($id)) {
            $this->deleteComment($id);
        }
        if (isset($_GET['from']) && $_GET['from'] === 'admin') {
            header("Location: /projetweb/view/back-office/index.php?action=comments");
        } else {
            header("Location: /projetweb/index1.php?action=show&id=$pub_id");
        }
        exit();
    }

    // BUSINESS LOGIC: Sentiment Analysis (Métier)
    public function analyzeSentiment($text) {
        $text = strtolower($text);
        $positiveKeywords = ['great', 'awesome', 'excellent', 'good', 'love', 'thanks', 'helpful', 'perfect', 'bien', 'merci', 'top', 'intéressant'];
        $negativeKeywords = ['bad', 'terrible', 'awful', 'hate', 'useless', 'wrong', 'error', 'mauvais', 'nul', 'problème', 'faute', 'inutile'];

        $posCount = 0;
        $negCount = 0;

        foreach ($positiveKeywords as $word) {
            if (strpos($text, $word) !== false) $posCount++;
        }
        foreach ($negativeKeywords as $word) {
            if (strpos($text, $word) !== false) $negCount++;
        }

        if ($posCount > $negCount) return 'Positive';
        if ($negCount > $posCount) return 'Critical';
        return 'Neutral';
    }

    public function adminIndex() {
        $commentCtrl = $this;
        include __DIR__ . '/../view/comments/comments.php';
    }
}
?>
