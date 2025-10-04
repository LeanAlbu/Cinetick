<?php

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cinetickDB');

// Lógica para criar URLs dinâmicas
$project_path = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');

// URL base da API (aponta para a pasta public do backend)
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $project_path . '/backEnd/public');

// URL base para os assets do frontend (CSS, JS, Imagens)
define('FRONT_ASSETS_URL', 'http://' . $_SERVER['HTTP_HOST'] . $project_path . '/frontEnd');

