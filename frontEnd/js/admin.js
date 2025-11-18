document.addEventListener('DOMContentLoaded', function() {
    loadHeaderAndFooter();
    initializeAdminPage();
});

async function loadHeaderAndFooter() {
    const headerContainer = document.querySelector('.main-header');
    const modalContainer = document.getElementById('login-modal-container');

    try {
        const headerResponse = await fetch('templates/header.html');
        const headerHtml = await headerResponse.text();
        headerContainer.innerHTML = headerHtml;

        const modalResponse = await fetch('templates/login-modal.html');
        const modalHtml = await modalResponse.text();
        modalContainer.innerHTML = modalHtml;

        const { setupUserMenu } = await import('./common/ui.js');
        setupUserMenu();
    } catch (error) {
        console.error('Erro ao carregar o cabeçalho ou rodapé:', error);
    }
}

function initializeAdminPage() {
    const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';
    const user = JSON.parse(localStorage.getItem('cinetick_user'));

    // 1. VERIFICAÇÃO DE SEGURANÇA
    if (!user || user.role !== 'admin') {
        Swal.fire({
            icon: 'error',
            title: 'Acesso Negado',
            text: 'Você precisa ser um administrador para acessar esta página.',
            allowOutsideClick: false
        }).then(() => {
            window.location.href = 'index.html';
        });
        return;
    }

    const movieGridContainer = document.getElementById('admin-movie-list');
    const movieForm = document.getElementById('add-movie-form');
    const formTitle = document.querySelector('.admin-form-container h3');
    const submitButton = movieForm.querySelector('button[type="submit"]');
    const cancelEditButton = document.getElementById('cancel-edit');

    let moviesData = []; // Cache para os dados dos filmes

    // 2. FUNÇÕES DE RENDERIZAÇÃO E API

    function createAdminMovieCard(filme) {
        const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
        return `
            <div class="movie-card" data-id="${filme.id}">
                <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                <div class="movie-info">
                    <h3>${filme.title}</h3>
                    <p>${filme.director}</p>
                </div>
                <div class="card-footer">
                    <button class="btn-edit" data-id="${filme.id}">Editar</button>
                    <button class="btn-delete" data-id="${filme.id}">Excluir</button>
                </div>
            </div>
        `;
    }

    async function loadMovies() {
        try {
            const response = await fetch(`${API_BASE_URL}/api/filmes`);
            moviesData = await response.json();
            movieGridContainer.innerHTML = moviesData.map(createAdminMovieCard).join('');
        } catch (error) {
            console.error('Falha ao carregar filmes:', error);
            movieGridContainer.innerHTML = '<p>Erro ao carregar filmes.</p>';
        }
    }

    function populateFormForEdit(movieId) {
        const movie = moviesData.find(m => m.id == movieId);
        if (!movie) return;

        document.getElementById('movieId').value = movie.id;
        document.getElementById('title').value = movie.title;
        document.getElementById('release_year').value = movie.release_year;
        document.getElementById('director').value = movie.director;
        document.getElementById('description').value = movie.description;
        document.getElementById('imagem_url').value = movie.imagem_url;

        formTitle.textContent = 'Editar Filme';
        submitButton.textContent = 'Atualizar Filme';
        cancelEditButton.style.display = 'inline-block';
    }

    function resetForm() {
        movieForm.reset();
        document.getElementById('movieId').value = '';
        formTitle.textContent = 'Adicionar Novo Filme';
        submitButton.textContent = 'Adicionar Filme';
        cancelEditButton.style.display = 'none';
    }

    // 3. EVENT LISTENERS

    movieForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const movieId = document.getElementById('movieId').value;
        const movieData = {
            title: document.getElementById('title').value,
            release_year: document.getElementById('release_year').value,
            director: document.getElementById('director').value,
            description: document.getElementById('description').value,
            imagem_url: document.getElementById('imagem_url').value,
        };

        const isEditing = !!movieId;
        const url = isEditing ? `${API_BASE_URL}/api/filmes/${movieId}` : `${API_BASE_URL}/api/filmes`;
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(movieData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `Erro ao ${isEditing ? 'atualizar' : 'adicionar'} filme`);
            }

            Swal.fire('Sucesso!', `Filme ${isEditing ? 'atualizado' : 'adicionado'} com sucesso.`, 'success');
            resetForm();
            loadMovies();
        } catch (error) {
            Swal.fire('Erro!', error.message, 'error');
        }
    });

    movieGridContainer.addEventListener('click', async (e) => {
        const target = e.target;
        const movieId = target.dataset.id;

        if (target.classList.contains('btn-edit')) {
            populateFormForEdit(movieId);
        } else if (target.classList.contains('btn-delete')) {
            const result = await Swal.fire({
                title: 'Você tem certeza?',
                text: "Você não poderá reverter isso!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, pode excluir!',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${API_BASE_URL}/api/filmes/${movieId}`, {
                        method: 'DELETE'
                    });
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Erro ao excluir filme');
                    }
                    Swal.fire('Excluído!', 'O filme foi excluído.', 'success');
                    loadMovies();
                } catch (error) {
                    Swal.fire('Erro!', error.message, 'error');
                }
            }
        }
    });

    cancelEditButton.addEventListener('click', resetForm);

    // 4. CARREGAMENTO INICIAL
    loadMovies();
}