<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-tools me-2 text-primary"></i>Gestion des Matériels</h1>
        <p class="text-muted small mb-0">Organisez vos matériels</p>
    </div>
    <div class="d-flex gap-2">
        <a href="materiels.php?action=create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouveau matériel
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="materiels.php" class="row g-3 align-items-center">
            <input type="hidden" name="action" value="list">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <select name="etat" class="form-select">
                    <option value="">Tous les états</option>
                    <option value="Disponible" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="En panne" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'En panne') ? 'selected' : ''; ?>>En panne</option>
                    <option value="En réparation" <?php echo (isset($_GET['etat']) && $_GET['etat'] == 'En réparation') ? 'selected' : ''; ?>>En réparation</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow h-100 border-0">
    <div class="card-body p-0">
        <div class="table-responsive border-0">
            <table class="table align-middle p-4 mb-0 table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="border-0 rounded-start">ID</th>
                        <th scope="col" class="border-0">Nom</th>
                        <th scope="col" class="border-0">Description</th>
                        <th scope="col" class="border-0">État</th>
                        <th scope="col" class="border-0 rounded-end text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiels as $m): ?>
                    <tr>
                        <td>#<?php echo $m->getId(); ?></td>
                        <td><h6 class="mb-0"><?php echo htmlspecialchars($m->getNom()); ?></h6></td>
                        <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                        <td>
                            <?php if ($m->getEtat() == 'Disponible'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success">Disponible</span>
                            <?php else: ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning"><?php echo htmlspecialchars($m->getEtat()); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="materiels.php?action=update&id=<?php echo $m->getId(); ?>" class="btn btn-sm btn-light mb-0"><i class="bi bi-pencil"></i></a>
                            <a href="materiels.php?action=delete&id=<?php echo $m->getId(); ?>" class="btn btn-sm btn-danger-soft mb-0" onclick="return confirm('Êtes-vous sûr ?');"><i class="bi bi-trash text-danger"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($materiels)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Aucun matériel trouvé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
