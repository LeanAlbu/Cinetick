// js/common/ui.js

import { isUserLoggedIn, handleLogout, handleLogin } from './auth.js';

export function setupUserMenu() {
    const user = isUserLoggedIn() ? JSON.parse(localStorage.getItem('cinetick_user')) : null;
    const userMenu = document.querySelector('.user-menu');
    if (!userMenu) return;

    const avatar = userMenu.querySelector('#user-avatar');
    const dropdown = userMenu.querySelector('#user-dropdown');

    if (!avatar || !dropdown) return;

    let dropdownHtml = '';

    if (user) {
        // User is logged in
        avatar.src = user.profile_picture_url ? `${API_BASE_URL}${user.profile_picture_url}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=444&color=fff`;
        dropdownHtml = `
            <p>${user.name}</p>
            <a href="perfil.html">Meu Perfil</a>
            <a href="ingressos.html">Meus Ingressos</a>
            <a href="#" id="logout-link">Sair</a>
        `;
        dropdown.innerHTML = dropdownHtml;
        
        const logoutLink = dropdown.querySelector('#logout-link');
        if (logoutLink) {
            handleLogout(logoutLink);
        }

        if (user.role === 'admin') {
            const adminNav = document.querySelector('.main-nav');
            if (adminNav && !adminNav.querySelector('a[href*="admin"]')) {
                const adminLink = document.createElement('a');
                adminLink.href = `http://localhost/Cinetick/backEnd/public/admin`;
                adminLink.textContent = 'Admin';
                adminNav.appendChild(adminLink);
            }
        }

    } else {
        // User is logged out
        avatar.src = 'img/avatar-placeholder.svg'; // Placeholder icon
        dropdownHtml = `<a href="#" id="login-link">Entrar</a>`;
        dropdown.innerHTML = dropdownHtml;

        const loginLink = dropdown.querySelector('#login-link');
        const loginModal = document.getElementById('login-modal');
        const loginForm = document.getElementById('login-form');

        if (loginLink && loginModal) {
            loginLink.addEventListener('click', (e) => {
                e.preventDefault();
                loginModal.classList.add('active');
            });
        }
        
        const closeLogin = document.getElementById('close-login');
        if (closeLogin && loginModal) {
            closeLogin.addEventListener('click', () => {
                loginModal.classList.remove('active');
            });
        }

        if (loginForm && loginModal) {
            handleLogin(loginForm, loginModal);
        }
    }

    avatar.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.user-menu')) {
            dropdown.classList.remove('active');
        }
    });
}