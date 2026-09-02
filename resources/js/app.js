import './bootstrap';
import TomSelect from 'tom-select';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.searchable-dropdown').forEach(function (element) {
        new TomSelect(element, {
            create: false,
            allowEmptyOption: true,
            maxOptions: 100,
            closeAfterSelect: true,
            selectOnTab: true,
            placeholder: element.dataset.placeholder || 'Search and select...',
            searchField: ['text'],
        });
    });
});