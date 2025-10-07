<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="container">
    <h2>Perfil do Usuário</h2>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-primary">Painel do Administrador</a>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>
