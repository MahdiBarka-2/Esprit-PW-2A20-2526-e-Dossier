<?php
include_once __DIR__ . '/../model/Comment.php';
<<<<<<< HEAD
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
=======

class CommentC {
    private $model;

    public function __construct() {
        $this->model = new Comment();
    }

    // Show comments for a publication (included in show.php)
    public function getComments($publication_id) {
        $comment = new Comment();
        $comment->setPublicationId($publication_id);
        return $this->model->getCommentsByPublication($comment->getPublicationId());
    }

    // Show add comment form
    public function create() {
        if (!isset($_GET['publication_id'])) {
            header("Location: /projetweb/index1.php");
            exit();
        }
        $publication_id = $_GET['publication_id'];
        include __DIR__ . '/../view/comment/add.php';
    }

    // Handle add comment submission
    public function store() {
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

<<<<<<< HEAD
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
=======
            $errors = [];

            if (empty($contenu)) {
                $errors[] = "Comment content is required.";
            } elseif (strlen($contenu) < 5) {
                $errors[] = "Comment must be at least 5 characters.";
            }

            if (empty($auteur)) {
                $errors[] = "Author name is required.";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) {
                $errors[] = "Author name can only contain letters and spaces.";
            }

            if (empty($publication_id)) {
                $errors[] = "Publication is required.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: /projetweb/index1.php?action=addComment&publication_id=$publication_id");
                exit();
            }

            // Use setters
            $comment = new Comment();
            $comment->setContenu($contenu);
            $comment->setAuteur($auteur);
            $comment->setPublicationId($publication_id);

            // Pass using getters
            $this->model->addComment(
                $comment->getContenu(),
                $comment->getAuteur(),
                $comment->getPublicationId()
            );

            header("Location: /projetweb/index1.php?action=show&id=$publication_id");
            exit();
        }
    }

    // Show edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /projetweb/index1.php");
            exit();
        }

        $comment = new Comment();
        $comment->setId($_GET['id']);
        $commentData = $this->model->getOneComment($comment->getId());

        if (!$commentData) {
            header("Location: /projetweb/index1.php");
            exit();
        }

        include __DIR__ . '/../view/comment/edit.php';
    }

    // Handle edit submission
    public function update() {
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id             = $_POST['id'] ?? '';
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

<<<<<<< HEAD
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
=======
            $errors = [];

            if (empty($contenu)) {
                $errors[] = "Comment content is required.";
            } elseif (strlen($contenu) < 5) {
                $errors[] = "Comment must be at least 5 characters.";
            }

            if (empty($auteur)) {
                $errors[] = "Author name is required.";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) {
                $errors[] = "Author name can only contain letters and spaces.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: /projetweb/index1.php?action=editComment&id=$id");
                exit();
            }

            // Use setters
            $comment = new Comment();
            $comment->setId($id);
            $comment->setContenu($contenu);
            $comment->setAuteur($auteur);

            // Pass using getters
            $this->model->updateComment(
                $comment->getId(),
                $comment->getContenu(),
                $comment->getAuteur()
            );

            header("Location: /projetweb/index1.php?action=show&id=$publication_id");
            exit();
        }
    }

    // Delete comment
    public function delete() {
        $publication_id = $_GET['publication_id'] ?? '';

        if (isset($_GET['id'])) {
            $comment = new Comment();
            $comment->setId($_GET['id']);
            $this->model->deleteComment($comment->getId());
        }

        // Redirect back to publication or admin
        if (isset($_GET['from']) && $_GET['from'] === 'admin') {
            header("Location: /projetweb/back-office/index.php?action=comments");
        } else {
            header("Location: /projetweb/index1.php?action=show&id=$publication_id");
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
        }
        exit();
    }

<<<<<<< HEAD
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
=======
    // Admin - list all comments
    public function adminIndex() {
        $list = $this->model->getAllComments();
        include __DIR__ . '/../back-office/comments.php';
    }
}
?>
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
