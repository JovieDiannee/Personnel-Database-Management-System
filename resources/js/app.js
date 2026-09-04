import './bootstrap';

import Alpine from 'alpinejs';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

window.Alpine = Alpine;
Alpine.start();

function initializeSearchableDropdowns() {
    document.querySelectorAll('.searchable-dropdown').forEach((element) => {
        if (element.tomselect) {
            return;
        }

        new TomSelect(element, {
            create: false,
            allowEmptyOption: true,
            maxOptions: 100,
            closeAfterSelect: true,
            selectOnTab: true,
            searchField: ['text'],
            placeholder:
                element.dataset.placeholder ||
                'Search and select...',
        });
    });
}

document.addEventListener(
    'DOMContentLoaded',
    initializeSearchableDropdowns
);