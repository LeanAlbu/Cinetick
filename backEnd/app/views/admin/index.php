<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Dashboard</h1>
<p>Bem-vindo ao painel de administração, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
<p>Use a navegação ao lado para gerenciar os conteúdos do site.</p>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>