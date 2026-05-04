<?php ob_start(); ?>
<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
    .stat-card { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; border-left: 5px solid #3498db; }
    .stat-card.success { border-left-color: #2ecc71; }
    .stat-card.warning { border-left-color: #f39c12; }
    .stat-card.danger { border-left-color: #e74c3c; }
    .stat-icon { font-size: 2.5rem; margin-right: 1.5rem; color: #7f8c8d; }
    .stat-details h3 { margin: 0; font-size: 0.9rem; color: #95a5a6; text-transform: uppercase; }
    .stat-details p { margin: 0.5rem 0 0; font-size: 1.8rem; font-weight: bold; color: #2c3e50; }
    
    .etat-list { list-style: none; padding: 0; margin: 1rem 0 0; }
    .etat-list li { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1; }
    .etat-list li:last-child { border-bottom: none; }
</style>

<h1>Tableau de Bord</h1>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-details">
            <h3>Total Matériels</h3>
            <p><?php echo htmlspecialchars($total_materiels); ?></p>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-icon">🎯</div>
        <div class="stat-details">
            <h3>Missions en cours</h3>
            <p><?php echo htmlspecialchars($ongoing_missions); ?></p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card" style="width: 100%;">
        <h3 style="margin-top: 0;">Répartition des matériels par état</h3>
        <ul class="etat-list">
            <?php if (empty($materiels_par_etat)): ?>
                <li>Aucun matériel enregistré.</li>
            <?php else: ?>
                <?php foreach ($materiels_par_etat as $etat => $count): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($etat); ?></strong>
                        <span class="badge" style="background: #3498db; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8em;"><?php echo htmlspecialchars($count); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
