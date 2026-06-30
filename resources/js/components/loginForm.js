import { useFormSubmission } from '../mixins.js';

export function loginForm() {
    return {
        ...useFormSubmission({ url: '/login', method: 'POST', successMessage: 'Login realizado!', redirectTo: '/' }),

        form: { email: '', password: '' },

        async handleLogin() {
            this.resetErrors();
            this.loading = true;

            await fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'same-origin' });

            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(this.form),
                credentials: 'same-origin',
            });

            const data = await response.json();

            this.loading = false;

            if (response.ok) {
                window.showAlert('Login realizado!', 'success');
                setTimeout(() => { window.location.href = '/'; }, 1500);
            } else if (response.status === 422 && data.errors) {
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
