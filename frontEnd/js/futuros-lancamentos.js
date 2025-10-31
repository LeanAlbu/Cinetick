import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    fetchAndDisplayMovies('/filmes/futuros-lancamentos', 'futuros-lancamentos-grid');
});