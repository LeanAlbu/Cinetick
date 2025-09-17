<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

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
            <?php if (isset($data['emCartaz']) && !empty($data['emCartaz'])): ?>
                <?php 
                    $posters = ["filme1.png", "filme2.png", "filme3.jpg", "filme4.png"];
                    $i = 0;
                ?>
                <?php foreach (array_slice($data['emCartaz'], 0, 4) as $filme): ?>
                    <div class="movie-card">
                        <img src="<?= BASE_URL ?>/img/<?= $posters[$i] ?>" alt="Pôster do Filme">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme em cartaz no momento.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="movie-section">
        <h2 class="section-title">Próximos Lançamentos</h2>
        <div class="movie-list">
            <?php if (isset($data['futurosLancamentos']) && !empty($data['futurosLancamentos'])): ?>
                <?php 
                    $posters = ["swag.jpg", "swag1.jpg", "swag2.png", "jbb.jpg"];
                    $i = 0;
                ?>
                <?php foreach ($data['futurosLancamentos'] as $filme): ?>
                    <div class="movie-card">
                        <img src="<?= BASE_URL ?>/img/<?= $posters[$i] ?>" alt="Pôster do Filme">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                    </div>
                    <?php $i = ($i + 1) % count($posters); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum lançamento futuro no momento.</p>
            <?php endif; ?>
        </div>
    </section>
</div>


<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>