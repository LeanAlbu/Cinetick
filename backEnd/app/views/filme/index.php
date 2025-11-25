<?php
$comments = $comments ?? [];
$filme = $filme ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Filme</title>
    
    <link rel="stylesheet" href="<?= FRONT_ASSETS_URL ?>/css/style.css">

    <style>
        /* Seus estilos inline podem ficar, mas o ideal é mover para o style.css */
        body { font-family: sans-serif; container-type: inline-size; }
        .container { max-width: 800px; margin: 2rem auto; padding: 1rem; }
        /* ... resto do CSS ... */
    </style>
</head>
<body>
    <header class="main-header"></header>

    <div class="container">
        
        <div id="movie-detail-container" data-id="<?= $id ?? '' ?>">
             <?php if(isset($filme['title'])): ?>
                <h1><?= htmlspecialchars($filme['title']) ?></h1>
                <?php else: ?>
                <p>Carregando detalhes...</p>
             <?php endif; ?>
        </div>

        <div class="comments-section">
            <h2>Comentários e Avaliações</h2>

            <div id="comments-list">
                <?php if (isset($comments) && !empty($comments)):
                    foreach ($comments as $comment):
                 ?>
                        <div class="comment">
                            <p class="comment-author"><strong><?= htmlspecialchars($comment['user_name'] ?? 'Anônimo') ?></strong></p>
                            <p class="comment-rating">Nota: <?= htmlspecialchars($comment['rating']) ?>/5</p>
                            <p class="comment-text"><?= htmlspecialchars($comment['comment']) ?></p>
                            <p class="comment-date"><em><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></em></p>
                        </div>
                <?php 
                    endforeach;
                else: ?>
                    <p>Ainda não há comentários para este filme.</p>
                <?php endif; ?>
            </div>

            <div class="add-comment-form">
                <h3>Deixe seu comentário</h3>
                <form id="comment-form">
                    <input type="hidden" name="filme_id" value="<?= $id ?? '' ?>">
                    <div class="form-group">
                        <label for="rating">Nota (1 a 5):</label>
                        <input type="number" id="rating" name="rating" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comentário:</label>
                        <textarea id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Enviar Comentário</button>
                    <div class="feedback-message" style="display:none;"></div>
                </form>
            </div>
        </div>
    </div>

    <div id="login-modal-container"></div>

    <script type="module" src="<?= FRONT_ASSETS_URL ?>/js/scripts.js"></script>
    
    <script src="<?= FRONT_ASSETS_URL ?>/js/filme.js"></script>
    <script src="<?= FRONT_ASSETS_URL ?>/js/comments.js"></script>

    <script>
        const API_BASE_URL = '<?= BASE_URL ?>';
    </script>
</body>
</html>
