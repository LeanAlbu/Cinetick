<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="container mt-5">
    <h2>Pagamento de Ingresso</h2>
    <div class="row">
        <div class="col-md-8">
            <h4>Filme: <?= htmlspecialchars($data['filme']['title']) ?></h4>
            <p>Ano: <?= htmlspecialchars($data['filme']['release_year']) ?></p>
            <p>Diretor: <?= htmlspecialchars($data['filme']['director']) ?></p>
            <p>Valor: R$ 25.00</p>

            <form action="<?= BASE_URL ?>/pagamento/store" method="POST">
                <input type="hidden" name="filme_id" value="<?= htmlspecialchars($data['filme']['id']) ?>">
                <input type="hidden" name="valor" value="25.00">

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>

                <div class="form-group">
                    <label for="cartao">Número do Cartão</label>
                    <input type="text" class="form-control" id="cartao" name="cartao" required>
                </div>

                <button type="submit" class="btn btn-primary">Pagar</button>
            </form>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>
