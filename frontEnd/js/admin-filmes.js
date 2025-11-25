document.addEventListener('DOMContentLoaded', function() {
    loadHeaderAndFooter();
    fetchAndDisplayFilmes();

    async function fetchAndDisplayFilmes() {
        const container = document.getElementById('filmes-list');
        if (!container) return;

        try {
            const response = await fetch(`${window.BASE_URL}/admin/filmes`);
            const result = await response.text(); // Get the raw HTML response

            if (!response.ok) {
                throw new Error(`Erro na rede: ${response.statusText}`);
            }

            container.innerHTML = result;
        } catch (error) {
            console.error('Falha ao buscar filmes:', error.message);
            container.innerHTML = `<p>Não foi possível carregar os filmes. Tente novamente mais tarde. (${error.message})</p>`;
        }
    }
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
