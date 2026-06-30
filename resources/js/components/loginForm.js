import { useFormSubmission } from '../mixins.js';

export function loginForm() {
    return {
        ...useFormSubmission({ url: '/login', method: 'POST', successMessage: 'Login realizado!', redirectTo: '/' }),

        form: { email: '', password: '' },

        async handleLogin() {
            this.resetErrors();
            this.loading = true;

            try {
                await fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'same-origin' });

                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': window.getXsrfToken(),
                    },
                    body: JSON.stringify(this.form),
                    credentials: 'same-origin',
                });

                const data = await response.json();

                if (response.ok) {
                    window.showAlert('Login realizado!', 'success');
                    setTimeout(() => { window.location.href = '/'; }, 1500);
                } else if (response.status === 422 && data.errors) {
                    this.errors = Object.fromEntries(
                        Object.entries(data.errors).map(([field, messages]) => [field, messages[0]])
                    );
                } else {
                    this.errors = {};
                    window.showAlert('Usuário ou senha inválidos.', 'error');
                }
            } catch (error) {
                window.showAlert('Usuário ou senha inválidos.', 'error');
            } finally {
                this.loading = false;
            }
        }
    };
}
