<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Adicionar Novo Item à Bomboniere</h1>

<form id="add-item-form" class="admin-form">
    <input type="hidden" id="item_id" name="item_id" value="<?= $item['id'] ?? '' ?>">
    <div class="form-group">
        <label for="name">Nome do Item</label>
        <input type="text" id="name" name="name" value="<?= $item['name'] ?? '' ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Descrição</label>
        <textarea id="description" name="description" required><?= $item['description'] ?? '' ?></textarea>
    </div>
    <div class="form-group">
        <label for="price">Preço</label>
        <input type="number" id="price" name="price" step="0.01" value="<?= $item['price'] ?? '' ?>" required>
    </div>
    <div class="form-group">
        <label for="image_url">URL da Imagem</label>
        <input type="text" id="image_url" name="image_url" value="<?= $item['image_url'] ?? '' ?>" required>
    </div>
    <button type="submit" class="btn btn-submit">Salvar Item</button>
</form>

<script>
document.getElementById('add-item-form').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const itemId = document.getElementById('item_id').value;
    
    const url = itemId ? `<?= BASE_URL ?>/api/bomboniere/${itemId}` : '<?= BASE_URL ?>/api/bomboniere';
    const method = itemId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Item salvo com sucesso!');
            window.location.href = '<?= BASE_URL ?>/admin/bomboniere';
        } else {
            alert('Erro ao salvar item: ' + result.error);
        }
    } catch (error) {
        console.error('Erro no fetch:', error);
        alert('Ocorreu um erro de comunicação. Verifique o console.');
    }
});
</script>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>
