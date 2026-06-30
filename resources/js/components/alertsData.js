export function alertsData() {
    return {
        alerts: [],

        addAlert(message, type = 'error') {
            this.alerts.push({ message, type, visible: true });
        },

        dismissAlert(index) {
            const alert = this.alerts[index];
            if (!alert) return;
            alert.visible = false;
            setTimeout(() => {
                const idx = this.alerts.indexOf(alert);
                if (idx > -1) this.alerts.splice(idx, 1);
            }, 300);
        },
    };
}

window.showAlert = function (message, type = 'error') {
    const el = document.querySelector('[x-data="alertsData()"]');
    if (!el) return;
    Alpine.$data(el).addAlert(message, type);
};
