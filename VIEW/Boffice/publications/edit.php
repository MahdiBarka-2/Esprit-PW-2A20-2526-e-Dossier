<?php
// VIEW/Boffice/publications/edit.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../CONTROLLER/PublicationC.php';
$pubCtrl = new PublicationC();

$id = $_GET['id'] ?? $_POST['id'] ?? '';
if (empty($id)) {
    header("Location: /integration/VIEW/Boffice/posts.php");
    exit();
}

$publication = $pubCtrl->getOnePublication($id);
if (!$publication) {
    header("Location: /integration/VIEW/Boffice/posts.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre     = trim($_POST['titre'] ?? '');
    $contenu   = trim($_POST['contenu'] ?? '');
    $auteur    = trim($_POST['auteur'] ?? '');
    $date      = $_POST['date'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $document  = $publication['document']; 

    $errors = [];
    if (empty($titre)) $errors[] = "Title is required.";
    if (empty($contenu)) $errors[] = "Content is required.";
    if (empty($auteur)) $errors[] = "Author is required.";
    if (empty($date)) $errors[] = "Date is required.";

    // Handle New File Upload
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['document']['tmp_name'];
        $fileName = $_FILES['document']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = __DIR__ . '/../../../uploads/';
        
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }
        
        $dest_path = $uploadFileDir . $newFileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            if ($publication['document'] && file_exists($uploadFileDir . $publication['document'])) {
                @unlink($uploadFileDir . $publication['document']);
            }
            $document = $newFileName;
        } else {
            $errors[] = 'Error moving the uploaded file.';
        }
    }

    if (empty($errors)) {
        $pubCtrl->updatePublication($id, $titre, $contenu, $auteur, $date, $categorie, $document);
        header("Location: /integration/VIEW/Boffice/posts.php");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $publication = array_merge($publication, $_POST);
    }
}

require_once __DIR__ . "/../header.php"; 
?>

<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="fas fa-edit"></i></div>
            <div>
                <h1 class="h3 mb-0">Edit Publication</h1>
                <p class="text-muted mb-0">Modifying publication #<?= str_pad($publication['id'], 5, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>

        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger rounded-4 border-0 shadow p-4 mb-4">
                <h6 class="alert-heading fw-bold">Validation Error</h6>
                <ul class="mb-0">
                    <?php foreach($_SESSION['errors'] as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $publication['id'] ?>">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Title</label>
                        <input type="text" name="titre" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($publication['titre']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Category</label>
                        <select name="categorie" class="form-select form-select-lg bg-light border-0">
                            <option value="Announcement" <?= $publication['categorie'] == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                            <option value="Law" <?= $publication['categorie'] == 'Law' ? 'selected' : '' ?>>Law</option>
                            <option value="Report" <?= $publication['categorie'] == 'Report' ? 'selected' : '' ?>>Report</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Author</label>
                        <input type="text" name="auteur" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($publication['auteur']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Date & Time</label>
                        <input type="datetime-local" name="date" class="form-control form-control-lg bg-light border-0" value="<?= date('Y-m-d\TH:i', strtotime($publication['date'])) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Attachment (PDF or Image)</label>
                        <input type="file" name="document" class="form-control form-control-lg bg-light border-0">
                        <?php if(!empty($publication['document'])): ?>
                            <div class="mt-2 text-primary small fw-bold">
                                <i class="bi bi-file-earmark-check me-1"></i>Current: <?= htmlspecialchars($publication['document']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label class="form-label h6 fw-bold text-uppercase" style="font-size: 0.75rem;">Content</label>
                        <textarea name="contenu" class="form-control bg-light border-0 p-4" rows="12"><?= htmlspecialchars($publication['contenu']) ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                        <a href="/integration/VIEW/Boffice/posts.php" class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Update Publication</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../footer.php"; ?>
