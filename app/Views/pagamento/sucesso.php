<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Pagamento Aprovado!</h4>
        <p>Seu pagamento foi processado com sucesso. Bom filme!</p>
        <hr>
        <p class="mb-0">Você receberá um email com a confirmação.</p>
    </div>
    <a href="<?= BASE_URL ?>/" class="btn btn-primary">Voltar para a Home</a>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>
