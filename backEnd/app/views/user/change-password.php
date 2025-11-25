<?php include_once __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <h1>Mudar Senha</h1>

    <?php if (isset($_GET['error'])): ?>
        <p class="error">
            <?php 
                switch ($_GET['error']) {
                    case 'invalid_input':
                        echo 'Por favor, preencha todos os campos corretamente.';
                        break;
                    case 'wrong_password':
                        echo 'A senha antiga está incorreta.';
                        break;
                }
            ?>
        </p>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/password/change" method="post">
        <div class="form-group">
            <label for="old_password">Senha Antiga:</label>
            <input type="password" name="old_password" id="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Nova Senha:</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmar Nova Senha:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit">Mudar Senha</button>
    </form>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>