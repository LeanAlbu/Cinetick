import { isUserLoggedIn } from './common/auth.js';

const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';

document.addEventListener('DOMContentLoaded', () => {
    if (!isUserLoggedIn()) {
        window.location.href = 'index.html';
        return;
    }

    loadHeaderAndFooter();
    loadProfileData();
});

async function loadHeaderAndFooter() {
    const headerContainer = document.querySelector('.main-header');
    const modalContainer = document.getElementById('login-modal-container');

    // These paths are relative to where perfil.html is
    const headerResponse = await fetch('templates/header.html');
    const headerHtml = await headerResponse.text();
    headerContainer.innerHTML = headerHtml;

    const modalResponse = await fetch('templates/login-modal.html');
    const modalHtml = await modalResponse.text();
    modalContainer.innerHTML = modalHtml;

    // After loading header, setup auth UI
    const { setupUserMenu } = await import('./common/ui.js');
    setupUserMenu();
}

async function loadProfileData() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/user/profile`);
        if (!response.ok) {
            // If unauthorized, redirect to login
            if (response.status === 401) {
                window.location.href = 'login.html';
            }
            throw new Error('Falha ao carregar dados do perfil.');
        }
        const user = await response.json();
        renderProfile(user);
    } catch (error) {
        console.error(error);
        document.getElementById('profile-container').innerHTML = '<p>Erro ao carregar o perfil.</p>';
    }
}

function renderProfile(user) {
    const profileContainer = document.getElementById('profile-container');
    
    const profileHtml = `
        <div class="profile-container">
            <div class="profile-picture">
                <img id="profile-img" src="${API_BASE_URL}${user.profile_picture_url || '/uploads/avatars/default.png'}" alt="Foto de Perfil">
                <form id="picture-form" enctype="multipart/form-data">
                    <label for="profile_picture">Mudar foto de perfil:</label>
                    <input type="file" name="profile_picture" id="profile_picture" required>
                    <button type="submit">Salvar Foto</button>
                    <div class="feedback-message" style="display: none;"></div>
                </form>
            </div>

            <div class="profile-details">
                <form id="details-form">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" name="name" id="name" value="${user.name}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="${user.email}" required>
                    </div>
                    <button type="submit">Salvar Alterações</button>
                    <div class="feedback-message" style="display: none;"></div>
                </form>
                <a href="change-password.html">Mudar Senha</a>
            </div>
        </div>
    `;

    profileContainer.innerHTML = profileHtml;

    document.getElementById('picture-form').addEventListener('submit', handlePictureUpdate);
    document.getElementById('details-form').addEventListener('submit', handleDetailsUpdate);
}

async function handlePictureUpdate(event) {
    event.preventDefault();
    const form = event.target;
    const feedback = form.querySelector('.feedback-message');
    const formData = new FormData(form);

    try {
        const response = await fetch(`${API_BASE_URL}/profile/update-picture`, {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Erro ao atualizar a foto.');
        }

        feedback.textContent = result.message;
        feedback.style.color = 'green';
        feedback.style.display = 'block';

        // Update image src and localStorage
        const newImageUrl = API_BASE_URL + result.profile_picture_url;
        document.getElementById('profile-img').src = newImageUrl;
        const user = JSON.parse(localStorage.getItem('cinetick_user'));
        user.profile_picture_url = result.profile_picture_url;
        localStorage.setItem('cinetick_user', JSON.stringify(user));

    } catch (error) {
        feedback.textContent = error.message;
        feedback.style.color = 'red';
        feedback.style.display = 'block';
    }
}

async function handleDetailsUpdate(event) {
    event.preventDefault();
    const form = event.target;
    const feedback = form.querySelector('.feedback-message');
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;

    try {
        const response = await fetch(`${API_BASE_URL}/profile/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.error || 'Erro ao atualizar os detalhes.');
        }
        
        feedback.textContent = result.message;
        feedback.style.color = 'green';
        feedback.style.display = 'block';

        // Update localStorage
        const user = JSON.parse(localStorage.getItem('cinetick_user'));
        user.name = name;
        user.email = email;
        localStorage.setItem('cinetick_user', JSON.stringify(user));

    } catch (error) {
        feedback.textContent = error.message;
        feedback.style.color = 'red';
        feedback.style.display = 'block';
    }
}
