</main> 

    <div id="login-modal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close">&times;</button>
            <h2>Acessar o CineTick</h2>
            <a href="<?= BASE_URL ?>/login" class="login-button email">Acessar por E-mail</a>
            <p class="signup-link">
                Não tem conta? <a href="<?= BASE_URL ?>/user/create">Crie uma agora</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Carrega o script principal como um módulo para habilitar import/export -->
    <script type="module" src="<?= FRONT_ASSETS_URL ?>/js/scripts.js"></script>

    <!-- Carrega um script específico da página, se definido pelo controller -->
    <?php if (isset($page_script)): ?>
        <script type="module" src="<?= FRONT_ASSETS_URL ?>/js/<?= $page_script ?>"></script>
    <?php endif; ?>
    
</body>
</html>