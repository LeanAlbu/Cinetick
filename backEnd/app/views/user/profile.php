<?php include_once __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h1>Meu Perfil</h1>
    <?php if (isset($data['user'])): ?>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($data['user']['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($data['user']['email']); ?></p>
    <?php else: ?>
        <p>Nenhum dado de usuário encontrado.</p>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>