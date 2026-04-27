// ── FILE HANDLING ──

var dropZone = document.getElementById('drop-zone');
var fileInput = document.getElementById('file-input');
var fileListEl = document.getElementById('file-list');

var files = [];

if (dropZone && fileInput) {
  dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropZone.classList.add('dragover');
  });

  dropZone.addEventListener('dragleave', function() {
    dropZone.classList.remove('dragover');
  });

  dropZone.addEventListener('click', function() {
    fileInput.click();
  });

  dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    addFiles(Array.from(e.dataTransfer.files));
  });

  fileInput.addEventListener('change', function() {
    addFiles(Array.from(fileInput.files));
    fileInput.value = '';
  });
}

function addFiles(newFiles) {
  for (var i = 0; i < newFiles.length; i++) {
    var f = newFiles[i];
    var existe = false;
    for (var j = 0; j < files.length; j++) {
      if (files[j].name === f.name && files[j].size === f.size) {
        existe = true;
        break;
      }
    }
    if (!existe) files.push(f);
  }
  renderFiles();
}

function removeFile(index) {
  files.splice(index, 1);
  renderFiles();
}

function getFileBadge(name) {
  var ext = name.split('.').pop().toLowerCase();
  if (ext === 'pdf') return { cls: 'badge-pdf', label: 'PDF' };
  if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') return { cls: 'badge-img', label: ext.toUpperCase() };
  if (ext === 'doc' || ext === 'docx') return { cls: 'badge-doc', label: 'DOC' };
  return { cls: 'badge-other', label: ext.toUpperCase().slice(0, 4) };
}

function formatSize(bytes) {
  if (bytes < 1024) return bytes + ' o';
  if (bytes < 1048576) return Math.round(bytes / 1024) + ' Ko';
  return (bytes / 1048576).toFixed(1) + ' Mo';
}

function renderFiles() {
  if (!fileListEl) return;
  var html = '';
  for (var i = 0; i < files.length; i++) {
    var badge = getFileBadge(files[i].name);
    html += '<div class="file-item">';
    html += '<div class="file-badge ' + badge.cls + '">' + badge.label + '</div>';
    html += '<span class="file-name">' + files[i].name + '</span>';
    html += '<span class="file-size">' + formatSize(files[i].size) + '</span>';
    html += '<button class="file-del" onclick="removeFile(' + i + ')" title="Supprimer">x</button>';
    html += '</div>';
  }
  fileListEl.innerHTML = html;
}

// ── VALIDATION ──

function valider() {
  var nom = document.getElementById('nom').value.trim();
  var email = document.getElementById('email').value.trim();
  var jobId = document.getElementById('job-id').value;

  if (nom === '') {
    alert('Veuillez saisir votre nom complet.');
    return false;
  }

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (email === '' || !emailRegex.test(email)) {
    alert('Veuillez saisir une adresse e-mail valide.');
    return false;
  }

  if (jobId === '') {
    alert('Veuillez selectionner un job.');
    return false;
  }

  return true;
}

// ── SOUMETTRE / MODIFIER ──

function traiterCandidature(mode) {
  if (!valider()) return;

  var ref = document.getElementById('candidature-ref').value.trim();
  if (ref === '') {
    ref = genererRef();
  }

  document.getElementById('rh-title').textContent = (mode === 'create') ? 'Candidature envoyee' : 'Candidature mise a jour';
  document.getElementById('rh-id').textContent = 'Reference : ' + ref + ' · ' + new Date().toLocaleDateString('fr-FR');

  if (mode === 'create') {
    document.getElementById('ai-text').textContent = 'Votre candidature a ete enregistree. Conservez la reference pour suivre ou modifier votre dossier.';
  } else {
    document.getElementById('ai-text').textContent = 'Votre candidature a ete mise a jour avec succes.';
  }

  var resultSection = document.getElementById('result-section');
  resultSection.classList.remove('hidden');
  resultSection.scrollIntoView({ behavior: 'smooth' });
}

function genererRef() {
  var n = Math.floor(1000 + Math.random() * 9000);
  return 'CAND-' + new Date().getFullYear() + '-' + n;
}

function resetForm() {
  document.getElementById('nom').value = '';
  document.getElementById('email').value = '';
  document.getElementById('candidature-ref').value = '';
  document.getElementById('description').value = '';
  document.getElementById('job-id').value = '';

  files = [];
  renderFiles();

  document.getElementById('result-section').classList.add('hidden');
  document.querySelector('.main').scrollIntoView({ behavior: 'smooth' });
}

// ── BOUTONS ──

var submitBtn = document.getElementById('submit-btn');
var updateBtn = document.getElementById('update-btn');
var resetBtn = document.getElementById('reset-btn');

if (submitBtn) {
  submitBtn.addEventListener('click', function(e) {
    e.preventDefault();
    traiterCandidature('create');
  });
}

if (updateBtn) {
  updateBtn.addEventListener('click', function(e) {
    e.preventDefault();
    traiterCandidature('update');
  });
}

if (resetBtn) {
  resetBtn.addEventListener('click', function(e) {
    e.preventDefault();
    resetForm();
  });
}