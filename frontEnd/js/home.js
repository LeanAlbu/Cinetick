import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    loadHeaderAndFooter();
    fetchAndDisplayMovies('/filmes/em-cartaz', 'em-alta-list');
    fetchAndDisplayMovies('/filmes/futuros-lancamentos', 'lancamentos-list');
});

async function loadHeaderAndFooter() {
    const headerContainer = document.querySelector('.main-header');
    const modalContainer = document.getElementById('login-modal-container');

    try {
        const headerResponse = await fetch('templates/header.html');
        const headerHtml = await headerResponse.text();
        headerContainer.innerHTML = headerHtml;

        const modalResponse = await fetch('templates/login-modal.html');
        const modalHtml = await modalResponse.text();
        modalContainer.innerHTML = modalHtml;

        const { setupUserMenu } = await import('./common/ui.js');
        setupUserMenu();
    } catch (error) {
        console.error('Erro ao carregar o cabeçalho ou rodapé:', error);
    }
}
