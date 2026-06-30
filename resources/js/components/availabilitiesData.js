import { useDataLoader } from '../mixins.js';

export function availabilitiesData() {
    return {
        ...useDataLoader('/availabilities', 'availabilities'),

        dayLabels: { 0: 'Domingo', 1: 'Segunda-feira', 2: 'Terça-feira', 3: 'Quarta-feira', 4: 'Quinta-feira', 5: 'Sexta-feira', 6: 'Sábado' },

        async init() {
            await this.loadData();
        },

        getDayLabel(day) {
            return this.dayLabels[day] || '-';
        },
    };
}
