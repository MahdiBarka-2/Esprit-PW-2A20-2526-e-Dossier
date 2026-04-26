<?php include __DIR__ . '/../view/layouts/header.php'; ?>

<div class="container py-5">
    <!-- This div handles the alignment: Title on Left, Buttons on Right -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <!-- Left Side: Title -->
        <h1>BackOffice - Publications</h1>
        
        <!-- Right Side: Buttons Group -->
        <div class="d-flex gap-2">
            <a href="/projetweb/index1.php" class="btn btn-secondary">
                ← FrontOffice
            </a>
            
            <a href="/projetweb/back-office/index.php?action=create" class="btn btn-primary">
                + Add Publication
            </a>
            
            <a href="/projetweb/back-office/index.php?action=comments" class="btn btn-info text-white">
                <i class="fas fa-comments me-1"></i> Comments
            </a>
        </div>
        
    </div>
    
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Author</th>
                <th>Category</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['titre']) ?></td>
                <td><?= htmlspecialchars(substr($p['contenu'], 0, 50)) . '...' ?></td>
                <td><?= htmlspecialchars($p['auteur']) ?></td>
                <td>
                    <span class="badge 
                        <?= $p['categorie'] == 'Law' ? 'bg-danger' : ($p['categorie'] == 'Announcement' ? 'bg-warning text-dark' : 'bg-info text-dark') ?>">
                        <?= htmlspecialchars($p['categorie']) ?>
                    </span>
                </td>
                <td><?= $p['date'] ?></td>
                <td>
                    <a href="/projetweb/back-office/index.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $p['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Delete Publication?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/projetweb/back-office/index.php?action=delete&id=${id}`;
            }
        });
    }
</script>

<?php include __DIR__ . '/../view/layouts/footer.php'; ?>