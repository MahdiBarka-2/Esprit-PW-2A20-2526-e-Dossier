<?php
include_once __DIR__ . '/../model/Publication.php';
include_once __DIR__ . '/../MODEL/Database.php';
include_once __DIR__ . '/CommentC.php';

class PublicationC
{
    private $db;
    public $commentCtrl;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->commentCtrl = new CommentC();
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['saved_publications'])) $_SESSION['saved_publications'] = [];
    }

    // TOGGLE SAVE (Session based)
    public function toggleSave($id)
    {
        $id = (int) $id;
        if (in_array($id, $_SESSION['saved_publications'])) {
            $_SESSION['saved_publications'] = array_diff($_SESSION['saved_publications'], [$id]);
            return ['status' => 'removed'];
        } else {
            $_SESSION['saved_publications'][] = $id;
            return ['status' => 'added'];
        }
    }

    // DB: ADD
    public function addPublication($titre, $contenu, $auteur, $date, $categorie, $document = null) {
        $sql = "INSERT INTO publication (titre, contenu, auteur, date, categorie, document) VALUES (:titre, :contenu, :auteur, :date, :categorie, :document)";
        $db = $this->db;
        try {
            $db->prepare($sql)->execute(['titre' => $titre, 'contenu' => $contenu, 'auteur' => $auteur, 'date' => $date, 'categorie' => $categorie, 'document' => $document]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DB: UPDATE
    public function updatePublication($id, $titre, $contenu, $auteur, $date, $categorie, $document = null)
    {
        $sql = "UPDATE publication SET titre=:titre, contenu=:contenu, auteur=:auteur, date=:date, categorie=:categorie, document=:document WHERE id=:id";
        $db = $this->db;
        try {
            $db->prepare($sql)->execute(['id' => $id, 'titre' => $titre, 'contenu' => $contenu, 'auteur' => $auteur, 'date' => $date, 'categorie' => $categorie, 'document' => $document]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DB: DELETE
    public function deletePublication($id)
    {
        $db = $this->db;
        try {
            $db->prepare("DELETE FROM publication WHERE id = :id")->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DB: GET ONE
    public function getOnePublication($id)
    {
        $db = $this->db;
        try {
            $query = $db->prepare("SELECT * FROM publication WHERE id = :id");
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // DB: GET ALL
    public function getAllPublications()
    {
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count FROM publication p ORDER BY p.date DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // DB: COUNT
    public function countPublications()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM publication")->fetch()['total'];
    }

    // ROUTING: BACK OFFICE DASHBOARD
    public function index()
    {
        $viewAction = $_GET['action'] ?? 'index';
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'date_desc';
        $order = ($sort === 'date_asc') ? "ORDER BY date ASC, id ASC" : (($sort === 'titre_asc') ? "ORDER BY titre ASC" : (($sort === 'titre_desc') ? "ORDER BY titre DESC" : "ORDER BY date DESC, id DESC"));

        $db = $this->db;

        // Fetch List (Case-insensitive search)
        $searchLower = "%" . strtolower($search) . "%";
        $sqlList = "SELECT *, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = publication.id) as comment_count FROM publication WHERE (LOWER(titre) LIKE :s OR LOWER(auteur) LIKE :s OR LOWER(categorie) LIKE :s) $order";
        $qList = $db->prepare($sqlList);
        $qList->execute(['s' => $searchLower]);
        $list = $qList->fetchAll();

        $commentCtrl = new CommentC();

        // 1. Data for Composition Chart (Categories)
        $sqlCat = "SELECT categorie, COUNT(*) as count FROM publication GROUP BY categorie";
        $catDistribution = $db->query($sqlCat)->fetchAll();
        $catMap = [];
        foreach ($catDistribution as $row) {
            $name = empty(trim($row['categorie'])) ? 'General' : trim($row['categorie']);
            $catMap[$name] = (int) $row['count'];
        }

        // 2. Data for Publication Trends (Real Data: Pubs vs Comments)
        // Last 6 months
        $months = [];
        $pubTrends = [];
        $commentTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $mName = date('M', strtotime("-$i months"));
            $mValue = date('Y-m', strtotime("-$i months"));
            $months[] = $mName;

            // Count Pubs for this month
            $qP = $db->prepare("SELECT COUNT(*) FROM publication WHERE DATE_FORMAT(date, '%Y-%m') = :m");
            $qP->execute(['m' => $mValue]);
            $pubTrends[] = (int) $qP->fetchColumn();

            // Count Comments for this month
            $qC = $db->prepare("SELECT COUNT(*) FROM comment WHERE DATE_FORMAT(date, '%Y-%m') = :m");
            $qC->execute(['m' => $mValue]);
            $commentTrends[] = (int) $qC->fetchColumn();
        }

        $totalPubs = (int) $this->countPublications();
        $totalComments = (int) $commentCtrl->countComments();

        $trending = $db->query("SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count FROM publication p ORDER BY comment_count DESC LIMIT 1")->fetch();

        // Stats Aggregation
        $stats = [
            'total_publications' => $totalPubs,
            'total_comments' => $totalComments,
            'category_distribution' => $catMap,
            'trends_months' => $months,
            'trends_pubs' => $pubTrends,
            'trends_comments' => $commentTrends,
            'avg_engagement' => $totalPubs > 0 ? round($totalComments / $totalPubs, 1) : 0,
            'unique_categories' => count($catMap),
            'sentiment' => $commentCtrl->getCommentStats(),
            'trending' => $trending
        ];
        include __DIR__ . '/../VIEW/Boffice/dashboard.php';
    }

    public function create()
    {
        include __DIR__ . '/../VIEW/Boffice/publications/add.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? '';
        $publication = $this->getOnePublication($id);
        if (!$publication)
                    header('Location: /integration/VIEW/Boffice/posts.php');
        include __DIR__ . '/../VIEW/Boffice/publications/edit.php';
    }

    public function delete()
    {
        if (!empty($_GET['id']))
            $this->deletePublication($_GET['id']);
                header('Location: /integration/VIEW/Boffice/posts.php');
    }

    public function show()
    {
        $id = $_GET['id'] ?? '';
        $publication = $this->getOnePublication($id);
        if (!$publication)
            die("Publication not found");
        $comments = $this->commentCtrl->getCommentsByPublication($publication['id']);
        
        if (basename($_SERVER['PHP_SELF']) === 'posts.php') {
            include __DIR__ . '/../VIEW/Boffice/publications/show.php';
        } else {
            include __DIR__ . '/../VIEW/Frontoffice/publications/show.php';
        }
    }

    public function frontIndex()
    {
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'date_desc';
        $category = $_GET['category'] ?? '';
        $order = ($sort === 'date_asc') ? "ORDER BY date ASC, id ASC" : (($sort === 'title_asc') ? "ORDER BY titre ASC" : (($sort === 'title_desc') ? "ORDER BY titre DESC" : "ORDER BY date DESC, id DESC"));

        $searchLower = "%" . strtolower($search) . "%";
        $sql = "SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count FROM publication p WHERE (LOWER(titre) LIKE :s OR LOWER(auteur) LIKE :s)";
        if ($category)
            $sql .= " AND LOWER(categorie) = :cat";
        $sql .= " $order";

        $db = $this->db;
        $q = $db->prepare($sql);
        $params = ['s' => $searchLower];
        if ($category)
            $params['cat'] = strtolower($category);
        $q->execute($params);
        $list = $q->fetchAll();
        include __DIR__ . '/../VIEW/Frontoffice/publications/index.php';
    }

    public function saved()
    {
        if (empty($_SESSION['saved_publications'])) {
            $list = [];
        } else {
            $ids = implode(',', array_map('intval', $_SESSION['saved_publications']));
            $list = $this->db->query("SELECT p.*, (SELECT COUNT(*) FROM comment c WHERE c.publication_id = p.id) as comment_count FROM publication p WHERE p.id IN ($ids) ORDER BY p.date DESC")->fetchAll();
        }
        $isSavedView = true;
        include __DIR__ . '/../VIEW/Frontoffice/publications/index.php';
    }

    public function toggleSaveAction()
    {
        if (isset($_GET['id']))
            echo json_encode($this->toggleSave($_GET['id']));
        exit();
    }
}
?>
