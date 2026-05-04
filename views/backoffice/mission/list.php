<?php ob_start(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h1>Gestion des Missions</h1>
    <a href="index.php?action=mission_create" class="btn">Ajouter une mission</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="index.php" style="display: flex; gap: 1rem; align-items: center;">
        <input type="hidden" name="action" value="mission_list">
        <div style="flex: 1;">
            <input type="text" name="search" placeholder="Rechercher par titre..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div>
            <button type="submit" class="btn">Rechercher</button>
            <a href="index.php?action=mission_list" class="btn" style="background: #95a5a6;">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($missions as $m): ?>
            <tr>
                <td><?php echo $m->getId(); ?></td>
                <td><?php echo htmlspecialchars($m->getTitre()); ?></td>
                <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($m->getDateDebut()); ?></td>
                <td><?php echo htmlspecialchars($m->getDateFin()); ?></td>
                <td>
                    <a href="index.php?action=mission_update&id=<?php echo $m->getId(); ?>" class="btn">Modifier</a>
                    <a href="index.php?action=mission_delete&id=<?php echo $m->getId(); ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($missions)): ?>
            <tr><td colspan="6">Aucune mission trouvée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
