<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="content-wrapper">

    <section class="movie-section">
        <h2 class="section-title">Em Cartaz</h2>
        
        <div class="movie-grid">
            <?php if (isset($data['filmes']) && !empty($data['filmes'])): ?>
                <?php 
                    $posters = ["filme1.png", "filme2.png", "filme3.jpg", "filme4.png"];
                    $i = 0;
                ?>
                <?php foreach ($data['filmes'] as $filme): ?>
                    <div class="movie-card">
                        <img src="<?= BASE_URL ?>/img/<?= $posters[$i] ?>" alt="Pôster do Filme">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/pagamento/create/<?= $filme['id'] ?>" class="btn-comprar"> Comprar Ingresso </a>
                        </div>
                    </div>
                    <?php $i = ($i + 1) % count($posters); ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme em cartaz no momento.</p>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>