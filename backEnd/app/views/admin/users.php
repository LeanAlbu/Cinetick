<?php require_once BASE_PATH . '/app/views/admin/templates/header.php'; ?>

<h1>Gerenciamento de Usuários</h1>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Role</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role'] ?? 'N/A'); ?></td>
                <td>
                    <?php if (($user['role'] ?? null) !== 'admin'): ?>
                        <form action="<?= BASE_URL ?>/admin/users/promote" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <button type="submit" class="btn btn-edit">Tornar Admin</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once BASE_PATH . '/app/views/admin/templates/footer.php'; ?>
