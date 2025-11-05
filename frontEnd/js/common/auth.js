const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';

export function isUserLoggedIn() {
    return localStorage.getItem('cinetick_user') !== null;
};

function handleLogin(form, modal) {
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        const errorContainer = form.querySelector('.error-message');
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

function handleLogout(logoutLink) {
    logoutLink.addEventListener('click', async (e) => {
        e.preventDefault();
        try {
            await fetch(`${API_BASE_URL}/logout`, { method: 'POST' });
        } catch(err) {
            console.error("API de logout falhou, mas o logout prosseguirá no front-end.", err);
        } finally {
            localStorage.removeItem('cinetick_user');
            window.location.reload();
        }
    });
}

export function setupAuthUI() {
    const loginLink = document.getElementById('login-link');
    const loginModal = document.getElementById('login-modal');
    const loginForm = document.getElementById('login-form');
    let user = null;

    try {
        user = JSON.parse(localStorage.getItem('cinetick_user'));
    } catch (e) {}

    if (user) {
        // User is logged in
        //loginLink.textContent = 'Sair';
        loginLink.style.display = 'none';
        loginLink.href = '#';
        handleLogout(loginLink);

        if (user.role === 'admin') {
            const adminNav = document.querySelector('.main-nav');
            const adminLink = document.createElement('a');
            adminLink.href = `../prototipos/admin.html`;
            adminLink.textContent = 'Admin';
            adminNav.appendChild(adminLink);
        }

    } else {
        // User is not logged in
        loginLink.textContent = 'Entrar';
        loginLink.href = '#';
        const closeLogin = document.getElementById('close-login');
        loginLink.addEventListener('click', (e) => {
            loginModal.classList.add('active');
        });

        if (loginForm && loginModal) {
            handleLogin(loginForm, loginModal);
        }
    }
}

export function setupUserMenu() {
    const user = JSON.parse(localStorage.getItem('cinetick_user'));
    const avatar = document.getElementById('user-avatar');
    const dropdown = document.getElementById('user-dropdown');
    const userName = document.getElementById('user-name');
    const logoutLink = document.getElementById('logout-link');

    if (!user) {
        document.querySelector('.user-menu').style.display = 'none';
        return;
    }

    // Exibir nome
    userName.textContent = user.name;

    // Exemplo: avatar aleatório usando UI Avatars
    avatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=444&color=fff`;

    // Toggle menu ao clicar
    avatar.addEventListener('click', () => {
        dropdown.classList.toggle('active');
    });

    // Sair
    logoutLink.addEventListener('click', (e) => {
    localStorage.removeItem('cinetick_user');
    document.querySelector('.user-menu').style.display = 'none';

     const loginLink = document.getElementById('login-link');
    if (loginLink) {
        loginLink.style.display = 'inline-block';
        loginLink.textContent = 'Entrar';
        loginLink.href = '#';
        loginLink.addEventListener('click', (e) => {
            e.preventDefault();
            const loginModal = document.getElementById('login-modal');
            loginModal.classList.add('active');
        });
    }
     window.location.reload();

    });

    // Fechar caso clique fora
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.user-menu')) {
            dropdown.classList.remove('active');
        }
    });
}

setupUserMenu();
