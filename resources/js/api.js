const API_BASE = '/api';

function getToken() {
    return localStorage.getItem('auth_token');
}

function setToken(token) {
    localStorage.setItem('auth_token', token);
}

function removeToken() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
}

function getUser() {
    const data = localStorage.getItem('auth_user');
    return data ? JSON.parse(data) : null;
}

function setUser(user) {
    localStorage.setItem('auth_user', JSON.stringify(user));
}

async function apiFetch(url, options = {}) {
    const token = getToken();

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    try {
        const response = await fetch(`${API_BASE}${url}`, {
            ...options,
            headers,
        });

        const data = await response.json();

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
        removeToken();
        window.location.href = '/login';
    });
}

window.api = apiFetch;
window.getToken = getToken;
window.setToken = setToken;
window.removeToken = removeToken;
window.getUser = getUser;
window.setUser = setUser;
window.logout = logout;
