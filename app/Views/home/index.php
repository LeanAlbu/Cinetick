<?php require_once BASE_PATH . '/app/views/templates/header.php'; ?>

<section class="banner-principal">
    <div class="swiper banner-carousel">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="<?= BASE_URL ?>/img/banner.png" alt="Banner de filme 1">
            </div>
            <div class="swiper-slide">
                <img src="<?= BASE_URL ?>/img/filme1.png" alt="Banner de filme 2">
            </div>
            <div class="swiper-slide">
                <img src="<?= BASE_URL ?>/img/filme2.png" alt="Banner de filme 3">
            </div>
        </div>
        <div class="swiper-pagination"></div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
</section>

<div class="content-wrapper">
    <section class="movie-section">
        <h2 class="section-title">Em alta</h2>
        <div class="movie-list">
            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme1.png" alt="Pôster do Filme">
                <div class="movie-info">
                    <h3>Alice no País das Maravilhas</h3>
                    <p>2016 - 1h48min</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme2.png" alt="Pôster do Filme">
                <div class="movie-info">
                    <h3>Título do Filme 2</h3>
                    <p>Ano - Duração</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme3.jpg" alt="Pôster do Filme">
                <div class="movie-info">
                    <h3>Título do Filme 3</h3>
                    <p>Ano - Duração</p>
                </div>
            </div>
            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme1.png" alt="Pôster do Filme">
                <div class="movie-info">
                    <h3>Título do Filme 4</h3>
                    <p>Ano - Duração</p>
                </div>
            </div>
        </div>
    </section>

    <section class="movie-section">
        <h2 class="section-title">Próximos Lançamentos</h2>
        <div class="movie-list">
            </div>
    </section>
</div>


<?php require_once BASE_PATH . '/app/views/templates/footer.php'; ?>