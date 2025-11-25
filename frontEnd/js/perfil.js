import { isUserLoggedIn } from './common/auth.js';

document.addEventListener('DOMContentLoaded', () => {
    // Verificação local básica
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

    try {
        const headerResponse = await fetch('templates/header.html');
        headerContainer.innerHTML = await headerResponse.text();

        const modalResponse = await fetch('templates/login-modal.html');
        modalContainer.innerHTML = await modalResponse.text();

        const { setupUserMenu } = await import('./common/ui.js');
        setupUserMenu();
    } catch (error) {
        console.error("Erro ao carregar templates:", error);
    }
}

async function loadProfileData() {
    const profileContainer = document.getElementById('profile-container');

    try {
        // 1. ADICIONADO: credentials: 'include' é OBRIGATÓRIO para sessões PHP
        const response = await fetch(`${window.API_BASE_URL}/user/profile`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include' // Importante para enviar cookies de sessão
    });
            method: 'GET',
            credentials: 'include', 
            headers: {
                'Content-Type': 'application/json'
            }
        });

        // 2. DEBUG: Ler como texto primeiro para ver erros do PHP
        const responseText = await response.text();

        if (!response.ok) {
            console.log("🔴 Erro ao carregar perfil (PHP Debug):", responseText);
            
            // Se der 401, a sessão expirou no servidor
            if (response.status === 401) {
                alert("Sua sessão expirou. Faça login novamente.");
                localStorage.removeItem('cinetick_user'); // Limpa o local também
                window.location.href = 'index.html';
                return;
            }
            throw new Error('Falha ao carregar dados do perfil.');
        }

        // Converte o texto para JSON
        const user = JSON.parse(responseText);
        renderProfile(user);

    } catch (error) {
        console.error(error);
        if(profileContainer) {
            profileContainer.innerHTML = '<div class="error-message">Erro ao carregar o perfil. Verifique o console.</div>';
        }
    }
}

function renderProfile(user) {
    const profileContainer = document.getElementById('profile-container');
    
    // Lógica inteligente para a imagem:
    // Se a URL já começar com http, usa ela. Se não, monta com a BASE_URL.
    let imageUrl = '/uploads/avatars/default.png';
    if (user.profile_picture_url) {
        imageUrl = user.profile_picture_url.startsWith('http') 
            ? user.profile_picture_url 
            : `${window.BASE_URL}${user.profile_picture_url}`;
    }

    const profileHtml = `
        <div class="profile-container">
            <div class="profile-picture">
                <img id="profile-img" src="${imageUrl}" alt="Foto de Perfil" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                <form id="picture-form" enctype="multipart/form-data" style="margin-top: 15px;">
                    <label for="profile_picture" class="btn-secondary" style="cursor: pointer;">Mudar foto</label>
                    <input type="file" name="profile_picture" id="profile_picture" required style="display: none;">
                    <button type="submit" class="btn-primary" style="margin-top: 10px;">Salvar Foto</button>
                    <div class="feedback-message" style="display: none; margin-top: 10px;"></div>
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
                    <button type="submit" class="btn-primary">Salvar Alterações</button>
                    <div class="feedback-message" style="display: none; margin-top: 10px;"></div>
                </form>
                <div style="margin-top: 20px;">
                     <a href="change-password.html" class="password-change-link">Mudar Senha</a>
                </div>
            </div>
        </div>
    `;

    profileContainer.innerHTML = profileHtml;

    // Hack para mostrar o nome do arquivo selecionado
    const fileInput = document.getElementById('profile_picture');
    fileInput.addEventListener('change', (e) => {
        if(e.target.files[0]) {
            e.target.previousElementSibling.textContent = e.target.files[0].name;
        }
    });

    document.getElementById('picture-form').addEventListener('submit', handlePictureUpdate);
    document.getElementById('details-form').addEventListener('submit', handleDetailsUpdate);
}

async function handlePictureUpdate(event) {
    event.preventDefault();
    const form = event.target;
    const feedback = form.querySelector('.feedback-message');
    const formData = new FormData(form);

    try {
        // POST com imagem
        const response = await fetch(`${window.API_BASE_URL}/profile/update-picture`, {
            method: 'POST',
            body: formData,
            credentials: 'include' // <--- IMPORTANTE
        });

        // Debug de resposta
        const responseText = await response.text();
        
        if (!response.ok) {
            console.error("Erro Upload:", responseText);
            throw new Error('Erro ao atualizar a foto.');
        }

        const result = JSON.parse(responseText);

        feedback.textContent = result.message || "Foto atualizada!";
        feedback.style.color = 'green';
        feedback.style.display = 'block';

        // Atualiza a imagem na hora
        let newImageUrl = result.profile_picture_url;
        if (!newImageUrl.startsWith('http')) {
            newImageUrl = window.BASE_URL + newImageUrl;
        }
        
        // Força recarregar a imagem adicionando um timestamp para evitar cache
        document.getElementById('profile-img').src = newImageUrl + '?t=' + new Date().getTime();

        // Atualiza localStorage
        const user = JSON.parse(localStorage.getItem('cinetick_user')) || {};
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
        const response = await fetch(`${window.API_BASE_URL}/profile/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email }),
            credentials: 'include' // <--- IMPORTANTE
        });

        const responseText = await response.text();
        const result = JSON.parse(responseText);

        if (!response.ok) {
            throw new Error(result.error || 'Erro ao atualizar os detalhes.');
        }
        
        feedback.textContent = result.message || "Dados atualizados!";
        feedback.style.color = 'green';
        feedback.style.display = 'block';

        // Atualiza localStorage
        const user = JSON.parse(localStorage.getItem('cinetick_user')) || {};
        user.name = name;
        user.email = email;
        localStorage.setItem('cinetick_user', JSON.stringify(user));

    } catch (error) {
        feedback.textContent = error.message;
        feedback.style.color = 'red';
        feedback.style.display = 'block';
    }
}
