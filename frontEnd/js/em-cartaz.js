document.addEventListener('DOMContentLoaded', function() {

    const API_BASE_URL = '../backEnd/public';

    /**
     * Cria o HTML para um card de filme com botão de compra.
     * @param {object} filme - O objeto do filme vindo da API.
     * @returns {string} - A string HTML do card.
     */
    function createMovieCard(filme) {
        const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
        const detailLink = `filme.html?id=${filme.id}`;

        return `
            <a href="${detailLink}" class="movie-card-link">
                <div class="movie-card">
                    <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                    <div class="movie-info">
                        <h3>${filme.title}</h3>
                        <p>${filme.release_year} - ${filme.director}</p>
                    </div>
                </div>
            </a>
        `;
    }

    /**
     * Busca filmes de um endpoint da API e os insere no DOM.
     * @param {string} endpoint - O endpoint da API (ex: '/em-cartaz').
     * @param {string} elementId - O ID do elemento onde os cards serão inseridos.
     */
    async function fetchAndDisplayMovies(endpoint, elementId) {
        const container = document.getElementById(elementId);
        if (!container) {
            console.error(`Elemento com ID '${elementId}' não encontrado.`);
            return;
        }

        try {
            const response = await fetch(`${API_BASE_URL}${endpoint}`);
            if (!response.ok) {
                throw new Error(`Erro na rede: ${response.statusText}`);
            }
            const movies = await response.json();

            if (movies.length === 0) {
                container.innerHTML = '<p>Nenhum filme em cartaz no momento.</p>';
            } else {
                container.innerHTML = movies.map(createMovieCard).join('');
            }
        } catch (error) {
            console.error(`Falha ao buscar filmes do endpoint ${endpoint}:`, error);
            container.innerHTML = '<p>Não foi possível carregar os filmes. Tente novamente mais tarde.</p>';
        }
    }

    // Inicia o carregamento dos filmes para a grade da página "Em Cartaz"
    fetchAndDisplayMovies('/em-cartaz', 'em-cartaz-grid');
});
