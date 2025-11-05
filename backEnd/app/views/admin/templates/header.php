<?php
$current_page = basename($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - CineTick</title>
    <link rel="icon" type="image/png" href="<?= FRONT_ASSETS_URL ?>/img/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Fjalla+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= FRONT_ASSETS_URL ?>/css/components/admin-layout.css">
</head>
<body class="admin-page">

    <aside class="admin-sidebar">
        <h2>CineTick Admin</h2>
        <nav class="admin-nav">
            <ul>
                <li><a href="<?= BASE_URL ?>/admin" class="<?= ($current_page === 'admin') ? 'active' : '' ?>">Dashboard</a></li>
                <li><a href="<?= BASE_URL ?>/admin/users" class="<?= ($current_page === 'users') ? 'active' : '' ?>">Usuários</a></li>
                <li><a href="<?= BASE_URL ?>/admin/filmes" class="<?= ($current_page === 'filmes') ? 'active' : '' ?>">Filmes</a></li>
                <li><a href="<?= HOME_URL ?>" target="_blank">Ver Site</a></li>
            </ul>
        </nav>
    </aside>

    <main class="admin-main-content">
