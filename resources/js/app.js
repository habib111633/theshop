import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (document.getElementById('cartCount')) {
    document.getElementById('cartCount').textContent = data.count || 0;
}
