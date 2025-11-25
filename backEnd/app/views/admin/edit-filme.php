<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Editar Filme: <?= htmlspecialchars($filme['title']) ?></h1>

<form action="<?= BASE_URL ?>/admin/filmes/update/<?= $filme['id'] ?>" method="POST" class="admin-form">
    <div class="form-group">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($filme['title']) ?>" required>
    </div>
    <div class="form-group">
        <label for="release_year">Ano de Lançamento</label>
        <input type="number" id="release_year" name="release_year" value="<?= htmlspecialchars($filme['release_year']) ?>" required>
    </div>
    <div class="form-group">
        <label for="director">Diretor</label>
        <input type="text" id="director" name="director" value="<?= htmlspecialchars($filme['director']) ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Descrição</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($filme['description']) ?></textarea>
    </div>
    <div class="form-group">
        <label for="imagem_url">URL da Imagem do Pôster</label>
        <input type="text" id="imagem_url" name="imagem_url" value="<?= htmlspecialchars($filme['imagem_url']) ?>" required>
    </div>
    <div class="form-group">
        <label for="em_cartaz">Em Cartaz?</label>
        <input type="checkbox" id="em_cartaz" name="em_cartaz" value="1" <?= !empty($filme['em_cartaz']) ? 'checked' : '' ?>>
    </div>
    <button type="submit" class="btn btn-submit">Salvar Alterações</button>
</form>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>
