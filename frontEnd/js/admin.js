document.addEventListener('DOMContentLoaded', function() {
    const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';
    const user = JSON.parse(localStorage.getItem('cinetick_user'));

    // 1. VERIFICAÇÃO DE SEGURANÇA
    if (!user || user.role !== 'admin') {
        // Se não for admin, redireciona para a home após um alerta
        Swal.fire({
            icon: 'error',
            title: 'Acesso Negado',
            text: 'Você precisa ser um administrador para acessar esta página.',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = 'index.html';
        });
        // Impede a execução do resto do script
        return; 
    }

    const movieListContainer = document.getElementById('admin-movie-list');
    const addMovieForm = document.getElementById('add-movie-form');

    // 2. FUNÇÕES DE RENDERIZAÇÃO E API

    /**
     * Cria o HTML para um card de filme na visão do admin (com botão de excluir)
     */
    function createAdminMovieCard(filme) {
        const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
        return `
            <div class="movie-card">
                <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                <div class="movie-info">
                    <h3>${filme.title}</h3>
                    <p>${filme.director}</p>
                </div>
                <div class="card-footer">
                    <button class="btn-delete" data-id="${filme.id}">Excluir</button>
                </div>
            </div>
        `;
    }

    /**
     * Carrega e exibe todos os filmes
     */
    async function loadMovies() {
        try {
            const response = await fetch(`${API_BASE_URL}/filmes`);
            const movies = await response.json();
            movieListContainer.innerHTML = movies.map(createAdminMovieCard).join('');
        } catch (error) {
            console.error('Falha ao carregar filmes:', error);
            movieListContainer.innerHTML = '<p>Erro ao carregar filmes.</p>';
        }
    }

    // 3. EVENT LISTENERS

    // Listener para o formulário de adicionar filme
    addMovieForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const newMovie = {
            title: document.getElementById('title').value,
            release_year: document.getElementById('release_year').value,
            director: document.getElementById('director').value,
            description: document.getElementById('description').value,
            imagem_url: document.getElementById('imagem_url').value,
        };

        try {
            const response = await fetch(`${API_BASE_URL}/filmes`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newMovie)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Erro ao adicionar filme');
            }

            Swal.fire('Sucesso!', 'Filme adicionado com sucesso.', 'success');
            addMovieForm.reset();
            loadMovies(); // Recarrega a lista
        } catch (error) {
            Swal.fire('Erro!', error.message, 'error');
        }
    });

    // Listener para os botões de excluir (usando delegação de evento)
    movieListContainer.addEventListener('click', async (e) => {
        if (e.target.classList.contains('btn-delete')) {
            const movieId = e.target.dataset.id;
            
            const result = await Swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, pode excluir!',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${API_BASE_URL}/filmes/${movieId}`, {
                        method: 'DELETE'
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Erro ao excluir filme');
                    }

                    Swal.fire('Excluído!', 'O filme foi excluído.', 'success');
                    loadMovies(); // Recarrega a lista
                } catch (error) {
                    Swal.fire('Erro!', error.message, 'error');
                }
            }
        }
    });

    // 4. CARREGAMENTO INICIAL
    loadMovies();
});
