<?php

// Lida com requisições de pre-flight do CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config.php';
session_start();


//LOADER DAS CLASSES

spl_autoload_register(function($class_name){
   $paths = [
      BASE_PATH . '/app/core/',
      BASE_PATH . '/app/controller/',
      BASE_PATH . '/app/models/'
   ];
   foreach($paths as $path){
      $file = $path . $class_name . '.php';
      if (file_exists($file)){
         require_once $file;
         return;
      }
   }
});
$router = new Router();


//------ROTAS------

// Rotas de Usuário e Autenticação (API)
$router->addRoute('POST', '/users', UserController::class, 'store_user');
$router->addRoute('POST', '/login', UserController::class, 'login');
$router->addRoute('POST', '/logout', UserController::class, 'logout');

// Rota principal
$router->addRoute('GET', '/', HomeController::class, "show_index");

// Rotas de Filmes (API)
$router->addRoute('GET', '/filmes', FilmeController::class, 'index');
$router->addRoute('GET', '/filmes/{id}', FilmeController::class, 'show');
$router->addRoute('POST', '/filmes', FilmeController::class, 'store');
$router->addRoute('PUT', '/filmes/{id}', FilmeController::class, 'update');
$router->addRoute('DELETE', '/filmes/{id}', FilmeController::class, 'destroy');
$router->addRoute('GET', '/em-cartaz', FilmeController::class, 'emCartaz');
$router->addRoute('GET', '/futuros-lancamentos', FilmeController::class, 'futurosLancamentos');

// Rotas de Pagamento (API)
$router->addRoute('POST', '/pagamentos', PagamentoController::class, 'store');

$router->dispatch();


