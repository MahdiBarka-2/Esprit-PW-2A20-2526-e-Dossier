<?php ob_start(); ?>
<h1>Liste des Missions (Public)</h1>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Date de début</th>
                <th>Date de fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($missions as $m): ?>
            <tr>
                <td><?php echo htmlspecialchars($m->getTitre()); ?></td>
                <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($m->getDateDebut()); ?></td>
                <td><?php echo htmlspecialchars($m->getDateFin()); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($missions)): ?>
            <tr><td colspan="4">Aucune mission disponible.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require 'layout.php'; ?>
