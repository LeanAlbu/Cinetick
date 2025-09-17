<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineTick</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/img/favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

    <header class="main-header">
        <a href="<?= BASE_URL ?>/" class="logo-container">
            <img src="<?= BASE_URL ?>/img/logo.png" alt="Logo CineTick">
            <span>CineTick</span>
        </a>
        
       <nav class="main-nav">
            <?php 
            $current_page_uri = $_SERVER['REQUEST_URI']; 
            ?>

            <a href="<?= BASE_URL ?>/" class="<?= (str_ends_with($current_page_uri, '/public/') || str_ends_with($current_page_uri, '/public/index.php')) ? 'active' : '' ?>">Home</a>
            <a href="<?= BASE_URL ?>/em-cartaz" class="<?= (str_ends_with($current_page_uri, '/em-cartaz')) ? 'active' : '' ?>">Em Cartaz</a>
            <a href="<?= BASE_URL ?>/futuros-lancamentos" class="<?= (str_ends_with($current_page_uri, '/futuros-lancamentos')) ? 'active' : '' ?>">Futuros Lançamentos</a>
        </nav>
        
        <div class="user-login">
            <a href="#" id="login-link">Entrar</a>
        </div>
    </header>

    <main> 
<?php require_once BASE_PATH . '/app/Views/templates/header.php'; ?>


<?php require_once BASE_PATH . '/app/Views/templates/footer.php'; ?>