

const jobForm = {
  titre: document.getElementById('job-titre'),
  ref: document.getElementById('job-ref'),
  contrat: document.getElementById('job-contrat'),
  lieu: document.getElementById('job-lieu'),
  desc: document.getElementById('job-description'),
  statut: document.getElementById('job-statut'),
  deadline: document.getElementById('job-deadline')
};

const jobSaveBtn = document.getElementById('job-save-btn');
const jobUpdateBtn = document.getElementById('job-update-btn');
const jobCancelEditBtn = document.getElementById('job-cancel-edit-btn');
const jobsTable = document.getElementById('jobs-table-admin').querySelector('tbody');
const jobSearchInput = document.getElementById('job-admin-search');

let editingJobId = null;

if (jobSaveBtn) jobSaveBtn.addEventListener('click', e => { e.preventDefault(); saveJob(); });
if (jobUpdateBtn) jobUpdateBtn.addEventListener('click', e => { e.preventDefault(); updateJob(); });
if (jobCancelEditBtn) jobCancelEditBtn.addEventListener('click', e => { e.preventDefault(); resetJobForm(); });

if (jobsTable) {
  jobsTable.addEventListener('click', e => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const tr = btn.closest('tr');
    const action = btn.getAttribute('data-action');
    const jobId = tr.getAttribute('data-job-id');

    if (action === 'edit-job') startEditJob(tr, jobId);
    if (action === 'delete-job') deleteJob(tr, jobId);
  });
}

if (jobSearchInput) {
  jobSearchInput.addEventListener('input', () => {
    const term = jobSearchInput.value.toLowerCase();
    [...jobsTable.rows].forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(term) ? '' : 'none';
    });
  });
}

function validateJobForm() {
  if (!jobForm.titre.value.trim()) { alert('Titre du job obligatoire.'); return false; }
  if (!jobForm.desc.value.trim()) { alert('Description du job obligatoire.'); return false; }
  return true;
}

function saveJob() {
  if (!validateJobForm()) return;


  const id = Date.now();

  const tr = document.createElement('tr');
  tr.setAttribute('data-job-id', id);
  tr.innerHTML = `
    <td>${jobForm.ref.value || ('JOB-' + id)}</td>
    <td>${jobForm.titre.value}</td>
    <td>${jobForm.contrat.value || '-'}</td>
    <td>${jobForm.lieu.value || '-'}</td>
    <td><span class="pill ${jobForm.statut.value === 'publie' ? 'pill-ok' : 'pill-warn'}">
      ${labelStatutJob(jobForm.statut.value)}
    </span></td>
    <td>${jobForm.deadline.value || '-'}</td>
    <td>
      <button class="btn-icon" data-action="edit-job">✎</button>
      <button class="btn-icon danger" data-action="delete-job">🗑</button>
    </td>
  `;
  jobsTable.appendChild(tr);
  resetJobForm();
  updateStats();
}

function startEditJob(tr, jobId) {
  editingJobId = jobId;
  const tds = tr.querySelectorAll('td');

  jobForm.ref.value = tds[0].innerText.trim();
  jobForm.titre.value = tds[1].innerText.trim();
  jobForm.contrat.value = tds[2].innerText.trim() === '-' ? '' : tds[2].innerText.trim();
  jobForm.lieu.value = tds[3].innerText.trim() === '-' ? '' : tds[3].innerText.trim();
  jobForm.statut.value = statutValueFromLabel(tds[4].innerText.trim());
  jobForm.deadline.value = tds[5].innerText.trim() === '-' ? '' : formatDateInput(tds[5].innerText.trim());

  document.getElementById('job-form-title').textContent = 'Modifier le job';
  jobSaveBtn.classList.add('hidden');
  jobUpdateBtn.classList.remove('hidden');
  jobCancelEditBtn.classList.remove('hidden');

  // TODO: récupérer description depuis backend si besoin
}

function updateJob() {
  if (!validateJobForm()) return;
  if (!editingJobId) return;

  const tr = jobsTable.querySelector(`tr[data-job-id="${editingJobId}"]`);
  if (!tr) return;
  const tds = tr.querySelectorAll('td');

  tds[0].innerText = jobForm.ref.value || tds[0].innerText;
  tds[1].innerText = jobForm.titre.value;
  tds[2].innerText = jobForm.contrat.value || '-';
  tds[3].innerText = jobForm.lieu.value || '-';
  tds[4].innerHTML = `<span class="pill ${jobForm.statut.value === 'publie' ? 'pill-ok' : 'pill-warn'}">
    ${labelStatutJob(jobForm.statut.value)}
  </span>`;
  tds[5].innerText = jobForm.deadline.value || '-';

  // TODO: appel AJAX vers JobController::update(editingJobId)

  resetJobForm();
  updateStats();
}

function deleteJob(tr, jobId) {
  if (!confirm('Supprimer définitivement ce job ?')) return;

  // TODO: appel AJAX vers JobController::delete(jobId)

  tr.remove();
  updateStats();
}

function resetJobForm() {
  Object.values(jobForm).forEach(el => { if (el) el.value = ''; });
  jobForm.statut.value = 'brouillon';

  document.getElementById('job-form-title').textContent = 'Ajouter un job';
  editingJobId = null;
  jobSaveBtn.classList.remove('hidden');
  jobUpdateBtn.classList.add('hidden');
  jobCancelEditBtn.classList.add('hidden');
}

function labelStatutJob(v) {
  if (v === 'publie') return 'Publié';
  if (v === 'ferme') return 'Fermé';
  return 'Brouillon';
}
function statutValueFromLabel(lab) {
  lab = lab.toLowerCase();
  if (lab.includes('publi')) return 'publie';
  if (lab.includes('ferm')) return 'ferme';
  return 'brouillon';
}
function formatDateInput(fr) {
  // "30/05/2025" -> "2025-05-30"
  const p = fr.split('/');
  if (p.length !== 3) return '';
  return `${p[2]}-${p[1]}-${p[0]}`;
}

// ── CANDIDATURES : TABLE, APPROUVER / REFUSER ──

const candsTable = document.getElementById('cands-table-admin').querySelector('tbody');
const candSearchInp = document.getElementById('cand-search');

const candDetailTitle = document.getElementById('cand-detail-title');
const candDetailRef = document.getElementById('cand-detail-ref');
const candDetailBody = document.getElementById('cand-detail-body');

if (candsTable) {
  candsTable.addEventListener('click', e => {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const tr = btn.closest('tr');
    const action = btn.getAttribute('data-action');
    const candId = tr.getAttribute('data-cand-id');

    if (action === 'approve-cand') changeCandStatus(tr, candId, 'approuve');
    if (action === 'reject-cand') changeCandStatus(tr, candId, 'refuse');
    if (action === 'view-cand') showCandDetail(tr, candId);
  });
}

if (candSearchInp) {
  candSearchInp.addEventListener('input', () => {
    const term = candSearchInp.value.toLowerCase();
    [...candsTable.rows].forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(term) ? '' : 'none';
    });
  });
}

function changeCandStatus(tr, candId, statut) {
  // TODO: appel AJAX vers CandidatureController::updateStatut(candId, statut)
  const tdStatut = tr.querySelectorAll('td')[4];
  if (statut === 'approuve') {
    tdStatut.innerHTML = '<span class="pill pill-ok">Approuvée</span>';
  } else {
    tdStatut.innerHTML = '<span class="pill pill-err">Refusée</span>';
  }
  updateStats();
}

function showCandDetail(tr, candId) {
  const tds = tr.querySelectorAll('td');
  const ref = tds[0].innerText;
  const nom = tds[1].innerText;
  const job = tds[2].innerText;
  const date = tds[3].innerText;
  const statut = tds[4].innerText;

  candDetailTitle.textContent = 'Candidature de ' + nom;
  candDetailRef.textContent = `Référence : ${ref} · Job : ${job}`;
  candDetailBody.textContent = `Date de dépôt : ${date}. Statut actuel : ${statut}. 
Pour voir le détail complet (CV, lettre, pièces jointes), ouvrez la fiche dans le BackOffice RH.`;

  candDetailCard.classList.remove('hidden');
  candDetailCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── EXPORT TABLE -> EXCEL (XLS) ──

const exportJobsBtn = document.getElementById('export-jobs-btn');
const exportCandsBtn = document.getElementById('export-cands-btn');

if (exportJobsBtn) exportJobsBtn.addEventListener('click', () => exportTableToExcel('jobs-table-admin', 'jobs'));
if (exportCandsBtn) exportCandsBtn.addEventListener('click', () => exportTableToExcel('cands-table-admin', 'candidatures'));

function exportTableToExcel(tableId, filename) {
  const table = document.getElementById(tableId);
  if (!table) return;

  const html = table.outerHTML.replace(/ /g, '%20');
  const uri = 'data:application/vnd.ms-excel;charset=utf-8,' + html;
  const link = document.createElement('a');
  link.href = uri;
  link.download = filename + '.xls';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

// ── STATS (FAKE, basées sur les tables affichées) ──

function updateStats() {
  const jobsRows = jobsTable ? [...jobsTable.rows] : [];
  const candsRows = candsTable ? [...candsTable.rows] : [];

  const jobsPublies = jobsRows.filter(r => r.innerText.toLowerCase().includes('publié')).length;
  const candsAttente = candsRows.filter(r => r.innerText.toLowerCase().includes('en attente')).length;
  const candsOk = candsRows.filter(r => r.innerText.toLowerCase().includes('approuvée')).length;
  const candsKo = candsRows.filter(r => r.innerText.toLowerCase().includes('refusée')).length;

  setText('stat-jobs', jobsRows.length);
  setText('stat-cands', candsRows.length);
  setText('stat-approuves', candsOk);

  setText('side-jobs-publies', jobsPublies);
  setText('side-cands-attente', candsAttente);
  setText('side-cands-ok', candsOk);
  setText('side-cands-ko', candsKo);
}

function setText(id, value) {
  const el = document.getElementById(id);
  if (el) el.textContent = value;
}

// mise à jour initiale
updateStats();