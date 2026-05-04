<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-plus-circle me-2 text-primary"></i>Ajouter un Matériel</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="materiels.php?action=list" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Retour à la liste
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="materiels.php?action=create" method="POST" id="materielForm">
            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom du matériel">
                <div class="text-danger small mt-1" id="nomError" style="display:none;">Le nom est obligatoire.</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description du matériel..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">État <span class="text-danger">*</span></label>
                <select name="etat" id="etat" class="form-select">
                    <option value="">Sélectionnez un état</option>
                    <option value="Disponible">Disponible</option>
                    <option value="En panne">En panne</option>
                    <option value="En réparation">En réparation</option>
                </select>
                <div class="text-danger small mt-1" id="etatError" style="display:none;">L'état est obligatoire.</div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Enregistrer</button>
            </div>
        </form>
    </div>
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
