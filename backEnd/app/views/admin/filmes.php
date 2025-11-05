<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Gerenciamento de Filmes</h1>

<a href="<?= BASE_URL ?>/admin/filmes/create" class="btn btn-add">Adicionar Novo Filme</a>

<table class="admin-table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Ano</th>
            <th>Diretor</th>
            <th>Em Cartaz</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filmes as $filme): ?>
            <tr>
                <td><?= htmlspecialchars($filme['title']) ?></td>
                <td><?= htmlspecialchars($filme['release_year']) ?></td>
                <td><?= htmlspecialchars($filme['director']) ?></td>
                <td><?= !empty($filme['em_cartaz']) ? 'Sim' : 'Não' ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/filmes/edit/<?= $filme['id'] ?>" class="btn btn-edit">Editar</a>
                    <form action="<?= BASE_URL ?>/admin/filmes/delete/<?= $filme['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja deletar este filme?');">
                        <button type="submit" class="btn btn-delete">Deletar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>