<?php
require_once '../../CONTROLLER/EvenementController.php';
require_once '../../CONTROLLER/ParticipantController.php';

$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
if (!$event_id) { header('Location: back_office.php'); exit; }

$evCtrl       = new EvenementC();
$partCtrl     = new ParticipantC();
$event        = $evCtrl->findById($event_id);
$participants = $partCtrl->findByEvent($event_id);

if (!$event) { header('Location: back_office.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Participants – <?= htmlspecialchars($event['titre']) ?></title>
  <link rel="stylesheet" href="style.css"/>
  <style>
    .participants-wrap { max-width: 900px; margin: 2rem auto; padding: 0 1rem; }

    .back-link {
      display: inline-flex; align-items: center; gap: .4rem;
      font-size: .85rem; color: var(--purple, #6C5CE7);
      text-decoration: none; margin-bottom: 1.5rem;
    }
    .back-link:hover { text-decoration: underline; }

    .event-banner {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 1.2rem 1.4rem;
      margin-bottom: 1.5rem;
    }
    .event-banner h2 { margin: 0 0 .3rem; font-size: 1.15rem; color: #1a1a2e; }
    .event-banner p  { margin: 0; font-size: .85rem; color: #6b7280; }

    .p-table-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      overflow: hidden;
    }

    .p-table-card table { width: 100%; border-collapse: collapse; }
    .p-table-card thead { background: #f9fafb; }
    .p-table-card th {
      padding: .75rem 1rem; text-align: left;
      font-size: .78rem; font-weight: 600;
      text-transform: uppercase; letter-spacing: .04em; color: #6b7280;
      border-bottom: 1px solid #e5e7eb;
    }
    .p-table-card td {
      padding: .75rem 1rem; font-size: .9rem; color: #374151;
      border-bottom: 1px solid #f3f4f6;
    }
    .p-table-card tr:last-child td { border-bottom: none; }
    .p-table-card tr:hover td { background: #f9fafb; }

    .empty-row td {
      text-align: center; padding: 2.5rem 1rem;
      color: #9ca3af; font-size: .9rem;
    }

    .count-badge {
      display: inline-block;
      background: #EEF0FF; color: #6C5CE7;
      border-radius: 20px; padding: .2rem .75rem;
      font-size: .8rem; font-weight: 600;
      margin-left: .5rem;
    }

    .age-badge {
      background: #f3f4f6;
      border-radius: 20px;
      padding: .15rem .6rem;
      font-size: .8rem;
      font-weight: 600;
      color: #374151;
    }
  </style>
</head>
<body>
<div class="participants-wrap">

  <a href="Evenement.php" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <polyline points="15 18 9 12 15 6"/>
    </svg>
    Retour à la liste
  </a>

  <div class="event-banner">
    <h2>
      <?= htmlspecialchars($event['titre']) ?>
      <span class="count-badge"><?= count($participants) ?> inscrit(s)</span>
    </h2>
    <p>
      <?= htmlspecialchars($event['date_debut']) ?>
      <?= $event['date_fin'] ? ' → ' . htmlspecialchars($event['date_fin']) : '' ?>
      <?= $event['lieu'] ? ' · ' . htmlspecialchars($event['lieu']) : '' ?>
    </p>
  </div>

  <div class="p-table-card">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Identifiant</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Âge</th>
          <th>Date d'inscription</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($participants)): ?>
          <tr class="empty-row">
            <td colspan="6">Aucun participant pour le moment.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($participants as $i => $p): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($p['user_id']) ?></td>
            <td><?= htmlspecialchars($p['nom']) ?></td>
            <td><?= htmlspecialchars($p['prenom']) ?></td>
            <td><span class="age-badge"><?= (int)$p['age'] ?> ans</span></td>
            <td><?= htmlspecialchars($p['joined_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
</body>
</html>