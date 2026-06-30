import Alpine from 'alpinejs';
import './api.js';

function appLayout() {
    return {
        isActive(path) {
            return window.location.pathname === path ? 'bg-blue-50 text-blue-700 font-medium' : '';
        }
    }
}

window.appLayout = appLayout;

Alpine.start();
