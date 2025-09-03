<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));

session_start();
require_once BASE_PATH . '/config.php';

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

// Rotas de Usuário e Autenticação
$router->addRoute('GET', '/', HomeController::class, "show_index");
$router->addRoute('GET', '/login', UserController::class, 'show_login_form');
$router->addRoute('POST', '/login', UserController::class, 'login');
$router->addRoute('GET', '/logout', UserController::class, 'logout');
$router->addRoute('GET', '/user/create', UserController::class, 'show_user_form');
$router->addRoute('POST', '/user/store', UserController::class, 'store_user');

// Rotas de Filmes (Protegidas no Controller)
$router->addRoute('GET', '/filmes', FilmeController::class, 'index');
$router->addRoute('GET', '/filmes/create', FilmeController::class, 'create');
$router->addRoute('POST', '/filmes/store', FilmeController::class, 'store');


$router->dispatch();

