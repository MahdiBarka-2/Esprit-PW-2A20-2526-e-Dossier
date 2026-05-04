<?php ob_start(); ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h1>Gestion des Matériels</h1>
    <a href="index.php?action=materiel_create" class="btn">Ajouter un matériel</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="index.php" style="display: flex; gap: 1rem; align-items: center;">
        <input type="hidden" name="action" value="materiel_list">
        <div style="flex: 1;">
            <input type="text" name="search" placeholder="Rechercher par nom..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
        </div>
        <div>
            <select name="etat" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">Tous les états</option>
                <option value="Disponible" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                <option value="En panne" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'En panne') ? 'selected' : ''; ?>>En panne</option>
                <option value="En réparation" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'En réparation') ? 'selected' : ''; ?>>En réparation</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn">Filtrer</button>
            <a href="index.php?action=materiel_list" class="btn" style="background: #95a5a6;">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiels as $m): ?>
            <tr>
                <td><?php echo $m->getId(); ?></td>
                <td><?php echo htmlspecialchars($m->getNom()); ?></td>
                <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($m->getEtat()); ?></td>
                <td>
                    <a href="index.php?action=materiel_update&id=<?php echo $m->getId(); ?>" class="btn">Modifier</a>
                    <a href="index.php?action=materiel_delete&id=<?php echo $m->getId(); ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($materiels)): ?>
            <tr><td colspan="5">Aucun matériel trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
