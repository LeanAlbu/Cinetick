const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';

export function isUserLoggedIn() {
    return localStorage.getItem('cinetick_user') !== null;
};

export function handleLogin(form, modal) {
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        const errorContainer = modal.querySelector('.error-message');
        if (!errorContainer) return;

        errorContainer.style.display = 'none';

        const email = form.querySelector('#email').value;
        const password = form.querySelector('#password').value;

        try {
            const response = await fetch(`${API_BASE_URL}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const result = await response.json();

            if (!response.ok) {
                errorContainer.textContent = result.error || 'Erro desconhecido.';
                errorContainer.style.display = 'block';
            } else {
                modal.classList.remove('active');
                localStorage.setItem('cinetick_user', JSON.stringify(result.user));
                window.location.reload();
            }
        } catch (error) {
            errorContainer.textContent = 'Não foi possível conectar ao servidor.';
            errorContainer.style.display = 'block';
        }
    });
}

export function handleLogout(logoutLink) {
    logoutLink.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
            await fetch(`${API_BASE_URL}/logout`, { method: 'POST', credentials: 'include' });
        } catch(err) {
            console.error("API de logout falhou, mas o logout prosseguirá no front-end.", err);
        } finally {
            localStorage.removeItem('cinetick_user');
            window.location.href = 'index.html';
        }
    });
}

