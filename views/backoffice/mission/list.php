<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-rocket-takeoff me-2 text-primary"></i>Gestion des Missions</h1>
        <p class="text-muted small mb-0">Organisez vos missions</p>
    </div>
    <div class="d-flex gap-2">
        <a href="missions.php?action=create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle mission
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="missions.php" class="row g-3 align-items-center">
            <input type="hidden" name="action" value="list">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par titre..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
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
                        <th scope="col" class="border-0">Titre</th>
                        <th scope="col" class="border-0">Description</th>
                        <th scope="col" class="border-0">Date Début</th>
                        <th scope="col" class="border-0">Date Fin</th>
                        <th scope="col" class="border-0">État</th>
                        <th scope="col" class="border-0 rounded-end text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($missions as $m): ?>
                    <tr>
                        <td>#<?php echo $m->getId(); ?></td>
                        <td><h6 class="mb-0"><?php echo htmlspecialchars($m->getTitre()); ?></h6></td>
                        <td><?php echo htmlspecialchars($m->getDescription()); ?></td>
                        <td><?php echo htmlspecialchars($m->getDateDebut()); ?></td>
                        <td><?php echo htmlspecialchars($m->getDateFin()); ?></td>
                        <td>
                            <?php 
                            $etat = $m->getEtat();
                            $color = "primary";
                            switch($etat) {
                                case 'Planifiée': $color = "secondary"; break;
                                case 'En cours': $color = "info"; break;
                                case 'Terminée': $color = "success"; break;
                                case 'Annulée': $color = "danger"; break;
                            }
                            ?>
                            <span class="badge bg-<?php echo $color; ?> bg-opacity-10 text-<?php echo $color; ?>"><?php echo htmlspecialchars($etat); ?></span>
                        </td>
                        <td class="text-center">
                            <a href="missions.php?action=update&id=<?php echo $m->getId(); ?>" class="btn btn-sm btn-light mb-0"><i class="bi bi-pencil"></i></a>
                            <a href="missions.php?action=delete&id=<?php echo $m->getId(); ?>" class="btn btn-sm btn-danger-soft mb-0" onclick="return confirm('Êtes-vous sûr ?');"><i class="bi bi-trash text-danger"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($missions)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Aucune mission trouvée.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
