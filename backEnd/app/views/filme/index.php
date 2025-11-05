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
        <div id="movie-detail-container">
            <!-- Movie details will be rendered here by filme.js -->
        </div>

        <div class="comments-section">
            <h2>Comentários e Avaliações</h2>

            <div id="comments-list">
                <?php if (isset($comments) && !empty($comments)):
                    foreach ($comments as $comment):
                 ?>
                        <div class="comment">
                            <p class="comment-author"><strong><?= htmlspecialchars($comment['user_name']) ?></strong></p>
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
                    <input type="hidden" name="filme_id" value="<?= $id ?>">
                    <div class="form-group">
                        <label for="rating">Nota (1 a 5):</label>
                        <input type="number" id="rating" name="rating" min="1" max="5" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comentário:</label>
                        <textarea id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Enviar Comentário</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/frontEnd/js/filme.js"></script>
    <script src="/frontEnd/js/comments.js"></script>
</body>
</html>
