document.addEventListener('DOMContentLoaded', function() {

    // --- CONFIGURAÇÃO DOS CARROSSÉIS (Swiper.js) ---
    const bannerSwiper = new Swiper('.banner-carousel', {
        loop: true,
        effect: 'fade',
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
    });

    // --- LÓGICA DO MODAL DE LOGIN ---
    const loginLink = document.getElementById('login-link');
    const loginModal = document.getElementById('login-modal');
    if (loginModal) {
        const closeModalButton = loginModal.querySelector('.modal-close');
        if (loginLink) {
            loginLink.addEventListener('click', function(event) {
                event.preventDefault();
                loginModal.classList.add('active');
            });
        }
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function() {
                loginModal.classList.remove('active');
            });
        }
        loginModal.addEventListener('click', function(event) {
            if (event.target === loginModal) {
                loginModal.classList.remove('active');
            }
        });
    }

    // --- CARREGAMENTO DE DADOS DA API ---

    // URL base da nossa API backend
    const API_BASE_URL = 'http://localhost/Cinetick/backEnd/public';

    /**
     * Cria o HTML para um card de filme.
     * @param {object} filme - O objeto do filme vindo da API.
     * @returns {string} - A string HTML do card.
     */
    function createMovieCard(filme) {
        // Usa uma imagem placeholder se a URL da imagem não existir
        const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
        return `
            <div class="movie-card">
                <img src="${imageUrl}" alt="Pôster de ${filme.title}">
                <div class="movie-info">
                    <h3>${filme.title}</h3>
                    <p>${filme.release_year} - ${filme.director}</p>
                </div>
            </div>
        `;
    }

    /**
     * Busca filmes de um endpoint da API e os insere no DOM.
     * @param {string} endpoint - O endpoint da API (ex: '/em-cartaz').
     * @param {string} elementId - O ID do elemento onde os cards serão inseridos.
     */
    async function fetchAndDisplayMovies(endpoint, elementId) {
        const container = document.getElementById(elementId);
        if (!container) return;

        try {
            const response = await fetch(`${API_BASE_URL}${endpoint}`);
            const result = await response.json(); // Tenta parsear o JSON em todos os casos

            if (!response.ok) {
                // Se a API retornou um erro no corpo do JSON, usa essa mensagem
                const errorMessage = result.error || `Erro na rede: ${response.statusText}`;
                throw new Error(errorMessage);
            }

            const movies = result;

            if (movies.length === 0) {
                container.innerHTML = '<p>Nenhum filme encontrado no momento.</p>';
            } else {
                container.innerHTML = movies.map(createMovieCard).join('');
            }
        } catch (error) {
            console.error(`Falha ao buscar filmes do endpoint ${endpoint}:`, error.message);
            container.innerHTML = `<p>Não foi possível carregar os filmes. Tente novamente mais tarde. (${error.message})</p>`;
        }
    }

    // --- LÓGICA DO FORMULÁRIO DE LOGIN ---
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');

    if (loginForm) {
        loginForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            loginError.style.display = 'none';

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${API_BASE_URL}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const result = await response.json();

                if (!response.ok) {
                    loginError.textContent = result.error || 'Erro desconhecido.';
                    loginError.style.display = 'block';
                } else {
                    loginModal.classList.remove('active');
                    // Salva os dados do usuário no localStorage para uso em outras páginas
                    localStorage.setItem('cinetick_user', JSON.stringify(result.user));

                    Swal.fire({
                        icon: 'success',
                        title: 'Login bem-sucedido!',
                        text: `Bem-vindo(a), ${result.user.name}!`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload(); // Recarrega a página para mostrar o link de admin
                    });
                }
            } catch (error) {
                console.error('Falha ao fazer login:', error);
                loginError.textContent = 'Não foi possível conectar ao servidor.';
                loginError.style.display = 'block';
            }
        });
    }

    // --- INICIA O CARREGAMENTO DOS DADOS ---
    fetchAndDisplayMovies('/em-cartaz', 'em-alta-list');
    fetchAndDisplayMovies('/futuros-lancamentos', 'lancamentos-list');

    // --- LÓGICA DE AUTENTICAÇÃO VISUAL ---
    function setupAuthUI() {
        const user = JSON.parse(localStorage.getItem('cinetick_user'));
        const adminPlaceholder = document.getElementById('admin-link-placeholder');
        const loginLink = document.getElementById('login-link');

        if (user) {
            // Usuário está logado
            loginLink.textContent = 'Sair';
            loginLink.href = '#';
            loginLink.addEventListener('click', async (e) => {
                e.preventDefault();
                // Chamar a API de logout
                await fetch(`${API_BASE_URL}/logout`, { method: 'POST' });
                // Limpar o localStorage e recarregar a página
                localStorage.removeItem('cinetick_user');
                window.location.reload();
            });

            if (user.role === 'admin' && adminPlaceholder) {
                adminPlaceholder.innerHTML = '<a href="admin.html">Admin</a>';
            }

        } else {
            // Usuário não está logado
            loginLink.textContent = 'Entrar';
            loginLink.href = '#';
            loginLink.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('login-modal').classList.add('active');
            });
        }
    }

    setupAuthUI();
});
