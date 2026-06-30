import { useFormSubmission } from '../mixins.js';

export function createAvailabilityForm(availabilityId) {
    return {
        ...useFormSubmission({
            url: availabilityId ? `/availabilities/${availabilityId}` : '/availabilities',
            method: availabilityId ? 'PUT' : 'POST',
            successMessage: availabilityId ? 'Disponibilidade atualizada!' : 'Disponibilidade criada!',
            redirectTo: '/availabilities',
        }),

        isEdit: !!availabilityId,
        form: { user_id: '', day_of_week: '', start_time: '', end_time: '', is_active: true },
        attendants: [],

        async init() {
            const { success, data } = await window.api('/users');
            if (success) {
                this.attendants = data.filter(u => u.profile === 'atendente');
            }
            if (availabilityId) {
                const { success: loaded, data: avail } = await window.api(`/availabilities/${availabilityId}`);
                if (loaded) {
                    this.form = {
                        user_id: avail.user.public_id,
                        day_of_week: avail.day_of_week.toString(),
                        start_time: avail.start_time,
                        end_time: avail.end_time,
                        is_active: avail.is_active,
                    };
                }
            }
        },
    };
}
