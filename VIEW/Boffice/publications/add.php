<?php
// VIEW/Boffice/publications/add.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../CONTROLLER/PublicationC.php';
$pubCtrl = new PublicationC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre     = trim($_POST['titre'] ?? '');
    $contenu   = trim($_POST['contenu'] ?? '');
    $auteur    = trim($_POST['auteur'] ?? '');
    $date      = $_POST['date'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $document  = null;

    $errors = [];
    if (empty($titre)) $errors[] = "Title is required.";
    if (empty($contenu)) $errors[] = "Content is required.";
    if (empty($auteur)) $errors[] = "Author is required.";
    if (empty($date)) $errors[] = "Date is required.";

    // Handle File Upload
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
            $document = $newFileName;
        } else {
            $errors[] = 'Error moving the file to upload directory.';
        }
    }

    if (empty($errors)) {
        $pubCtrl->addPublication($titre, $contenu, $auteur, $date, $categorie, $document);
        header("Location: /Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php");
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
    }
}

require_once __DIR__ . "/../header.php"; 
?>

<div class="row g-4">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-lg bg-primary bg-opacity-10 text-primary rounded-circle me-3"><i class="bi bi-file-earmark-plus"></i></div>
            <div>
                <h1 class="h3 mb-0">Add Post</h1>
                <p class="text-muted mb-0">Create a new publication in the system.</p>
            </div>
        </div>
        
        <?php if(isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4">
                <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Validation Protocol Failed</h6>
                <ul class="mb-0 small">
                    <?php foreach($_SESSION['errors'] as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label h6">Title *</label>
                        <input type="text" name="titre" class="form-control form-control-lg bg-light border-0" placeholder="Enter the title of the publication" value="<?= htmlspecialchars($_SESSION['old']['titre'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Category *</label>
                        <select name="categorie" class="form-select form-select-lg bg-light border-0">
                            <option value="">Select Category</option>
                            <option value="Announcement" <?= ($_SESSION['old']['categorie'] ?? '') == 'Announcement' ? 'selected' : '' ?>>Announcement</option>
                            <option value="Law" <?= ($_SESSION['old']['categorie'] ?? '') == 'Law' ? 'selected' : '' ?>>Law</option>
                            <option value="Report" <?= ($_SESSION['old']['categorie'] ?? '') == 'Report' ? 'selected' : '' ?>>Report</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Author *</label>
                        <input type="text" name="auteur" class="form-control form-control-lg bg-light border-0" placeholder="Enter the name of the author" value="<?= htmlspecialchars($_SESSION['old']['auteur'] ?? ($_SESSION['name'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label h6">Date & Time *</label>
                        <input type="datetime-local" name="date" class="form-control form-control-lg bg-light border-0" value="<?= htmlspecialchars($_SESSION['old']['date'] ?? date('Y-m-d\TH:i')) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label h6">Document (PDF or Image)</label>
                        <input type="file" name="document" class="form-control form-control-lg bg-light border-0">
                        <small class="text-muted">You can upload official records, proofs, or photos.</small>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label h6 mb-0">Content *</label>
                            <button type="button" id="ai-assist-btn" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-robot me-1"></i> AI Assist
                            </button>
                        </div>
                        <textarea name="contenu" id="publication-content" class="form-control bg-light border-0 p-4" rows="10" placeholder="Write the content of the publication here..."><?= htmlspecialchars($_SESSION['old']['contenu'] ?? '') ?></textarea>
                    </div>
                    <?php unset($_SESSION['old']); ?>
                    <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                        <a href="/Esprit-PW-2A20-2526-e-Dossier/VIEW/Boffice/posts.php" class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Add Post</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('ai-assist-btn').addEventListener('click', function() {
    const title = document.querySelector('input[name="titre"]').value;
    if (!title) {
        alert('Please enter a title first so the AI knows what to write about!');
        return;
    }

    const btn = this;
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Thinking...';

    const formData = new FormData();
    formData.append('action', 'ai_assist');
    formData.append('title', title);

    fetch('/Esprit-PW-2A20-2526-e-Dossier/CONTROLLER/ai_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.content) {
            document.getElementById('publication-content').value = data.content;
            // If there's a summary, we could put it in a hidden field or just prepend it
            if (data.summary) {
                console.log("AI Summary: " + data.summary);
            }
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('AI Assist failed. Please check your API key or connection.');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
</script>

<?php require_once __DIR__ . "/../footer.php"; ?>
