import { isUserLoggedIn } from './common/auth.js';

document.addEventListener('DOMContentLoaded', () => {
    if (!isUserLoggedIn()) {
        window.location.href = 'index.html';
        return;
    }

    loadHeaderAndFooter();

    document.getElementById('change-password-form').addEventListener('submit', handleChangePassword);
});

async function loadHeaderAndFooter() {
    const headerContainer = document.querySelector('.main-header');
    const modalContainer = document.getElementById('login-modal-container');

    const headerResponse = await fetch('templates/header.html');
    const headerHtml = await headerResponse.text();
    headerContainer.innerHTML = headerHtml;

    const modalResponse = await fetch('templates/login-modal.html');
    const modalHtml = await modalResponse.text();
    modalContainer.innerHTML = modalHtml;

    const { setupUserMenu } = await import('./common/ui.js');
    setupUserMenu();
}

async function handleChangePassword(event) {
    event.preventDefault();
    const errorContainer = document.querySelector('.error-message');
    errorContainer.style.display = 'none';

    const old_password = document.getElementById('old_password').value;
    const new_password = document.getElementById('new_password').value;
    const confirm_password = document.getElementById('confirm_password').value;

    if (new_password !== confirm_password) {
        errorContainer.textContent = 'As novas senhas não coincidem.';
        errorContainer.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/password/change`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ old_password, new_password, confirm_password }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Erro ao alterar a senha.');
        }

        errorContainer.textContent = result.message;
        errorContainer.style.color = 'green';
        errorContainer.style.display = 'block';

        setTimeout(() => {
            window.location.href = 'perfil.html';
        }, 2000);

    } catch (error) {
        errorContainer.textContent = error.message;
        errorContainer.style.color = 'red';
        errorContainer.style.display = 'block';
    }
}
