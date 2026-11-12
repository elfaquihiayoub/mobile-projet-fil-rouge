const API_URL = new URL('../backend/api/controllers/controllerRayon.php', window.location.href).toString();
const tableBody = document.getElementById('rayon-table-body');
const rayonForm = document.getElementById('rayon-form');
const rayonIdInput = document.getElementById('rayon_id');
const nomRayonInput = document.getElementById('nom_rayon');
const emplacementInput = document.getElementById('emplacement');
const formSection = document.getElementById('section-form');
const formTitle = document.getElementById('form-title');
const newRayonBtn = document.getElementById('new-rayon-btn');
const cancelRayonBtn = document.getElementById('cancel-rayon-btn');
const toastContainer = document.getElementById('toast-container');

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `rounded-lg px-4 py-3 text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    toast.textContent = message;
    toastContainer.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function resetForm() {
    rayonForm.reset();
    rayonIdInput.value = '';
    formTitle.textContent = 'Ajouter un rayon';
    formSection.classList.add('hidden');
}

function editRayon(rayon) {
    rayonIdInput.value = rayon.id;
    nomRayonInput.value = rayon.nom;
    emplacementInput.value = rayon.emplacement || '';
    formTitle.textContent = 'Modifier un rayon';
    formSection.classList.remove('hidden');
    nomRayonInput.focus();
}

function renderRayons(rayons) {
    tableBody.innerHTML = '';

    rayons.forEach((rayon) => {
        const row = document.createElement('tr');
        const idCell = document.createElement('td');
        const nameCell = document.createElement('td');
        const locationCell = document.createElement('td');
        const actionsCell = document.createElement('td');
        const editButton = document.createElement('button');
        const deleteButton = document.createElement('button');

        idCell.className = 'px-6 py-4 text-sm text-slate-500';
        nameCell.className = 'px-6 py-4 text-sm font-medium text-slate-700';
        locationCell.className = 'px-6 py-4 text-sm text-slate-600';
        actionsCell.className = 'space-x-3 px-6 py-4 text-sm';
        editButton.className = 'font-medium text-blue-600 hover:text-blue-800';
        deleteButton.className = 'font-medium text-red-600 hover:text-red-800';
        editButton.type = 'button';
        deleteButton.type = 'button';
        editButton.textContent = 'Modifier';
        deleteButton.textContent = 'Supprimer';
        editButton.addEventListener('click', () => editRayon(rayon));
        deleteButton.addEventListener('click', () => deleteRayon(rayon.id));

        idCell.textContent = rayon.id;
        nameCell.textContent = rayon.nom;
        locationCell.textContent = rayon.emplacement || '-';
        actionsCell.append(editButton, deleteButton);
        row.append(idCell, nameCell, locationCell, actionsCell);
        tableBody.appendChild(row);
    });
}

async function loadRayons() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) {
            throw new Error('Impossible de charger la liste des rayons.');
        }
        renderRayons(await response.json());
    } catch (error) {
        showToast(error.message, 'error');
    }
}

async function deleteRayon(id) {
    if (!window.confirm('Supprimer ce rayon ?')) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}?id=${encodeURIComponent(id)}`, { method: 'DELETE' });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Impossible de supprimer le rayon.');
        }
        showToast('Rayon supprime avec succes.');
        await loadRayons();
    } catch (error) {
        showToast(error.message, 'error');
    }
}

rayonForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    const id = rayonIdInput.value;
    const data = {
        nom: nomRayonInput.value.trim(),
        emplacement: emplacementInput.value.trim(),
    };

    try {
        const response = await fetch(id ? `${API_URL}?id=${encodeURIComponent(id)}` : API_URL, {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Impossible d’enregistrer le rayon.');
        }
        showToast(id ? 'Rayon modifie avec succes.' : 'Rayon ajoute avec succes.');
        resetForm();
        await loadRayons();
    } catch (error) {
        showToast(error.message, 'error');
    }
});

newRayonBtn.addEventListener('click', () => {
    resetForm();
    formSection.classList.remove('hidden');
    nomRayonInput.focus();
});

cancelRayonBtn.addEventListener('click', resetForm);
document.addEventListener('DOMContentLoaded', loadRayons);
