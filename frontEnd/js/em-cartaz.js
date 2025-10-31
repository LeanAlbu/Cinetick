import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    fetchAndDisplayMovies('/filmes/em-cartaz', 'em-cartaz-grid');
});