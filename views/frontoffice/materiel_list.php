<?php ob_start(); ?>
<h1>Liste des Matériels (Public)</h1>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiels as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m->getNom()); ?></td>
                <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($m->getEtat()); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($materiels)): ?>
            <tr><td colspan="3">Aucun matériel disponible.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require 'layout.php'; ?>
