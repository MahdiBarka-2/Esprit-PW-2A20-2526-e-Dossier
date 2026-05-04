<?php ob_start(); ?>
<h1>Ajouter une Mission</h1>

<div class="card">
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?action=mission_create" method="POST" id="missionForm">
        <div class="form-group">
            <label>Titre :</label>
            <input type="text" name="titre" id="titre">
            <span class="error-msg" id="titreError">Le titre est obligatoire.</span>
        </div>
        <div class="form-group">
            <label>Description :</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div class="form-group">
            <label>Date de début (YYYY-MM-DD) :</label>
            <input type="text" name="date_debut" id="date_debut" placeholder="YYYY-MM-DD">
            <span class="error-msg" id="dateDebutError">Format de date invalide. Utilisez YYYY-MM-DD.</span>
        </div>
        <div class="form-group">
            <label>Date de fin (YYYY-MM-DD) :</label>
            <input type="text" name="date_fin" id="date_fin" placeholder="YYYY-MM-DD">
            <span class="error-msg" id="dateFinError">Format de date invalide. Utilisez YYYY-MM-DD.</span>
            <span class="error-msg" id="dateCompareError">La date de fin doit être postérieure à la date de début.</span>
        </div>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div>

<script>
document.getElementById('missionForm').addEventListener('submit', function(e) {
    let isValid = true;
    const titre = document.getElementById('titre').value.trim();
    const dateDebut = document.getElementById('date_debut').value.trim();
    const dateFin = document.getElementById('date_fin').value.trim();

    // Regex for YYYY-MM-DD
    const dateRegex = /^\d{4}-\d{2}-\d{2}$/;

    if (titre === '') {
        document.getElementById('titreError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('titreError').style.display = 'none';
    }

    if (!dateRegex.test(dateDebut)) {
        document.getElementById('dateDebutError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('dateDebutError').style.display = 'none';
    }

    if (!dateRegex.test(dateFin)) {
        document.getElementById('dateFinError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('dateFinError').style.display = 'none';
    }

    if (dateRegex.test(dateDebut) && dateRegex.test(dateFin)) {
        const d1 = new Date(dateDebut);
        const d2 = new Date(dateFin);
        if (d1 > d2) {
            document.getElementById('dateCompareError').style.display = 'block';
            isValid = false;
        } else {
            document.getElementById('dateCompareError').style.display = 'none';
        }
    } else {
        document.getElementById('dateCompareError').style.display = 'none';
    }

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
