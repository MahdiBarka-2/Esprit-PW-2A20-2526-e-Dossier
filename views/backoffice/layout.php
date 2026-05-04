<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BackOffice - Administration</title>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; background-color: #f4f7f6; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #2c3e50; color: #fff; padding-top: 2rem; }
        .sidebar h2 { text-align: center; margin-bottom: 2rem; }
        .sidebar a { display: block; color: #ecf0f1; padding: 1rem 2rem; text-decoration: none; }
        .sidebar a:hover { background-color: #34495e; }
        .main-content { flex: 1; padding: 2rem; }
        .card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .btn { padding: 10px 15px; background: #3498db; color: #fff; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #34495e; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: .5rem; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error { color: #e74c3c; margin-bottom: 1rem; }
        .error-msg { color: #e74c3c; font-size: 0.9em; display: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>BackOffice</h2>
        <a href="index.php?action=front_home">⬅ Retour FrontOffice</a>
        <a href="index.php?action=backoffice_dashboard">📊 Tableau de bord</a>
        <a href="index.php?action=materiel_list">📦 Gérer Matériels</a>
        <a href="index.php?action=mission_list">🎯 Gérer Missions</a>
    </div>
    <div class="main-content">
        <?php echo $content; ?>
    </div>
</body>
</html>
