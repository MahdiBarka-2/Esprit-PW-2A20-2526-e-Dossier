<?php
include_once __DIR__ . '/../model/Comment.php';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id             = $_POST['id'] ?? '';
            $contenu        = trim($_POST['contenu'] ?? '');
            $auteur         = trim($_POST['auteur'] ?? '');
            $publication_id = $_POST['publication_id'] ?? '';

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
        }
        exit();
    }

    // Admin - list all comments
    public function adminIndex() {
        $list = $this->model->getAllComments();
        include __DIR__ . '/../back-office/comments.php';
    }
}
?>