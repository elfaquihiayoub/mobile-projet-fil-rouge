const API_URL = '../backend/api.php';
const tableBody = document.getElementById('rayon-table-body');
const rayonForm = document.getElementById('rayon-form');
const nomRayonInput = document.getElementById('nom_rayon');
const emplacementInput = document.getElementById('emplacement');
const rayonFormTitle = document.getElementById('rayon-form-title');
const newRayonBtn = document.getElementById('new-rayon-btn');
const cancelRayonBtn = document.getElementById('cancel-rayon-btn');
let editingId = null;

function resetForm() {
    rayonForm.reset();
    editingId = null;
    rayonFormTitle.textContent = 'Ajouter un rayon';
}

function renderRayons(rayons) {
    tableBody.innerHTML = '';

    rayons.forEach((rayon) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-slate-500">${rayon.id_rayon}</td>
            <td class="px-6 py-4 text-sm font-medium text-slate-700">${rayon.nom_rayon}</td>
            <td class="px-6 py-4 text-sm text-slate-600">${rayon.emplacement || '-'}</td>
            <td class="px-6 py-4 text-sm">
                <div class="flex gap-3">
                    <button type="button" class="text-blue-600 hover:underline edit-btn" data-id="${rayon.id_rayon}">Modifier</button>
                    <button type="button" class="text-red-500 hover:underline delete-btn" data-id="${rayon.id_rayon}">Supprimer</button>
                </div>
            </td>
        `;
        tableBody.appendChild(row);
    });

    document.querySelectorAll('.edit-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const id = Number(button.dataset.id);
            const rayon = rayons.find((item) => item.id_rayon === id);

            if (!rayon) {
                return;
            }

            editingId = id;
            rayonFormTitle.textContent = 'Modifier un rayon';
            nomRayonInput.value = rayon.nom_rayon;
            emplacementInput.value = rayon.emplacement || '';
            nomRayonInput.focus();
        });
    });

    document.querySelectorAll('.delete-btn').forEach((button) => {
        button.addEventListener('click', async () => {
            const id = Number(button.dataset.id);

            const response = await fetch(API_URL, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_rayon: id }),
            });

            const result = await response.json();
            if (result.success) {
                loadRayons();
            }
        });
    });
}

async function loadRayons() {
    const response = await fetch(API_URL);
    const rayons = await response.json();
    renderRayons(rayons);
}

rayonForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const payload = {
        nom_rayon: nomRayonInput.value.trim(),
        emplacement: emplacementInput.value.trim(),
    };

    if (editingId !== null) {
        payload.id_rayon = editingId;
    }

    const method = editingId === null ? 'POST' : 'PUT';

    const response = await fetch(API_URL, {
        method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (result.success) {
        resetForm();
        loadRayons();
    }
});

newRayonBtn.addEventListener('click', () => {
    resetForm();
    nomRayonInput.focus();
});

cancelRayonBtn.addEventListener('click', resetForm);

document.addEventListener('DOMContentLoaded', () => {
    loadRayons();
});
