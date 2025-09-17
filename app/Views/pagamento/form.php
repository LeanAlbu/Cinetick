<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>

<div class="content-wrapper">
    <div class="auth-container">
        <h2>Pagamento de Ingresso</h2>
        <div>
            <h4>Filme: <?= htmlspecialchars($data['filme']['title']) ?></h4>
            <p>Ano: <?= htmlspecialchars($data['filme']['release_year']) ?></p>
            <p>Diretor: <?= htmlspecialchars($data['filme']['director']) ?></p>
            <p>Valor: R$ 25.00</p>
        </div>

        <form action="<?= BASE_URL ?>/pagamento/store" method="POST">
            <input type="hidden" name="filme_id" value="<?= htmlspecialchars($data['filme']['id']) ?>">
            <input type="hidden" name="valor" value="25.00">

            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" required>
            </div>

            <div class="form-group">
                <label for="cartao">Número do Cartão</label>
                <input type="text" id="cartao" name="cartao" required>
            </div>

            <button type="submit" class="btn-primary">Pagar</button>
        </form>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>