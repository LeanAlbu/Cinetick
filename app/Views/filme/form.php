<?php
// Simple form for adding a new movie
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Filme</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; }
        .form-container { background-color: white; padding: 2rem; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 3px; }
        button { width: 100%; padding: 0.75rem; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; margin-bottom: 1rem; }
        .back-link { display: block; text-align: center; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Adicionar Novo Filme</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?= $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="/filmes/store" method="POST">
            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="release_year">Ano de Lançamento</label>
                <input type="number" id="release_year" name="release_year" min="1800" max="2100">
            </div>
            <div class="form-group">
                <label for="director">Diretor</label>
                <input type="text" id="director" name="director">
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <button type="submit">Salvar Filme</button>
        </form>
        <a href="/filmes" class="back-link">Voltar para a lista</a>
    </div>
</body>
</html>
