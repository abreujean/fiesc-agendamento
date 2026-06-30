import { useFormSubmission } from '../mixins.js';

export function loginForm() {
    return {
        ...useFormSubmission({ url: '/login', method: 'POST', successMessage: 'Login realizado!', redirectTo: '/' }),

        form: { email: '', password: '' },

        checkAuth() {
            if (window.getToken()) {
                window.location.href = '/';
            }
        },

        async handleLogin() {
            this.resetErrors();
            this.loading = true;

            const { success, data, status } = await window.api('/login', {
                method: 'POST',
                body: JSON.stringify(this.form),
            });

            this.loading = false;

            if (success) {
                window.setToken(data.token);
                window.setUser(data.user);
                window.location.href = '/';
            } else if (status === 422 && data.errors) {
                this.errors = Object.fromEntries(
                    Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                );
            } else {
                this.errors = {};
                window.showAlert(data.message || 'Erro ao fazer login.', 'error');
            }
        }
    };
}
