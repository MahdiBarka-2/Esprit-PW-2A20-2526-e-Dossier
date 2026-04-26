<?php
include '../../CONTROLLER/EvenementController.php';
include '../../CONTROLLER/ParticipantController.php';

$controller = new ParticipantC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'join') {
        $controller->addParticipant($_POST);
    } elseif ($action === 'leave') {
        $controller->deleteParticipant($_POST['event_id'], $_POST['user_id']);
    }

    header("Location: Events.php");
    exit;
}

// GET — show the popup for the event_id passed in the URL
$event_id = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
$action   = $_GET['action'] ?? 'join';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <title>Participation</title>
  <style>
    /* dark overlay */
    body {
      margin: 0;
      font-family: sans-serif;
      background: rgba(0,0,0,.45);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    /* popup box */
    .popup {
      background: #fff;
      border-radius: 10px;
      padding: 2rem 2.5rem;
      width: 340px;
      box-shadow: 0 8px 32px rgba(0,0,0,.25);
      text-align: center;
    }

    .popup h2 {
      margin: 0 0 .5rem;
      font-size: 1.2rem;
      color: #1a1a2e;
    }

    .popup p {
      margin: 0 0 1.4rem;
      font-size: .9rem;
      color: #666;
    }

    .popup input[type="text"] {
      width: 100%;
      padding: .6rem .9rem;
      border: 1.5px solid #ddd;
      border-radius: 7px;
      font-size: .95rem;
      margin-bottom: 1.2rem;
      box-sizing: border-box;
    }
    .popup input[type="number"] {
      width: 100%;
      padding: .6rem .9rem;
      border: 1.5px solid #ddd;
      border-radius: 7px;
      font-size: .95rem;
      margin-bottom: 1.2rem;
      box-sizing: border-box;
    }

    .popup input[type="text"]:focus {
      outline: none;
      border-color: #6C5CE7;
    }
     .popup input[type="number"]:focus {
      outline: none;
      border-color: #6C5CE7;
    }

    .btn-row {
      display: flex;
      gap: .7rem;
    }

    .btn-cancel {
      flex: 1;
      padding: .6rem;
      border: 1.5px solid #ddd;
      border-radius: 7px;
      background: #fff;
      color: #666;
      cursor: pointer;
      font-size: .9rem;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .btn-submit {
      flex: 1;
      padding: .6rem;
      border: none;
      border-radius: 7px;
      background: #6C5CE7;
      color: #fff;
      cursor: pointer;
      font-size: .9rem;
      font-weight: 600;
    }

    .btn-submit:hover  { background: #5a4bd1; }
    .btn-cancel:hover  { border-color: #aaa; color: #333; }
  </style>
</head>
<body>

<div class="popup">
  <h2><?= $action === 'join' ? 'Rejoindre l\'événement' : 'Quitter l\'événement' ?></h2>
  <p>Entrez votre identifiant pour continuer.</p>

  <form method="POST" action="join_event.php">
    <input type="hidden" name="event_id" value="<?= $event_id ?>">
    <input type="hidden" name="action"   value="<?= htmlspecialchars($action) ?>">

    <input type="text"
           name="user_id"
           placeholder="Votre identifiant (cin)"
           autofocus />
           <input type="text"   name="nom"    placeholder="Nom"    >
<input type="text"   name="prenom" placeholder="Prénom" >
<input type="number" name="age"    placeholder="Âge" min="1" max="120" >

    <div class="btn-row">
      <a href="Events.php" class="btn-cancel">Annuler</a>
      <button type="submit" class="btn-submit">Confirmer</button>
    </div>
  </form>
</div>

</body>
</html>
