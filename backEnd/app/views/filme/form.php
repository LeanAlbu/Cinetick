<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Adicionar Novo Filme</h1>

<form id="add-movie-form" class="admin-form">
    <div class="form-group">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="release_year">Ano de Lançamento</label>
        <input type="number" id="release_year" name="release_year" required>
    </div>
    <div class="form-group">
        <label for="director">Diretor</label>
        <input type="text" id="director" name="director" required>
    </div>
    <div class="form-group">
        <label for="description">Descrição</label>
        <textarea id="description" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label for="imagem_url">URL da Imagem do Pôster</label>
        <input type="text" id="imagem_url" name="imagem_url" required>
    </div>
    <div class="form-group">
        <label for="em_cartaz">Em Cartaz?</label>
        <input type="checkbox" id="em_cartaz" name="em_cartaz" value="1">
    </div>
    <button type="submit" class="btn btn-submit">Salvar Filme</button>
</form>

<script>
document.getElementById('add-movie-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    data.em_cartaz = document.getElementById('em_cartaz').checked; // Pega o valor booleano do checkbox

    try {
        const response = await fetch('<?= BASE_URL ?>/api/filmes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Filme adicionado com sucesso!');
            window.location.href = '<?= BASE_URL ?>/admin/filmes';
        } else {
            alert('Erro ao adicionar filme: ' + result.error);
        }
    } catch (error) {
        console.error('Erro no fetch:', error);
        alert('Ocorreu um erro de comunicação. Verifique o console.');
    }
});
</script>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>