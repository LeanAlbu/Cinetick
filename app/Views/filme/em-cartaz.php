<?php require_once BASE_PATH . '/app/views/templates/header.php'; ?>

<div class="content-wrapper">

    <section class="movie-section">
        <h2 class="section-title">Em Cartaz</h2>
        
        <div class="movie-grid">
            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/banner.png" alt="Pôster do Filme Brighter Days Ahead">
                <div class="movie-info">
                    <h3> SWAG </h3>
                    <p>2024 - 56m</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn-comprar"> Comprar Ingresso </a>
                </div>
            </div>

            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme1.png" alt="Pôster do Filme SOS">
                <div class="movie-info">
                    <h3>SOS</h3>
                    <p>2022 - 1h 8m</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn-comprar"> Comprar Ingresso </a>
                </div>
            </div>

            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme2.png" alt="Pôster do Filme Queen">
                <div class="movie-info">
                    <h3>Queen</h3>
                    <p>2018 - 1h 10m</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn-comprar"> Comprar Ingresso </a>
                </div>
            </div>

            <div class="movie-card">
                <img src="<?= BASE_URL ?>/img/filme3.jpg" alt="Pôster do Filme Lana">
                <div class="movie-info">
                    <h3>LANA</h3>
                    <p>2025 - 2h 4m</p>
                </div>
                <div class="card-footer">
                    <a href="#" class="btn-comprar"> Comprar Ingresso </a>
                </div>
            </div>
            
            </div>
    </section>

</div>

<?php require_once BASE_PATH . '/app/views/templates/footer.php'; ?>