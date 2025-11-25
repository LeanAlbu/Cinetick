<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Gerenciamento da Bomboniere</h1>

<a href="<?= BASE_URL ?>/admin/bomboniere/create" class="btn btn-add">Adicionar Novo Item</a>

<table class="admin-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td>R$ <?= htmlspecialchars(number_format($item['price'], 2, ',', '.')) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/bomboniere/edit/<?= $item['id'] ?>" class="btn btn-edit">Editar</a>
                    <form action="<?= BASE_URL ?>/admin/bomboniere/delete/<?= $item['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja deletar este item?');">
                        <button type="submit" class="btn btn-delete">Deletar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>
