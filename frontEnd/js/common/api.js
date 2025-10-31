const API_BASE_URL = '../backEnd/public';

/**
 * Cria o HTML para um card de filme.
 * @param {object} filme - O objeto do filme vindo da API.
 * @returns {string} - A string HTML do card.
 */
function createMovieCard(filme) {
    const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
    return `
        <a href="filme/${filme.id}" class="movie-card-link">
            <div class="movie-card">
                <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                <div class="movie-info">
                    <h3>${filme.title}</h3>
                </div>
            </div>
        </a>
    `;
}

/**
 * Busca filmes de um endpoint da API e os insere no DOM.
 * @param {string} endpoint - O endpoint da API (ex: '/filmes/em-cartaz').
 * @param {string} elementId - O ID do elemento onde os cards serão inseridos.
 */
export async function fetchAndDisplayMovies(endpoint, elementId) {
    const container = document.getElementById(elementId);
    if (!container) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api${endpoint}`);
        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.error || `Erro na rede: ${response.statusText}`;
            throw new Error(errorMessage);
        }

        if (result.length === 0) {
            container.innerHTML = '<p>Nenhum filme encontrado no momento.</p>';
        } else {
            container.innerHTML = result.map(createMovieCard).join('');
        }
    } catch (error) {
        console.error(`Falha ao buscar filmes do endpoint ${endpoint}:`, error.message);
        container.innerHTML = `<p>Não foi possível carregar os filmes. Tente novamente mais tarde.</p>`;
    }
}
