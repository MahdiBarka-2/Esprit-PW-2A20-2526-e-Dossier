<?php
include_once __DIR__ . '/../model/Comment.php';
include_once __DIR__ . '/../MODEL/Database.php';

class CommentC {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // DB: ADD
    public function addComment($contenu, $utilisateur, $publication_id) {
        $db = $this->db;
        $sql = "INSERT INTO comment (contenu, utilisateur, publication_id, date) VALUES (:contenu, :utilisateur, :publication_id, NOW())";
        try { $db->prepare($sql)->execute(['contenu' => $contenu, 'utilisateur' => $utilisateur, 'publication_id' => $publication_id]); } 
        catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }

    // DB: UPDATE
    public function updateComment($id, $contenu, $utilisateur) {
        $db = $this->db;
        try { $db->prepare("UPDATE comment SET contenu=:contenu, utilisateur=:utilisateur WHERE id=:id")->execute(['id' => $id, 'contenu' => $contenu, 'utilisateur' => $utilisateur]); } 
        catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }

    // DB: DELETE
    public function deleteComment($id) {
        $db = $this->db;
        try { $db->prepare("DELETE FROM comment WHERE id = :id")->execute(['id' => $id]); } 
        catch (Exception $e) { die('Error: ' . $e->getMessage()); }
    }

    // DB: GET ONE
    public function getOneComment($id) {
        $db = $this->db;
        $q = $db->prepare("SELECT * FROM comment WHERE id = :id");
        $q->execute(['id' => $id]);
        return $q->fetch();
    }

    // DB: GET BY PUB
    public function getCommentsByPublication($pub_id) {
        $db = $this->db;
        $q = $db->prepare("SELECT * FROM comment WHERE publication_id = :pub_id ORDER BY date DESC");
        $q->execute(['pub_id' => $pub_id]);
        return $q->fetchAll();
    }

    // DB: GET ALL JOINED
    public function getAllComments($search = '', $sort = 'date_desc') {
        $sql = "SELECT c.*, p.titre as publication_titre, p.auteur as publication_auteur FROM comment c JOIN publication p ON c.publication_id = p.id";
        if ($search)
            $sql .= " WHERE LOWER(c.contenu) LIKE :s OR LOWER(c.utilisateur) LIKE :s OR LOWER(p.titre) LIKE :s OR LOWER(p.auteur) LIKE :s";
        
        if ($sort === 'date_asc') $sql .= " ORDER BY c.date ASC";
        elseif ($sort === 'utilisateur_asc') $sql .= " ORDER BY c.utilisateur ASC";
        elseif ($sort === 'utilisateur_desc') $sql .= " ORDER BY c.utilisateur DESC";
        else $sql .= " ORDER BY c.date DESC";

        $db = $this->db;
        $q = $db->prepare($sql);
        if ($search) $q->execute(['s' => "%" . strtolower($search) . "%"]); 
        else $q->execute();
        return $q->fetchAll();
    }

    // DB: COUNT
    public function countComments() {
        return $this->db->query("SELECT COUNT(*) as total FROM comment")->fetch()['total'];
    }

    // BUSINESS: STATS
    public function getCommentStats() {
        $all = $this->getAllComments();
        $pos = 0; $crit = 0; $neu = 0;
        foreach($all as $c) {
            $s = $this->analyzeSentiment($c['contenu']);
            if ($s === 'Positive') $pos++; elseif ($s === 'Critical') $crit++; else $neu++;
        }
        return ['total' => count($all), 'positive' => $pos, 'critical' => $crit, 'neutral' => $neu];
    }

    // BUSINESS: SENTIMENT
    public function analyzeSentiment($text) {
        $text = strtolower($text);
        $posK = ['great', 'awesome', 'excellent', 'good', 'love', 'thanks', 'helpful', 'perfect', 'bien', 'merci', 'top'];
        $negK = ['bad', 'terrible', 'awful', 'hate', 'useless', 'wrong', 'error', 'mauvais', 'nul', 'problème'];
        $p = 0; $n = 0;
        foreach ($posK as $w) if (strpos($text, $w) !== false) $p++;
        foreach ($negK as $w) if (strpos($text, $w) !== false) $n++;
        return ($p > $n) ? 'Positive' : (($n > $p) ? 'Critical' : 'Neutral');
    }

    // ROUTING
    public function create() {
        if (basename($_SERVER['PHP_SELF']) === 'index1.php') {
            include __DIR__ . '/../VIEW/Frontoffice/comments/add.php';
        } else {
            include __DIR__ . '/../VIEW/Boffice/comments/add.php';
        }
    }
    public function edit() {
        if (basename($_SERVER['PHP_SELF']) === 'index1.php') {
            include __DIR__ . '/../VIEW/Frontoffice/comments/edit.php';
        } else {
            include __DIR__ . '/../VIEW/Boffice/comments/edit.php';
        }
    }
    public function delete() {
        if (!empty($_GET['id'])) $this->deleteComment($_GET['id']);
        if (($_GET['from'] ?? '') === 'admin') header("Location: /integration/VIEW/Boffice/posts.php?action=comments");
        else header("Location: /integration/index1.php?action=show&id=" . ($_GET['publication_id'] ?? ''));
        exit();
    }
    public function adminIndex() {
        if (basename($_SERVER['PHP_SELF']) === 'index1.php') {
            include __DIR__ . '/../VIEW/Frontoffice/comments/comments.php';
        } else {
            include __DIR__ . '/../VIEW/Boffice/comments/comments.php';
        }
    }
}
?>
