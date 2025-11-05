import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    fetchAndDisplayMovies('/api/filmes/todos', 'todos-os-filmes-list');
});