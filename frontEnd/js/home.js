import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    // Carrega as seções de filmes da página inicial
    fetchAndDisplayMovies('/filmes/em-cartaz', 'em-alta-list');
    fetchAndDisplayMovies('/filmes/futuros-lancamentos', 'lancamentos-list');
});
