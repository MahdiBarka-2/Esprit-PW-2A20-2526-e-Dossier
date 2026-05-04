<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>E-Municipality - FrontOffice</title>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; }
        header { background-color: #2c3e50; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #fff; text-decoration: none; margin-left: 1rem; font-weight: bold; }
        nav a:hover { color: #3498db; }
        .container { padding: 2rem; max-width: 1200px; margin: auto; }
        .card { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        h1, h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #34495e; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <header>
        <div class="logo">E-Municipality</div>
        <nav>
            <a href="index.php?action=front_home">Accueil</a>
            <a href="index.php?action=front_materiels">Matériels</a>
            <a href="index.php?action=front_missions">Missions</a>
            <a href="index.php?action=materiel_list">Accès BackOffice</a>
        </nav>
    </header>
    <div class="container">
        <?php echo $content; ?>
    </div>
</body>
</html>
