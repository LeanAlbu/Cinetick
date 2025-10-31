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
