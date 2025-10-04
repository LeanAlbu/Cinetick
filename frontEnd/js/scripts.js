document.addEventListener('DOMContentLoaded', function() {
    // Ativa o carrossel de BANNERS
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

    const swiper = new Swiper('.em-alta-carousel', {
        loop: true,
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
    });

});