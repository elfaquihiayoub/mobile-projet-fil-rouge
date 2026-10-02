const sidebar = document.querySelector('#sidebar');
document.querySelector('#menuToggle')?.addEventListener('click', () => sidebar?.classList.toggle('open'));

const modal = document.querySelector('#confirmModal');
let pendingForm = null;
document.querySelectorAll('[data-delete-form]').forEach((form) => {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    pendingForm = form;
    modal?.classList.add('open');
  });
});
document.querySelector('[data-close-modal]')?.addEventListener('click', () => modal?.classList.remove('open'));
document.querySelector('#confirmDelete')?.addEventListener('click', () => pendingForm?.submit());

const apiRayonUrl = 'index.php?controller=api&resource=rayon';
const rayonForm = document.getElementById('rayonForm');
const rayonTableBody = document.getElementById('rayonTableBody');
const rayonFormWrapper = document.getElementById('rayonFormWrapper');
const resetRayonForm = () => {
  rayonForm?.reset();
  document.getElementById('rayonId')?.setAttribute('value', '');
  rayonFormWrapper?.style.setProperty('display', 'none');
};

const escapeHtml = (value = '') => String(value)
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;')
  .replace(/'/g, '&#039;');

const renderRayonRow = (rayon) => `
  <tr data-rayon-id="${rayon.id_rayon}">
    <td>${escapeHtml(rayon.nom_rayon || '')}</td>
    <td>${escapeHtml(rayon.emplacement || '')}</td>
    <td class="actions">
      <a class="btn sm" href="index.php?controller=rayon&action=show&id=${rayon.id_rayon}">Voir</a>
      <button type="button" class="btn sm secondary" data-edit-rayon="${rayon.id_rayon}">Modifier</button>
      <button type="button" class="btn sm danger" data-delete-rayon="${rayon.id_rayon}">Supprimer</button>
    </td>
  </tr>
`;

async function loadRayons() {
  if (!rayonTableBody) {
    return;
  }

  try {
    const response = await fetch(apiRayonUrl, { method: 'GET' });
    const payload = await response.json();

    if (!response.ok || !payload.success) {
      throw new Error(payload.message || 'Erreur de chargement des rayons.');
    }

    rayonTableBody.innerHTML = (payload.data || []).map(renderRayonRow).join('');
  } catch (error) {
    rayonTableBody.innerHTML = `<tr><td colspan="3">${escapeHtml(error.message || 'Erreur de chargement')}</td></tr>`;
  }
}

rayonForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const id = document.getElementById('rayonId')?.value || '';
  const payload = {
    nom_rayon: document.getElementById('nom_rayon')?.value?.trim() || '',
    emplacement: document.getElementById('emplacement')?.value?.trim() || ''
  };

  const url = id ? `${apiRayonUrl}&id=${id}` : apiRayonUrl;
  const response = await fetch(url, {
    method: id ? 'PUT' : 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const message = data.message || 'Erreur lors de la soumission du rayon.';
    alert(message);
    return;
  }

  resetRayonForm();
  await loadRayons();
});

document.querySelector('[data-open-rayon-form]')?.addEventListener('click', () => {
  rayonFormWrapper?.style.setProperty('display', 'block');
  document.getElementById('nom_rayon')?.focus();
});

document.querySelector('[data-reset-rayon-form]')?.addEventListener('click', resetRayonForm);

rayonTableBody?.addEventListener('click', async (event) => {
  const editButton = event.target.closest('[data-edit-rayon]');
  if (editButton) {
    const id = editButton.getAttribute('data-edit-rayon');
    const response = await fetch(`${apiRayonUrl}&id=${id}`, { method: 'GET' });
    const payload = await response.json();

    if (!response.ok || !payload.success) {
      alert(payload.message || 'Impossible de charger le rayon.');
      return;
    }

    rayonFormWrapper?.style.setProperty('display', 'block');
    document.getElementById('rayonId')?.setAttribute('value', String(payload.data.id_rayon));
    document.getElementById('nom_rayon').value = payload.data.nom_rayon || '';
    document.getElementById('emplacement').value = payload.data.emplacement || '';
    document.getElementById('nom_rayon')?.focus();
    return;
  }

  const deleteButton = event.target.closest('[data-delete-rayon]');
  if (deleteButton) {
    const id = deleteButton.getAttribute('data-delete-rayon');
    const confirmed = window.confirm('Supprimer ce rayon ?');

    if (!confirmed) {
      return;
    }

    const response = await fetch(`${apiRayonUrl}&id=${id}`, { method: 'DELETE' });
    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
      alert(payload.message || 'Impossible de supprimer le rayon.');
      return;
    }

    await loadRayons();
  }
});

if (rayonTableBody) {
  loadRayons();
}
