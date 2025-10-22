document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '../backEnd/public';

    async function fetchAndDisplayFilmes() {
        const container = document.getElementById('filmes-list');
        if (!container) return;

        try {
            const response = await fetch(`${API_BASE_URL}/admin/filmes`);
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

    fetchAndDisplayFilmes();
});
