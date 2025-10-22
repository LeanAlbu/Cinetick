<?php
// Inclui o header
require_once BASE_PATH . '/app/views/templates/header.php';
?>

<div class="container">
    <h2>Todos os Filmes</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Ano de Lançamento</th>
                <th>Diretor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['filmes'] as $filme) : ?>
                <tr>
                    <td><?= htmlspecialchars($filme['id']) ?></td>
                    <td><?= htmlspecialchars($filme['title']) ?></td>
                    <td><?= htmlspecialchars($filme['release_year']) ?></td>
                    <td><?= htmlspecialchars($filme['director']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
// Inclui o footer
require_once BASE_PATH . '/app/views/templates/footer.php';
?>
