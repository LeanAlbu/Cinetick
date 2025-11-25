import { fetchAndDisplayMovies } from './common/api.js';

document.addEventListener('DOMContentLoaded', () => {
    loadHeaderAndFooter();
    loadBanners(); // Carrega os banners do Swiper
    fetchAndDisplayMovies('/filmes/em-cartaz', 'em-alta-list');
    // Comentei esta linha abaixo se a rota ainda não existir, senão pode descomentar
    // fetchAndDisplayMovies('/filmes/futuros-lancamentos', 'lancamentos-list');
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
        console.error('Erro ao carregar header/footer:', error);
    }
}

// --- FUNÇÃO DE BANNERS PARA SWIPER.JS ---
async function loadBanners() {
    const swiperWrapper = document.querySelector('.swiper-wrapper');
    
    // Se não achar o container do Swiper, para aqui
    if (!swiperWrapper) return;

    try {
        // 1. Busca os dados na API
        const response = await fetch(`${window.API_BASE_URL}/active-banners`);
        if (!response.ok) throw new Error('Erro ao buscar banners');
        
        const banners = await response.json();

        // Limpa conteúdo antigo
        swiperWrapper.innerHTML = '';

        // 2. Cria os slides HTML
        banners.forEach(banner => {
            const imageUrl = banner.imagem_path ? `${window.BASE_URL}/uploads/banners/${banner.imagem_path}` : '';

            // Link opcional: se tiver link, usa <a>, senão apenas a imagem
            const conteudo = banner.link_url 
                ? `<a href="${banner.link_url}" target="_blank" class="banner-link">
                     <img src="${imageUrl}" alt="${banner.title}" class="banner-img">
                   </a>`
                : `<img src="${imageUrl}" alt="${banner.title}" class="banner-img">`;

            const slide = document.createElement('div');
            slide.classList.add('swiper-slide'); // Classe obrigatória do Swiper
            slide.innerHTML = conteudo;
            
            swiperWrapper.appendChild(slide);
        });

        // 3. INICIALIZA O SWIPER (Só depois de criar os slides)
        initSwiper();

    } catch (error) {
        console.error('Erro ao carregar banners:', error);
        swiperWrapper.innerHTML = '<div class="swiper-slide">Erro ao carregar banners</div>';
    }
}

function initSwiper() {
    // Configurações do Swiper
    new Swiper('.banner-carousel', {
        loop: true, // Loop infinito
        autoplay: {
            delay: 5000, // Troca a cada 5 segundos
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        // Efeito de transição
        effect: 'fade', 
        fadeEffect: {
            crossFade: true
        },
    });
}
