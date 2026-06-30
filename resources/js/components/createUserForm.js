import { useFormSubmission } from '../mixins.js';

export function createUserForm() {
    return {
        ...useFormSubmission({ url: '/users', method: 'POST', successMessage: 'Usuário criado com sucesso!', redirectTo: '/users' }),

        form: { name: '', email: '', password: '', password_confirmation: '', profile: '' },
    };
}
