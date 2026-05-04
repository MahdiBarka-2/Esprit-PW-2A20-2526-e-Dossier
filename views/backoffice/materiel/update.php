<?php ob_start(); ?>
<h1>Modifier un Matériel</h1>

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

    <form action="index.php?action=materiel_update&id=<?php echo $materiel->getId(); ?>" method="POST" id="materielForm">
        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($materiel->getNom()); ?>">
            <span class="error-msg" id="nomError">Le nom est obligatoire.</span>
        </div>
        <div class="form-group">
            <label>Description :</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($materiel->getDescription()); ?></textarea>
        </div>
        <div class="form-group">
            <label>État :</label>
            <select name="etat" id="etat">
                <option value="">Sélectionnez un état</option>
                <option value="Disponible" <?php echo $materiel->getEtat() == 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                <option value="En maintenance" <?php echo $materiel->getEtat() == 'En maintenance' ? 'selected' : ''; ?>>En maintenance</option>
                <option value="En mission" <?php echo $materiel->getEtat() == 'En mission' ? 'selected' : ''; ?>>En mission</option>
            </select>
            <span class="error-msg" id="etatError">L'état est obligatoire.</span>
        </div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>

<script>
document.getElementById('materielForm').addEventListener('submit', function(e) {
    let isValid = true;
    const nom = document.getElementById('nom').value.trim();
    const etat = document.getElementById('etat').value.trim();

    if (nom === '') {
        document.getElementById('nomError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('nomError').style.display = 'none';
    }

    if (etat === '') {
        document.getElementById('etatError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('etatError').style.display = 'none';
    }

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php'; ?>
