const API_URL = new URL('../backend/api/controllers/controllerRayon.php', window.location.href).toString();
const tableBody = document.getElementById('rayon-table-body');
const rayonForm = document.getElementById('rayon-form');
const nomRayonInput = document.getElementById('nom_rayon');
const emplacementInput = document.getElementById('emplacement');
const formSection = document.getElementById('section-form');
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
    formSection.classList.add('hidden');
}

function renderRayons(rayons) {
    tableBody.innerHTML = '';

    rayons.forEach((rayon) => {
        const row = document.createElement('tr');
        const idCell = document.createElement('td');
        const nameCell = document.createElement('td');
        const locationCell = document.createElement('td');

        idCell.className = 'px-6 py-4 text-sm text-slate-500';
        nameCell.className = 'px-6 py-4 text-sm font-medium text-slate-700';
        locationCell.className = 'px-6 py-4 text-sm text-slate-600';
        idCell.textContent = rayon.id;
        nameCell.textContent = rayon.nom;
        locationCell.textContent = rayon.emplacement || '-';

        row.append(idCell, nameCell, locationCell);
        tableBody.appendChild(row);
    });
}

function loadRayons() {
    fetch(API_URL)
        .then((response) => {
            if (!response.ok) {
                throw new Error('Impossible de charger la liste des rayons.');
            }

            return response.json();
        })
        .then(renderRayons)
        .catch((error) => showToast(error.message, 'error'));
}

rayonForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const data = {
        nom: nomRayonInput.value.trim(),
        emplacement: emplacementInput.value.trim(),
    };

    fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json().then((result) => {
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Impossible d’ajouter le rayon.');
            }

            return result;
        }))
        .then(() => {
            showToast('Rayon ajoute avec succes.');
            resetForm();
            loadRayons();
        })
        .catch((error) => showToast(error.message, 'error'));
});

newRayonBtn.addEventListener('click', () => {
    formSection.classList.remove('hidden');
    nomRayonInput.focus();
});

cancelRayonBtn.addEventListener('click', resetForm);

document.addEventListener('DOMContentLoaded', loadRayons);
