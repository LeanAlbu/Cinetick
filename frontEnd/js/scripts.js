/**
 * Verifica se o usuário está logado checando o localStorage.
 * Esta função é definida globalmente para que outros scripts possam usá-la
 * antes do DOM estar completamente carregado, evitando race conditions.
 * @returns {boolean} - True se o usuário está logado, false caso contrário.
 */
window.isUserLoggedIn = function() {
    return localStorage.getItem('cinetick_user') !== null;
};


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
    const API_BASE_URL = '../backEnd/public';

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
    /**
     * Cria o HTML para um slide do carrossel.
     * @param {object} filme - O objeto do filme vindo da API.
     * @returns {string} - A string HTML do slide.
     */
    function createCarouselSlide(filme) {
        const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
        return `
            <div class="swiper-slide">
                <a href="filme.html?id=${filme.id}">
                    <img src="${imageUrl}" alt="Banner de ${filme.title}">
                </a>
            </div>
        `;
    }

    /**
     * Busca filmes para o carrossel e os insere no DOM.
     */
    async function fetchAndDisplayCarouselMovies() {
        const carouselWrapper = document.querySelector('.banner-carousel .swiper-wrapper');
        if (!carouselWrapper) return;

        try {
            const response = await fetch(`${API_BASE_URL}/filmes/todos`);
            const result = await response.json();

            if (!response.ok) {
                const errorMessage = result.error || `Erro na rede: ${response.statusText}`;
                throw new Error(errorMessage);
            }

            const movies = result;

            if (movies.length > 0) {
                carouselWrapper.innerHTML = movies.map(createCarouselSlide).join('');
                bannerSwiper.update(); // Atualiza o Swiper após adicionar os slides
            }
        } catch (error) { 
            console.error(`Falha ao buscar filmes para o carrossel:`, error.message);
        }
    }

    fetchAndDisplayCarouselMovies();
    fetchAndDisplayMovies('/em-cartaz', 'em-alta-list');

    // --- LÓGICA DE AUTENTICAÇÃO VISUAL ---
    function setupAuthUI() {
        const adminPlaceholder = document.getElementById('admin-link-placeholder');
        const loginLink = document.getElementById('login-link');
        let user = null;

        try {
            const storedUser = localStorage.getItem('cinetick_user');
            if (storedUser) {
                user = JSON.parse(storedUser);
            }
        } catch (e) {
            console.error("Erro ao ler dados do usuário do localStorage. Limpando sessão.", e);
            localStorage.removeItem('cinetick_user');
        }

        if (user) {
            // Usuário está logado
            loginLink.textContent = 'Sair';
            loginLink.href = '#';
            
            // Para evitar adicionar múltiplos event listeners, removemos o antigo antes de adicionar um novo
            const newLoginLink = loginLink.cloneNode(true);
            loginLink.parentNode.replaceChild(newLoginLink, loginLink);

            newLoginLink.addEventListener('click', async (e) => {
                e.preventDefault();
                try {
                    await fetch(`${API_BASE_URL}/logout`, { method: 'POST' });
                } catch(err) {
                    console.error("API de logout falhou, mas o logout prosseguirá no front-end.", err);
                } finally {
                    localStorage.removeItem('cinetick_user');
                    window.location.reload();
                }
            });

            if (user.role === 'admin' && adminPlaceholder) {
                adminPlaceholder.innerHTML = '<a href="admin.html">Admin</a>';
            }

        } else {
            // Usuário não está logado
            loginLink.textContent = 'Entrar';
            loginLink.href = '#';

            // Para evitar adicionar múltiplos event listeners, removemos o antigo antes de adicionar um novo
            const newLoginLink = loginLink.cloneNode(true);
            loginLink.parentNode.replaceChild(newLoginLink, loginLink);

            newLoginLink.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('login-modal').classList.add('active');
            });
            
            if(adminPlaceholder) {
                adminPlaceholder.innerHTML = ''; // Limpa o link de admin se o usuário não estiver logado
            }
        }
    }

    setupAuthUI();
});