<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="main-content-wrapper">
    <div class="auth-container">
        <h2>Login</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); // Clear the message after displaying ?>
        <?php endif; ?>
        <form action="<?= BASE_URL ?>/login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Entrar</button>
        </form>
        <p class="auth-link">Não tem uma conta? <a href="<?= BASE_URL ?>/user/create">Registre-se</a></p>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>