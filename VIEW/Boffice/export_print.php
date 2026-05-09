<?php
// ── Feature 3 : Page d'impression des demandes ──
// Ouvre dans un nouvel onglet avec un bouton "Imprimer"
session_start();
require_once '../../CONTROLLER/demandeC.php';
require_once '../../CONTROLLER/categorieC.php';

$dc       = new demandeC();
$demandes = $dc->listeDemandes()->fetchAll();
$total    = count($demandes);
$attente  = count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente'));
$approuv  = count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee'));
$rejete   = count(array_filter($demandes, fn($d) => $d['statut'] === 'rejetee'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Impression – Demandes – <?= date('d/m/Y') ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1   { font-size: 18px; margin-bottom: 4px; color: #1d3461; }
        .meta { color: #666; font-size: 11px; margin-bottom: 16px; }
        .stats { display: flex; gap: 16px; margin-bottom: 20px; }
        .stat  { padding: 8px 16px; border-radius: 6px; font-weight: bold; }
        .stat.total  { background: #e8f0fe; color: #1d3461; }
        .stat.wait   { background: #fff8e1; color: #856404; }
        .stat.ok     { background: #e8f5e9; color: #1b5e20; }
        .stat.no     { background: #fce4e4; color: #b71c1c; }

        table { width: 100%; border-collapse: collapse; }
        thead { background: #1d3461; color: white; }
        th, td { padding: 6px 8px; border: 1px solid #ddd; text-align: left; }
        tbody tr:nth-child(even) { background: #f5f5f5; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: bold; }
        .b-wait { background: #fff3cd; color: #856404; }
        .b-ok   { background: #d4edda; color: #155724; }
        .b-no   { background: #f8d7da; color: #721c24; }
        .b-norm { background: #d4edda; color: #155724; }
        .b-urg  { background: #fff3cd; color: #856404; }
        .b-crit { background: #f8d7da; color: #721c24; }

        .btn-print {
            background: #1d3461; color: white; border: none; padding: 8px 20px;
            border-radius: 6px; cursor: pointer; font-size: 13px; margin-bottom: 16px;
        }
        @media print {
            .btn-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Imprimer / Sauvegarder en PDF</button>

    <h1>📋 Liste des Demandes – E-Dossier</h1>
    <p class="meta">Généré le <?= date('d/m/Y à H:i') ?> | <?= $total ?> demande(s) au total</p>

    <div class="stats">
        <div class="stat total">📊 Total : <?= $total ?></div>
        <div class="stat wait">⏳ En attente : <?= $attente ?></div>
        <div class="stat ok">✅ Approuvées : <?= $approuv ?></div>
        <div class="stat no">❌ Rejetées : <?= $rejete ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Priorité</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($demandes as $d): ?>
        <?php
            $sLabel = match($d['statut']) { 'approuvee'=>'Approuvée', 'rejetee'=>'Rejetée', default=>'En attente' };
            $sCls   = match($d['statut']) { 'approuvee'=>'b-ok', 'rejetee'=>'b-no', default=>'b-wait' };
            $prio   = $d['priorite'] ?? 'normale';
            $pLabel = match($prio) { 'critique'=>'Critique', 'urgente'=>'Urgente', default=>'Normale' };
            $pCls   = match($prio) { 'critique'=>'b-crit', 'urgente'=>'b-urg', default=>'b-norm' };
        ?>
        <tr>
            <td><?= $d['id'] ?></td>
            <td><?= htmlspecialchars($d['utilisateur']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td><?= htmlspecialchars($d['categorie_nom']) ?></td>
            <td><span class="badge <?= $sCls ?>"><?= $sLabel ?></span></td>
            <td><span class="badge <?= $pCls ?>"><?= $pLabel ?></span></td>
            <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
