<?php
include_once __DIR__ . '/../model/Publication.php';

class PublicationC {
    private $model;

    public function __construct() {
        $this->model = new Publication();
    }

    // FRONT OFFICE
    public function frontIndex() {
        $list = $this->model->listePublication();
        include __DIR__ . '/../view/index.php';
    }

    // BACK OFFICE
    public function index() {
        $list = $this->model->listePublication();
        include __DIR__ . '/../back-office/dashboard.php';
    }

    // Show create form
    public function create() {
        include __DIR__ . '/../view/add.php';
    }

    // Handle create submission
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre     = trim($_POST['titre'] ?? '');
            $contenu   = trim($_POST['contenu'] ?? '');
            $auteur    = trim($_POST['auteur'] ?? '');
            $date      = trim($_POST['date'] ?? '');
            $categorie = trim($_POST['categorie'] ?? '');

            $errors = [];

            if (empty($titre)) {
                $errors[] = "Title is required.";
            } elseif (strlen($titre) < 3) {
                $errors[] = "Title must be at least 3 characters.";
            }

            if (empty($contenu)) {
                $errors[] = "Content is required.";
            } elseif (strlen($contenu) < 10) {
                $errors[] = "Content must be at least 10 characters.";
            }

            if (empty($auteur)) {
                $errors[] = "Author is required.";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) {
                $errors[] = "Author name can only contain letters and spaces.";
            }

            if (empty($date)) {
                $errors[] = "Date is required.";
            } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
                $errors[] = "Date format is invalid (YYYY-MM-DD).";
            }

            if (empty($categorie)) {
                $errors[] = "Category is required.";
            } elseif (!in_array($categorie, ['Announcement', 'Law', 'Report'])) {
                $errors[] = "Invalid category selected.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: /projetweb/back-office/index.php?action=create");
                exit();
            }

<<<<<<< HEAD
            // Use setters
=======
            // Use setters to set the publication properties
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            $publication = new Publication();
            $publication->setTitre($titre);
            $publication->setContenu($contenu);
            $publication->setAuteur($auteur);
            $publication->setDate($date);
            $publication->setCategorie($categorie);

<<<<<<< HEAD
            // Pass using getters
=======
            // Pass the object to the model
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            $this->model->addPublication(
                $publication->getTitre(),
                $publication->getContenu(),
                $publication->getAuteur(),
                $publication->getDate(),
                $publication->getCategorie()
            );

            header("Location: /projetweb/back-office/index.php");
            exit();
        }
    }

    // Show edit form
    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /projetweb/back-office/index.php");
            exit();
        }

<<<<<<< HEAD
        $data = $this->model->getOnePublication($_GET['id']);

        if (!$data) {
=======
        $id = $_GET['id'];
        $publication = $this->model->getOnePublication($id);

        if (!$publication) {
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            header("Location: /projetweb/back-office/index.php");
            exit();
        }

<<<<<<< HEAD
        // Create Publication object and use setters
        $publication = new Publication();
        $publication->setId($data['id']);
        $publication->setTitre($data['titre']);
        $publication->setContenu($data['contenu']);
        $publication->setAuteur($data['auteur']);
        $publication->setDate($data['date']);
        $publication->setCategorie($data['categorie']);

=======
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
        include __DIR__ . '/../view/edit.php';
    }

    // Handle edit submission
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id        = $_POST['id'] ?? '';
            $titre     = trim($_POST['titre'] ?? '');
            $contenu   = trim($_POST['contenu'] ?? '');
            $auteur    = trim($_POST['auteur'] ?? '');
            $date      = trim($_POST['date'] ?? '');
            $categorie = trim($_POST['categorie'] ?? '');

            $errors = [];

            if (empty($titre)) {
                $errors[] = "Title is required.";
            } elseif (strlen($titre) < 3) {
                $errors[] = "Title must be at least 3 characters.";
            }

            if (empty($contenu)) {
                $errors[] = "Content is required.";
            } elseif (strlen($contenu) < 10) {
                $errors[] = "Content must be at least 10 characters.";
            }

            if (empty($auteur)) {
                $errors[] = "Author is required.";
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $auteur)) {
                $errors[] = "Author name can only contain letters and spaces.";
            }

            if (empty($date)) {
                $errors[] = "Date is required.";
            } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
                $errors[] = "Date format is invalid (YYYY-MM-DD).";
            }

            if (empty($categorie)) {
                $errors[] = "Category is required.";
            } elseif (!in_array($categorie, ['Announcement', 'Law', 'Report'])) {
                $errors[] = "Invalid category selected.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = $_POST;
                header("Location: /projetweb/back-office/index.php?action=edit&id=$id");
                exit();
            }

<<<<<<< HEAD
            // Use setters
=======
            // Use setters to set the publication properties
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            $publication = new Publication();
            $publication->setId($id);
            $publication->setTitre($titre);
            $publication->setContenu($contenu);
            $publication->setAuteur($auteur);
            $publication->setDate($date);
            $publication->setCategorie($categorie);

<<<<<<< HEAD
            // Pass using getters
=======
            // Pass the object to the model
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            $this->model->updatePublication(
                $publication->getId(),
                $publication->getTitre(),
                $publication->getContenu(),
                $publication->getAuteur(),
                $publication->getDate(),
                $publication->getCategorie()
            );

            header("Location: /projetweb/back-office/index.php");
            exit();
        }
    }

    // Delete
    public function delete() {
        if (isset($_GET['id'])) {
            $publication = new Publication();
            $publication->setId($_GET['id']);
            $this->model->deletePublication($publication->getId());
        }
        header("Location: /projetweb/back-office/index.php");
        exit();
    }

    // Show single publication (front office)
    public function show() {
        if (!isset($_GET['id'])) {
            header("Location: /projetweb/index1.php");
            exit();
        }

<<<<<<< HEAD
        $data = $this->model->getOnePublication($_GET['id']);

        if (!$data) {
=======
        $publication = new Publication();
        $publication->setId($_GET['id']);
        $publication = $this->model->getOnePublication($publication->getId());

        if (!$publication) {
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
            header("Location: /projetweb/index1.php");
            exit();
        }

<<<<<<< HEAD
        // Create Publication object and use setters
        $publication = new Publication();
        $publication->setId($data['id']);
        $publication->setTitre($data['titre']);
        $publication->setContenu($data['contenu']);
        $publication->setAuteur($data['auteur']);
        $publication->setDate($data['date']);
        $publication->setCategorie($data['categorie']);

=======
>>>>>>> dc4c8718778aa20ce2d552b15f07f0ce99b0a6d3
        include __DIR__ . '/../view/show.php';
    }
}
?>