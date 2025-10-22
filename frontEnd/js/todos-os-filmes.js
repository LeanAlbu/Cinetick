document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = '../backEnd/public';

    async function fetchAndDisplayAllFilmes() {
        const container = document.getElementById('todos-os-filmes-list');
        if (!container) return;

        try {
            const response = await fetch(`${API_BASE_URL}/filmes`);
            const filmes = await response.json();

            if (!response.ok) {
                throw new Error(`Erro na rede: ${response.statusText}`);
            }

            if (filmes.length === 0) {
                container.innerHTML = '<p>Nenhum filme encontrado no momento.</p>';
                return;
            }

            const movieGrid = document.createElement('div');
            movieGrid.className = 'movie-grid';

            filmes.forEach(filme => {
                const movieCard = document.createElement('a');
                movieCard.href = `filme.html?id=${filme.id}`;
                movieCard.className = 'movie-card';

                const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';

                movieCard.innerHTML = `
                    <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                    <div class="movie-info">
                        <h3>${filme.title}</h3>
                    </div>
                `;
                movieGrid.appendChild(movieCard);
            });

            container.innerHTML = '';
            container.appendChild(movieGrid);

        } catch (error) {
            console.error('Falha ao buscar todos os filmes:', error.message);
            container.innerHTML = `<p>Não foi possível carregar os filmes. Tente novamente mais tarde. (${error.message})</p>`;
        }
    }

    fetchAndDisplayAllFilmes();
});
