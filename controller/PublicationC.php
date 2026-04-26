<?php
include_once __DIR__ . '/../model/Publication.php';
<<<<<<< HEAD
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/CommentC.php';

class PublicationC
{

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['saved_publications'])) {
            $_SESSION['saved_publications'] = [];
        }
    }

    // TOGGLE SAVE (Session based)
    public function toggleSave($id)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id = (int)$id;
        if (in_array($id, $_SESSION['saved_publications'])) {
            $_SESSION['saved_publications'] = array_diff($_SESSION['saved_publications'], [$id]);
            return ['status' => 'removed'];
        } else {
            $_SESSION['saved_publications'][] = $id;
            return ['status' => 'added'];
        }
    }

    // LIST SAVED
    public function listSaved()
    {
        if (empty($_SESSION['saved_publications'])) {
            return [];
        }
        $ids = implode(',', array_map('intval', $_SESSION['saved_publications']));
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count 
                FROM publication p 
                WHERE p.id IN ($ids) ORDER BY p.date DESC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    // LIST ALL
    public function listePublication()
    {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count 
                FROM publication p 
                ORDER BY p.date DESC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // ADD
    public function addPublication($titre, $contenu, $auteur, $date, $categorie)
    {
        $sql = "INSERT INTO publication (titre, contenu, auteur, date, categorie) 
                VALUES (:titre, :contenu, :auteur, :date, :categorie)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $titre,
                'contenu' => $contenu,
                'auteur' => $auteur,
                'date' => $date,
                'categorie' => $categorie
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // UPDATE
    public function updatePublication($id, $titre, $contenu, $auteur, $date, $categorie)
    {
        $sql = "UPDATE publication SET titre=:titre, contenu=:contenu, auteur=:auteur, date=:date, categorie=:categorie 
                WHERE id=:id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'titre' => $titre,
                'contenu' => $contenu,
                'auteur' => $auteur,
                'date' => $date,
                'categorie' => $categorie
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // GET ONE
    public function getOnePublication($id)
    {
        $sql = "SELECT * FROM publication WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DELETE
    public function deletePublication($id)
    {
        $sql = "DELETE FROM publication WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // COUNT
    public function countPublications()
    {
        $sql = "SELECT COUNT(*) as total FROM publication";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetch()['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // BACK OFFICE INDEX - High-End Command Center
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'date_desc';

        $order = "ORDER BY date DESC";
        if ($sort === 'date_asc')
            $order = "ORDER BY date ASC";
        if ($sort === 'titre_asc')
            $order = "ORDER BY titre ASC";
        if ($sort === 'titre_desc')
            $order = "ORDER BY titre DESC";

        $sqlList = "SELECT * FROM publication WHERE 1=1 ";
        $paramsList = [];

        if (!empty($search)) {
            $sqlList .= "AND (titre LIKE :search OR auteur LIKE :search OR categorie LIKE :search) ";
            $paramsList['search'] = "%$search%";
        }

        $sqlList .= $order;

        $db = config::getConnexion();
        try {
            $queryList = $db->prepare($sqlList);
            $queryList->execute($paramsList);
            $list = $queryList->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
        $totalPubs = (int) $this->countPublications();
        $commentCtrl = new CommentC();
        $totalComments = (int) $commentCtrl->countComments();
        $recentComments = $commentCtrl->getAllComments();
        $recentComments = array_slice($recentComments, 0, 6);

        // Data for category distribution chart
        $sql = "SELECT categorie, COUNT(*) as count FROM publication GROUP BY categorie";
        $db = config::getConnexion();
        $catDistribution = $db->query($sql)->fetchAll();

        $catLabels = [];
        $catValues = [];
        $catMap = [];
        foreach ($catDistribution as $row) {
            $catName = empty(trim($row['categorie'])) ? 'General' : trim($row['categorie']);
            if (!isset($catMap[$catName])) $catMap[$catName] = 0;
            $catMap[$catName] += (int) $row['count'];
        }
        foreach ($catMap as $cat => $count) {
            $catLabels[] = $cat;
            $catValues[] = $count;
        }

        // Data for Velocity Chart (Monthly trends)
        $sqlVelocity = "SELECT DATE_FORMAT(date, '%M') as month, COUNT(*) as count 
                        FROM publication 
                        GROUP BY month 
                        ORDER BY MIN(date) ASC 
                        LIMIT 12";
        $velocityData = $db->query($sqlVelocity)->fetchAll();

        $monthlyVelocity = [];
        foreach ($velocityData as $row) {
            $monthlyVelocity[$row['month']] = (int) $row['count'];
        }

        // Aggregate stats for view
        $commentStats = $commentCtrl->getCommentStats();
        $stats = [
            'total_publications' => $totalPubs,
            'total_comments' => $totalComments,
            'category_distribution' => array_combine($catLabels, $catValues) ?: [],
            'monthly_velocity' => $monthlyVelocity,
            'avg_engagement' => $totalPubs > 0 ? round($totalComments / $totalPubs, 1) : 0,
            'unique_categories' => count($catDistribution),
            'sentiment' => $commentStats
        ];

        // BUSINESS LOGIC (Métier): Top Trending Insight (JOIN query)
        $sqlTrending = "SELECT p.*, COUNT(c.id) as comment_count 
                         FROM publication p 
                         LEFT JOIN comment c ON p.id = c.publication_id 
                         GROUP BY p.id 
                         ORDER BY comment_count DESC 
                         LIMIT 1";
        $trending = $db->query($sqlTrending)->fetch();
        $stats['trending'] = $trending;

        include __DIR__ . '/../view/back-office/dashboard.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');
            $auteur = trim($_POST['auteur'] ?? '');
            $date = trim($_POST['date'] ?? '');
            $categorie = trim($_POST['categorie'] ?? '');
            if (empty($categorie)) $categorie = 'General';

            $this->addPublication($titre, $contenu, $auteur, $date, $categorie);
            header('Location: /projetweb/view/back-office/index.php');
            exit();
        } else {
            $pubCtrl = $this;
            include __DIR__ . '/../view/publications/add.php';
        }
    }

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $titre = trim($_POST['titre'] ?? '');
            $contenu = trim($_POST['contenu'] ?? '');
            $auteur = trim($_POST['auteur'] ?? '');
            $date = trim($_POST['date'] ?? '');
            $categorie = trim($_POST['categorie'] ?? '');
            if (empty($categorie)) $categorie = 'General';

            $this->updatePublication($id, $titre, $contenu, $auteur, $date, $categorie);
            header('Location: /projetweb/view/back-office/index.php');
            exit();
        } else {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header("Location: /projetweb/view/back-office/index.php");
                exit();
            }
            $publication = $this->getOnePublication($id);
            if (!$publication) {
                header("Location: /projetweb/view/back-office/index.php");
                exit();
            }
            $pubCtrl = $this;
            include __DIR__ . '/../view/publications/edit.php';
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? '';
        if (!empty($id)) {
            $this->deletePublication($id);
        }
        header('Location: /projetweb/view/back-office/index.php');
        exit();
    }

    public function show()
    {
        $pubCtrl = $this;
        $publication = $this->getOnePublication($_GET['id']);
        if (!$publication) {
            die("Publication not found");
        }
        $commentCtrl = new CommentC();
        $comments = $commentCtrl->getCommentsByPublication($publication['id']);
        include __DIR__ . '/../view/design/publications/show.php';
    }

    public function frontIndex()
    {
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'date_desc';
        $category = $_GET['category'] ?? ''; // Added category filter

        $order = "ORDER BY date DESC";
        if ($sort === 'date_asc')
            $order = "ORDER BY date ASC";
        if ($sort === 'title_asc')
            $order = "ORDER BY titre ASC";
        if ($sort === 'title_desc')
            $order = "ORDER BY titre DESC";

        $sql = "SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count FROM publication p WHERE 1=1 ";
        $params = [];

        if (!empty($search)) {
            $sql .= "AND (titre LIKE :search OR auteur LIKE :search) ";
            $params['search'] = "%$search%";
        }

        if (!empty($category)) {
            $sql .= "AND categorie = :category ";
            $params['category'] = $category;
        }

        $sql .= $order;

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            $list = $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }

        include __DIR__ . '/../view/design/publications/index.php';
    }

    public function saved()
    {
        $list = $this->listSaved();
        $isSavedView = true;
        include __DIR__ . '/../view/design/publications/index.php';
    }

    public function toggleSaveAction()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $result = $this->toggleSave($id);
            echo json_encode($result);
        }
        exit();
    }
}
?>
=======

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
>>>>>>> 106395cd0f8aebb18ed38c977bea8c6f08d6b7e3
