export function appLayout() {
    return {
        isActive(path) {
            return window.location.pathname === path ? 'bg-secondary text-white font-semibold' : '';
        }
    }
}
