import { fetchAndDisplayMovies } from './api.js';

function createCarouselSlide(filme) {
    const imageUrl = filme.imagem_url ? filme.imagem_url : 'img/filme-placeholder.png';
    return `
        <div class="swiper-slide">
            <a href="filme/${filme.id}">
                <img src="${imageUrl}" alt="Banner de ${filme.title}" class="carousel-image">
            </a>
        </div>
    `;
}

async function fetchCarouselMovies(swiperInstance) {
    const carouselWrapper = document.querySelector('.banner-carousel .swiper-wrapper');
    if (!carouselWrapper) return;

    try {
        const response = await fetch('../backEnd/public/api/filmes/todos');
        const result = await response.json();

        if (response.ok && result.length > 0) {
            carouselWrapper.innerHTML = result.map(createCarouselSlide).join('');
            swiperInstance.update();
        }
    } catch (error) { 
        console.error(`Falha ao buscar filmes para o carrossel:`, error.message);
    }
}

export function initializeCarousels() {
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

    fetchCarouselMovies(bannerSwiper);
}

export function initializeLoginModal() {
    const loginModal = document.getElementById('login-modal');
    if (!loginModal) return;

    const closeModalButton = loginModal.querySelector('.modal-close');
    
    if (closeModalButton) {
        closeModalButton.addEventListener('click', () => loginModal.classList.remove('active'));
    }

    loginModal.addEventListener('click', (event) => {
        if (event.target === loginModal) {
            loginModal.classList.remove('active');
        }
    });
}
