const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const test = require('node:test');
const vm = require('node:vm');

const appSource = fs.readFileSync(
    path.join(__dirname, '..', 'frontend', 'assets', 'app.js'),
    'utf8',
);

class FakeElement {
    constructor() {
        this.children = [];
        this.listeners = new Map();
        this.parentElement = null;
        this.textContent = '';
        this.value = '';
        this.className = '';
        this.focused = false;
        this.classList = {
            values: new Set(),
            add: (value) => this.classList.values.add(value),
            remove: (value) => this.classList.values.delete(value),
            contains: (value) => this.classList.values.has(value),
        };
    }

    set innerHTML(value) {
        this.children = [];
        this.html = value;
    }

    addEventListener(type, listener) {
        this.listeners.set(type, listener);
    }

    append(...elements) {
        elements.forEach((element) => this.appendChild(element));
    }

    appendChild(element) {
        element.parentElement = this;
        this.children.push(element);
        return element;
    }

    remove() {
        if (this.parentElement) {
            this.parentElement.children = this.parentElement.children.filter(
                (child) => child !== this,
            );
            this.parentElement = null;
        }
    }

    focus() {
        this.focused = true;
    }

    reset() {
        this.onReset();
    }

    dispatch(type, event = {}) {
        const listener = this.listeners.get(type);
        assert.ok(listener, `Expected a ${type} listener`);
        return listener(event);
    }
}

function createApp(fetchImplementation = async () => {
    throw new Error('Unexpected fetch');
}) {
    const ids = [
        'rayon-table-body',
        'rayon-form',
        'nom_rayon',
        'emplacement',
        'section-form',
        'new-rayon-btn',
        'cancel-rayon-btn',
        'toast-container',
    ];
    const elements = Object.fromEntries(ids.map((id) => [id, new FakeElement()]));
    const timers = [];
    const documentListeners = new Map();
    const document = {
        getElementById: (id) => elements[id],
        createElement: () => new FakeElement(),
        addEventListener: (type, listener) => documentListeners.set(type, listener),
    };

    elements['section-form'].classList.add('hidden');
    elements['rayon-form'].onReset = () => {
        elements.nom_rayon.value = '';
        elements.emplacement.value = '';
    };

    const context = {
        document,
        fetch: fetchImplementation,
        URL,
        window: { location: { href: 'http://127.0.0.1:8000/frontend/index.html' } },
        setTimeout: (callback) => {
            timers.push(callback);
        },
    };
    vm.runInNewContext(appSource, context);

    return { context, documentListeners, elements, timers };
}

function response(payload, ok = true) {
    return { ok, json: async () => payload };
}

async function settlePromises() {
    await new Promise((resolve) => setImmediate(resolve));
}

test('showToast adds a toast and removes it after its timeout', () => {
    const { context, elements, timers } = createApp();

    context.showToast('Saved');

    assert.equal(elements['toast-container'].children.length, 1);
    assert.equal(elements['toast-container'].children[0].textContent, 'Saved');
    assert.match(elements['toast-container'].children[0].className, /bg-green-600/);
    timers[0]();
    assert.equal(elements['toast-container'].children.length, 0);
});

test('showToast uses the error style', () => {
    const { context, elements } = createApp();

    context.showToast('Failed', 'error');

    assert.match(elements['toast-container'].children[0].className, /bg-red-600/);
});

test('resetForm clears values and hides the form', () => {
    const { context, elements } = createApp();
    elements.nom_rayon.value = 'Fruits';
    elements.emplacement.value = 'A1';
    elements['section-form'].classList.remove('hidden');

    context.resetForm();

    assert.equal(elements.nom_rayon.value, '');
    assert.equal(elements.emplacement.value, '');
    assert.equal(elements['section-form'].classList.contains('hidden'), true);
});

test('renderRayons replaces rows and renders missing locations as a dash', () => {
    const { context, elements } = createApp();
    elements['rayon-table-body'].appendChild(new FakeElement());

    context.renderRayons([
        { id: 1, nom: 'Fruits', emplacement: 'A1' },
        { id: 2, nom: 'Boissons', emplacement: null },
    ]);

    const rows = elements['rayon-table-body'].children;
    assert.equal(rows.length, 2);
    assert.deepEqual(rows[0].children.map((cell) => cell.textContent), [1, 'Fruits', 'A1']);
    assert.deepEqual(rows[1].children.map((cell) => cell.textContent), [2, 'Boissons', '-']);
});

test('loadRayons fetches and renders the list', async () => {
    const rayons = [{ id: 1, nom: 'Fruits', emplacement: 'A1' }];
    const requests = [];
    const { context, elements } = createApp(async (...args) => {
        requests.push(args);
        return response(rayons);
    });

    context.loadRayons();
    await settlePromises();

    assert.equal(requests.length, 1);
    assert.equal(requests[0][0], 'http://127.0.0.1:8000/backend/api/controllers/controllerRayon.php');
    assert.equal(requests[0][1], undefined);
    assert.deepEqual(
        elements['rayon-table-body'].children[0].children.map((cell) => cell.textContent),
        [1, 'Fruits', 'A1'],
    );
});

test('loadRayons reports HTTP failures', async () => {
    const { context, elements } = createApp(async () => response({}, false));

    context.loadRayons();
    await settlePromises();

    assert.equal(elements['toast-container'].children[0].textContent, 'Impossible de charger la liste des rayons.');
});

test('the add button reveals the form and focuses the name input', () => {
    const { elements } = createApp();

    elements['new-rayon-btn'].dispatch('click');

    assert.equal(elements['section-form'].classList.contains('hidden'), false);
    assert.equal(elements.nom_rayon.focused, true);
});

test('submitting the form posts trimmed fields and refreshes the list', async () => {
    const requests = [];
    const { elements } = createApp(async (...args) => {
        requests.push(args);
        return requests.length === 1
            ? response({ success: true })
            : response([{ id: 3, nom: 'Fruits', emplacement: 'A1' }]);
    });
    elements.nom_rayon.value = '  Fruits  ';
    elements.emplacement.value = ' A1 ';
    elements['section-form'].classList.remove('hidden');
    let prevented = false;

    elements['rayon-form'].dispatch('submit', {
        preventDefault: () => {
            prevented = true;
        },
    });
    await settlePromises();

    assert.equal(prevented, true);
    assert.equal(requests.length, 2);
    assert.equal(requests[0][1].method, 'POST');
    assert.equal(requests[0][1].headers['Content-Type'], 'application/json');
    assert.deepEqual(JSON.parse(requests[0][1].body), { nom: 'Fruits', emplacement: 'A1' });
    assert.equal(elements['section-form'].classList.contains('hidden'), true);
    assert.deepEqual(
        elements['rayon-table-body'].children[0].children.map((cell) => cell.textContent),
        [3, 'Fruits', 'A1'],
    );
    assert.equal(elements['toast-container'].children[0].textContent, 'Rayon ajoute avec succes.');
});

test('submitting an unsuccessful response displays its error', async () => {
    const { elements } = createApp(async () => response({
        success: false,
        message: 'Le nom du rayon est requis.',
    }, false));

    elements['rayon-form'].dispatch('submit', { preventDefault() {} });
    await settlePromises();

    assert.equal(elements['toast-container'].children[0].textContent, 'Le nom du rayon est requis.');
    assert.match(elements['toast-container'].children[0].className, /bg-red-600/);
});

test('the cancel button resets the form', () => {
    const { elements } = createApp();
    elements.nom_rayon.value = 'Fruits';
    elements['section-form'].classList.remove('hidden');

    elements['cancel-rayon-btn'].dispatch('click');

    assert.equal(elements.nom_rayon.value, '');
    assert.equal(elements['section-form'].classList.contains('hidden'), true);
});

test('DOMContentLoaded loads the initial list', async () => {
    let fetchCount = 0;
    const { documentListeners } = createApp(async () => {
        fetchCount += 1;
        return response([]);
    });

    documentListeners.get('DOMContentLoaded')();
    await settlePromises();

    assert.equal(fetchCount, 1);
});
