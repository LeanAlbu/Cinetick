<?php require_once BASE_PATH . '/app/views/templates/header.php'; ?>

<div class="container">
    <h1>Gerenciamento de Banners</h1>

    <button id="add-banner-btn" class="btn btn-add">Adicionar Novo Banner</button>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Imagem</th>
                <th>Link</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="banners-tbody">
            <!-- Banners serão inseridos aqui via JavaScript -->
        </tbody>
    </table>
</div>

<!-- Modal para Adicionar/Editar Banner -->
<div id="banner-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modal-title">Adicionar Banner</h2>
        <form id="banner-form" enctype="multipart/form-data">
            <input type="hidden" id="banner-id" name="id">
            <div class="form-group">
                <label for="title">Título:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="imagem">Imagem do Banner:</label>
                <input type="file" id="imagem" name="imagem">
                <img id="imagem-preview" src="" alt="Pré-visualização da imagem" style="max-width: 200px; margin-top: 10px; display: none;">
            </div>
            <div class="form-group">
                <label for="link_url">URL do Link:</label>
                <input type="text" id="link_url" name="link_url">
            </div>
            <div class="form-group">
                <label for="ativo">Ativo:</label>
                <input type="checkbox" id="ativo" name="ativo">
            </div>
            <button type="submit" class="btn">Salvar</button>
        </form>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/templates/footer.php'; ?>
