<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1"><i class="bi bi-plus-circle me-2 text-primary"></i>Ajouter une Mission</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="missions.php?action=list" class="btn btn-outline-secondary btn-sm">
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

        <form action="missions.php?action=create" method="POST" id="missionForm">
            <div class="mb-3">
                <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" id="titre" class="form-control" placeholder="Entrez le titre de la mission">
                <div class="text-danger small mt-1" id="titreError" style="display:none;">Le titre est obligatoire.</div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description de la mission..."></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                    <div class="text-danger small mt-1" id="dateDebutError" style="display:none;">La date de début est obligatoire.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Date de fin <span class="text-danger">*</span></label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                    <div class="text-danger small mt-1" id="dateFinError" style="display:none;">La date de fin est obligatoire.</div>
                    <div class="text-danger small mt-1" id="dateCompareError" style="display:none;">La date de fin doit être postérieure à la date de début.</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">État / Statut <span class="text-danger">*</span></label>
                <select name="etat" id="etat" class="form-select">
                    <option value="Planifiée">Planifiée</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminée">Terminée</option>
                    <option value="Annulée">Annulée</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('missionForm').addEventListener('submit', function(e) {
    let isValid = true;
    const titre = document.getElementById('titre').value.trim();
    const dateDebut = document.getElementById('date_debut').value.trim();
    const dateFin = document.getElementById('date_fin').value.trim();

    if (titre === '') {
        document.getElementById('titreError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('titreError').style.display = 'none';
    }

    if (dateDebut === '') {
        document.getElementById('dateDebutError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('dateDebutError').style.display = 'none';
    }

    if (dateFin === '') {
        document.getElementById('dateFinError').style.display = 'block';
        isValid = false;
    } else {
        document.getElementById('dateFinError').style.display = 'none';
    }

    if (dateDebut !== '' && dateFin !== '') {
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
