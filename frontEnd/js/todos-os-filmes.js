import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    fetchAndDisplayMovies('/filmes', 'todos-os-filmes-list');
});