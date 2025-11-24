const API_BASE_URL = '../backEnd/public';

/**
 * Cria o HTML para um card de filme.
 * @param {object} filme - O objeto do filme vindo da API.
 * @returns {string} - A string HTML do card.
 */
export function createMovieCard(filme) {
    const imageUrl = filme.imagem_url ? `${API_BASE_URL}/${filme.imagem_url}` : 'img/filme-placeholder.png';
    return `
        <a href="filme.html?id=${filme.id}" class="movie-card-link">
            <div class="movie-card">
                <img src="${imageUrl}" alt="Pôster de ${filme.title}" class="movie-card-poster">
                <div class="movie-info">
                    <h3>${filme.title}</h3>
                    <div class="movie-showtimes">
                        <span class="showtime-tag">14:00</span>
                        <span class="showtime-tag">17:00</span>
                        <span class="showtime-tag">20:00</span>
                    </div>
                </div>
            </div>
        </a>
    `;
}

/**
 * Busca filmes de um endpoint da API.
 * @param {string} endpoint - O endpoint da API (ex: '/filmes/em-cartaz').
 * @returns {Promise<Array>} - Uma promessa que resolve para um array de filmes.
 */
export async function fetchMovies(endpoint) {
    try {
        const response = await fetch(`${API_BASE_URL}/api${endpoint}`);
        const result = await response.json();

        if (!response.ok) {
            const errorMessage = result.error || `Erro na rede: ${response.statusText}`;
            throw new Error(errorMessage);
        }
        return result;
    } catch (error) {
        console.error(`Falha ao buscar filmes do endpoint ${endpoint}:`, error.message);
        throw error; // Re-lança o erro para ser tratado por quem chamou
    }
}

/**
 * Insere filmes no DOM.
 * @param {Array} filmes - Array de objetos de filme.
 * @param {string} elementId - O ID do elemento onde os cards serão inseridos.
 */
export function displayMovies(filmes, elementId) {
    const container = document.getElementById(elementId);
    if (!container) return;

    if (filmes.length === 0) {
        container.innerHTML = '<p>Nenhum filme encontrado no momento.</p>';
    } else {
        container.innerHTML = filmes.map(createMovieCard).join('');
    }
}

/**
 * Busca filmes de um endpoint da API e os insere no DOM.
 * @param {string} endpoint - O endpoint da API (ex: '/filmes/em-cartaz').
 * @param {string} elementId - O ID do elemento onde os cards serão inseridos.
 */
export async function fetchAndDisplayMovies(endpoint, elementId) {
    try {
        const filmes = await fetchMovies(endpoint);
        displayMovies(filmes, elementId);
    } catch (error) {
        const container = document.getElementById(elementId);
        if (container) {
            container.innerHTML = `<p>Não foi possível carregar os filmes. Tente novamente mais tarde.</p>`;
        }
    }
}
