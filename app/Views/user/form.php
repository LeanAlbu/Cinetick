<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="modal-overlay active">
    <div class="modal-content">
        <button class="modal-close" onclick="history.back()">&times;</button>
        <h2>Registrar</h2>
        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="error-message-container" role="alert">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
            <?php unset($_SESSION['old_input']); ?>
        <?php endif; ?>
        <form action="<?= BASE_URL ?>/user/store" method="POST">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Registrar</button>
        </form>
        <p class="signup-link">Já tem uma conta? <a href="<?= BASE_URL ?>/login">Faça login</a></p>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>