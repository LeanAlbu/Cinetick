<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="container">
    <h2>Painel do Administrador</h2>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    <p>Este é o painel de administração. Aqui você pode gerenciar o sistema.</p>
    
    <h3>Gerenciamento de Filmes</h3>
    <ul>
        <li><a href="<?= BASE_URL ?>/filmes/create">Adicionar Novo Filme</a></li>
        <li><a href="<?= BASE_URL ?>/admin/filmes">Ver e Editar Filmes</a></li>
    </ul>

    <h3>Gerenciamento de Usuários</h3>
    <ul>
        <li><a href="<?= BASE_URL ?>/admin/users">Ver e Editar Usuários</a></li>
    </ul>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>
