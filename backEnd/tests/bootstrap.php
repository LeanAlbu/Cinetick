<?php

// Carrega o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Define o caminho base do projeto para que as classes possam ser encontradas
define('BASE_PATH', dirname(__DIR__));

// Carrega o arquivo de configuração (se necessário para constantes como DB_HOST, etc.)
// CUIDADO: Para testes unitários, geralmente queremos MOCAR o banco de dados,
// então evite carregar o config.php que se conecta ao DB real aqui.
// Se precisar de constantes, defina-as aqui ou em um arquivo de config de teste.
// require_once BASE_PATH . '/config.php';

// Exemplo de como definir constantes de banco de dados para um ambiente de teste (opcional)
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'test_db');
// define('DB_USER', 'test_user');
// define('DB_PASS', 'test_pass');
