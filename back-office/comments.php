<?php include __DIR__ . '/../view/layouts/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>BackOffice - Comments</h1>
        <div>
            <a href="/projetweb/back-office/index.php" class="btn btn-secondary">← Dashboard</a>
        </div>
    </div>

    <table class="table table-dark table-hover mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Comment</th>
                <th>Publication</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($list as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['auteur']) ?></td>
                <td><?= htmlspecialchars(substr($c['contenu'], 0, 50)) ?>...</td>
                <td><span class="badge bg-primary"><?= htmlspecialchars($c['publication_titre']) ?></span></td>
                <td><?= date('M d, Y', strtotime($c['date'])) ?></td>
                <td>
                    <a href="/projetweb/back-office/index.php?action=deleteComment&id=<?= $c['id'] ?>&publication_id=<?= $c['publication_id'] ?>&from=admin" 
   class="btn btn-sm btn-danger"
   onclick="return confirm('Delete this comment?')">
    <i class="fas fa-trash"></i> Delete
</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../view/layouts/footer.php'; ?>