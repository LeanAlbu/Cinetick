<?php

define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/config.php';
require_once BASE_PATH . '/app/core/Model.php';
require_once BASE_PATH . '/app/models/FilmeModel.php';

$filmeModel = new FilmeModel();
$filmes = $filmeModel->getAllFilmes();

print_r($filmes);
