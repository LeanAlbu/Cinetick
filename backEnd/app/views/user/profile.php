<?php include_once __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h1>Meu Perfil</h1>

    <?php if (isset($data['user'])): ?>
        <div class="profile-container">
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($data['user']['profile_picture_url'] ?? FRONT_ASSETS_URL . '/img/default-avatar.png'); ?>" alt="Foto de Perfil">
                <form action="<?= BASE_URL ?>/profile/update-picture" method="post" enctype="multipart/form-data">
                    <label for="profile_picture">Mudar foto de perfil:</label>
                    <input type="file" name="profile_picture" id="profile_picture" required>
                    <button type="submit">Salvar Foto</button>
                </form>
            </div>

            <div class="profile-details">
                <form action="<?= BASE_URL ?>/profile/update" method="post">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($data['user']['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($data['user']['email']); ?>" required>
                    </div>
                    <button type="submit">Salvar Alterações</button>
                </form>
                <a href="<?= BASE_URL ?>/password/change">Mudar Senha</a>
            </div>
        </div>
    <?php else: ?>
        <p>Nenhum dado de usuário encontrado.</p>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>