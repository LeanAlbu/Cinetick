<?php
// Simple view to list movies
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filmes</title>
    <style>
        body { font-family: sans-serif; container-type: inline-size; }
        .container { max-width: 800px; margin: 2rem auto; padding: 1rem; }
        .header { display: flex; justify-content: space-between; align-items: center; }
        .add-btn { background-color: #28a745; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        th { background-color: #f2f2f2; }
        .logout-btn { background-color: #dc3545; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Lista de Filmes</h1>
            <div>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/filmes/create" class="add-btn">Adicionar Filme</a>
                <?php endif; ?>
                <a href="/logout" class="logout-btn">Logout</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Ano</th>
                    <th>Diretor</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($filmes) && !empty($filmes)): ?>
                    <?php foreach ($filmes as $filme): ?>
                        <tr>
                            <td><?= htmlspecialchars($filme['title']) ?></td>
                            <td><?= htmlspecialchars($filme['release_year']) ?></td>
                            <td><?= htmlspecialchars($filme['director']) ?></td>
                            <td><?= htmlspecialchars($filme['description']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Nenhum filme encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
