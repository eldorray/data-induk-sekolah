import './bootstrap';

import Alpine from 'alpinejs';

// Prevent double initialization
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
