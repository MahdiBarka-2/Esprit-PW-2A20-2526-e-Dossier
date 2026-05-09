<?php
// =============================================
// SCRIPT DE MIGRATION – À ouvrir UNE SEULE FOIS
// URL : http://localhost/mon-projet/assets/migrate.php
// Supprimez ce fichier après utilisation !
// =============================================
require_once __DIR__ . '/../MODEL/Database.php';

$database = new Database();
$db = $database->getConnection();

$results = [];

// Feature 7 : Colonne priorité dans la table demande
try {
    $db->exec("ALTER TABLE demande ADD COLUMN priorite ENUM('normale','urgente','critique') NOT NULL DEFAULT 'normale'");
    $results[] = ['ok', "✅ Colonne 'priorite' ajoutée dans 'demande'"];
} catch (Exception $e) {
    $results[] = ['warn', "⚠️ 'priorite' existe déjà ou erreur : " . $e->getMessage()];
}

// Feature 5 : Colonne label dans justification
try {
    $db->exec("ALTER TABLE justification ADD COLUMN label VARCHAR(150) DEFAULT NULL");
    $results[] = ['ok', "✅ Colonne 'label' ajoutée dans 'justification'"];
} catch (Exception $e) {
    $results[] = ['warn', "⚠️ 'label' existe déjà ou erreur : " . $e->getMessage()];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Migration DB</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; }
        .ok   { background: #d4edda; padding: 12px; border-radius: 6px; margin: 8px 0; }
        .warn { background: #fff3cd; padding: 12px; border-radius: 6px; margin: 8px 0; }
        h2    { color: #333; }
        .done { background: #cce5ff; padding: 16px; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>🗄️ Migration Base de Données</h2>
    <?php foreach ($results as [$type, $msg]): ?>
        <div class="<?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>
    <div class="done">
        <strong>Migration terminée !</strong><br>
        ⚠️ <strong>Supprimez ce fichier</strong> maintenant pour des raisons de sécurité.<br>
        <a href="../VIEW/Boffice/demands.php">→ Aller au Backoffice</a>
    </div>
</body>
</html>
