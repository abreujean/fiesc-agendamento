const API_BASE = '/api';

function getXsrfToken() {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return match ? decodeURIComponent(match[1]) : null;
}

async function apiFetch(url, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
    };

    const method = (options.method || 'GET').toUpperCase();
    if (method !== 'GET' && method !== 'HEAD') {
        const token = getXsrfToken();
        if (token) headers['X-XSRF-TOKEN'] = token;
    }

    try {
        const response = await fetch(`${API_BASE}${url}`, {
            ...options,
            headers,
            credentials: 'same-origin',
        });

        const data = await response.json();

        if (response.status === 401) {
            window.location.href = '/login';
            return { success: false, data, status: 401 };
        }

        if (!response.ok) {
            window.dispatchEvent(new CustomEvent('api-error', {
                detail: { message: data.message || 'Erro inesperado.', status: response.status },
            }));

            return { success: false, data, status: response.status };
        }

        return { success: true, data, status: response.status };
    } catch (error) {
        window.dispatchEvent(new CustomEvent('api-error', {
            detail: { message: 'Erro de conexão com o servidor.', status: 0 },
        }));

        return { success: false, data: { message: 'Erro de conexão com o servidor.' }, status: 0 };
    }
}

function logout() {
    apiFetch('/logout', { method: 'POST' }).finally(() => {
        window.location.href = '/login';
    });
}

window.api = apiFetch;
window.logout = logout;
window.getXsrfToken = getXsrfToken;
