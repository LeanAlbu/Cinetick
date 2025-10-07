<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<section class="banner-principal">
    <div class="swiper banner-carousel">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="<?= FRONT_ASSETS_URL ?>/img/banner.png" alt="Banner de filme 1">
            </div>
            <div class="swiper-slide">
                <img src="<?= FRONT_ASSETS_URL ?>/img/filme1.png" alt="Banner de filme 2">
            </div>
            <div class="swiper-slide">
                <img src="<?= FRONT_ASSETS_URL ?>/img/filme2.png" alt="Banner de filme 3">
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
                <?php foreach (array_slice($data['emCartaz'], 0, 4) as $filme): ?>
                    <div class="movie-card">
                        <?php
                            $imageUrl = (!empty($filme['imagem_url']))
                                        ? FRONT_ASSETS_URL . htmlspecialchars($filme['imagem_url'])
                                        : FRONT_ASSETS_URL . '/img/placeholder.png';
                        ?>
                        <img src="<?= $imageUrl ?>" alt="Pôster de <?= htmlspecialchars($filme['title']) ?>">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                    </div>
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
                <?php foreach ($data['futurosLancamentos'] as $filme): ?>
                    <div class="movie-card">
                        <?php
                            $imageUrl = (!empty($filme['imagem_url']))
                                        ? FRONT_ASSETS_URL . htmlspecialchars($filme['imagem_url'])
                                        : FRONT_ASSETS_URL . '/img/placeholder.png';
                        ?>
                        <img src="<?= $imageUrl ?>" alt="Pôster de <?= htmlspecialchars($filme['title']) ?>">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum lançamento futuro no momento.</p>
            <?php endif; ?>
        </div>
    </section>
</div>


<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>