function savedMode(key, fallback) {
    try {
        const value = localStorage.getItem(key);
        return value === 'card' || value === 'table' ? value : fallback;
    } catch (_) {
        return fallback;
    }
}

function viewToolbar(changeMode) {
    const toolbar = document.createElement('div');
    toolbar.className = 'record-view-toolbar';
    toolbar.innerHTML = '<span class="record-view-label">Tampilan</span><div class="record-view-buttons" role="group" aria-label="Pilih tampilan daftar"><button type="button" class="record-view-button" data-mode="card" aria-pressed="false">Card</button><button type="button" class="record-view-button" data-mode="table" aria-pressed="false">Table</button></div>';
    toolbar.querySelectorAll('button').forEach((button) => {
        button.addEventListener('click', () => changeMode(button.dataset.mode));
    });
    return toolbar;
}

function updateToolbar(toolbar, mode) {
    toolbar.querySelectorAll('button').forEach((button) => {
        button.setAttribute('aria-pressed', String(button.dataset.mode === mode));
    });
}

export default function initRecordViews() {
    document.querySelectorAll('[data-record-views]').forEach((root, index) => {
        const cards = root.querySelector('[data-record-cards]');
        const table = root.querySelector('[data-record-table]');
        if (!cards || !table) return;

        const key = `record-view:${location.pathname}:${index}`;
        const toolbar = viewToolbar(setMode);
        root.prepend(toolbar);

        function setMode(mode) {
            cards.hidden = mode === 'table';
            table.hidden = mode === 'card';
            updateToolbar(toolbar, mode);
            try { localStorage.setItem(key, mode); } catch (_) { /* The view still changes. */ }
        }

        setMode(savedMode(key, root.dataset.defaultView || 'card'));
    });

    const tables = [...document.querySelectorAll('table[data-record-view]')]
        .filter((table) => !table.closest('[data-record-views]'));
    if (!tables.length) return;

    tables.forEach((table) => {
        const headings = [...table.querySelectorAll('thead th')].map((cell) => cell.textContent.trim());
        table.querySelectorAll('tbody tr').forEach((row) => {
            [...row.children].forEach((cell, index) => {
                if (!cell.hasAttribute('data-label')) cell.dataset.label = headings[index] || 'Pilih';
            });
        });
    });

    const firstSurface = tables[0].closest('.overflow-x-auto') || tables[0].parentElement;
    const key = `record-view:${location.pathname}`;
    const toolbar = viewToolbar(setMode);
    firstSurface.before(toolbar);

    function setMode(mode) {
        tables.forEach((table) => {
            table.classList.toggle('record-view-as-cards', mode === 'card');
            const surface = table.closest('.overflow-x-auto') || table.parentElement;
            surface.classList.toggle('record-view-card-wrap', mode === 'card');
        });
        document.querySelectorAll('[data-record-mobile-cards]').forEach((cards) => {
            cards.classList.toggle('record-view-show-mobile-cards', mode === 'card');
        });
        updateToolbar(toolbar, mode);
        try { localStorage.setItem(key, mode); } catch (_) { /* The view still changes. */ }
    }

    setMode(savedMode(key, 'table'));
}
