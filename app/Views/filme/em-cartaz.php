<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="content-wrapper">

    <section class="movie-section">
        <h2 class="section-title">Em Cartaz</h2>
        
        <div class="movie-grid">
            <?php if (isset($data['filmes']) && !empty($data['filmes'])): ?>
                <?php foreach ($data['filmes'] as $filme): ?>
                    <div class="movie-card">
                        <?php
                            // Define a URL da imagem. Se não houver, usa uma imagem padrão.
                            $imageUrl = (!empty($filme['imagem_url']))
                                        ? BASE_URL . htmlspecialchars($filme['imagem_url'])
                                        : BASE_URL . '/img/placeholder.png'; // Adicione uma imagem 'placeholder.png' em public/img/
                        ?>
                        <img src="<?= $imageUrl ?>" alt="Pôster de <?= htmlspecialchars($filme['title']) ?>">
                        <div class="movie-info">
                            <h3><?= htmlspecialchars($filme['title']) ?></h3>
                            <p><?= htmlspecialchars($filme['release_year']) ?> - Duração</p>
                        </div>
                        <div class="card-footer">
                            <a href="<?= BASE_URL ?>/pagamento/create/<?= $filme['id'] ?>" class="btn-comprar"> Comprar Ingresso </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme em cartaz no momento.</p>
            <?php endif; ?>
        </div>
    </section>

</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>