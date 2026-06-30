import { useFormSubmission } from '../mixins.js';

export function editUserForm(publicId) {
    return {
        ...useFormSubmission({ url: `/users/${publicId}`, method: 'PUT', successMessage: 'Usuário atualizado com sucesso!', redirectTo: '/users' }),

        form: { name: '', profile: '' },

        async init() {
            const { success, data } = await window.api(`/users/${publicId}`);
            if (success) {
                this.form.name = data.name;
                this.form.profile = data.profile;
            }
        },
    };
}
