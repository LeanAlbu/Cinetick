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
$router->addRoute('GET', '/api/user/profile', UserController::class, 'getProfile');
$router->addRoute('GET', '/profile', UserController::class, 'profile');
$router->addRoute('POST', '/profile/update', UserController::class, 'updateProfile');
$router->addRoute('POST', '/profile/update-picture', UserController::class, 'updateProfilePicture');
$router->addRoute('GET', '/password/change', UserController::class, 'showPasswordChangeForm');
$router->addRoute('POST', '/password/change', UserController::class, 'changePassword');
$router->addRoute('GET', '/admin', AdminController::class, 'index');
$router->addRoute('GET', '/admin/users', AdminController::class, 'users');
$router->addRoute('GET', '/admin/filmes', AdminController::class, 'filmes');
$router->addRoute('GET', '/admin/filmes/create', AdminController::class, 'createFilme');
$router->addRoute('GET', '/admin/filmes/edit/{id}', AdminController::class, 'edit');
$router->addRoute('POST', '/admin/filmes/update/{id}', AdminController::class, 'update');
$router->addRoute('POST', '/admin/filmes/delete/{id}', AdminController::class, 'deleteFilme');
$router->addRoute('POST', '/admin/users/promote', AdminController::class, 'promote');

// Rota principal
$router->addRoute('GET', '/', HomeController::class, "show_index");

// Rotas de Filmes (API)
$router->addRoute('GET', '/api/filmes', FilmeController::class, 'index');
$router->addRoute('GET', '/api/filmes/em-cartaz', FilmeController::class, 'emCartaz');
$router->addRoute('GET', '/api/filmes/futuros-lancamentos', FilmeController::class, 'futurosLancamentos');
$router->addRoute('GET', '/api/filmes/todos', FilmeController::class, 'todos');
$router->addRoute('GET', '/api/filmes/{id}', FilmeController::class, 'show'); // Rota genérica por último
$router->addRoute('POST', '/api/filmes', FilmeController::class, 'store');
$router->addRoute('PUT', '/api/filmes/{id}', FilmeController::class, 'update');
$router->addRoute('DELETE', '/api/filmes/{id}', FilmeController::class, 'destroy');

// Rotas de Filmes (Views)
$router->addRoute('GET', '/em-cartaz', FilmeController::class, 'showEmCartazPage');
$router->addRoute('GET', '/futuros-lancamentos', FilmeController::class, 'showFuturosLancamentosPage');
$router->addRoute('GET', '/filmes/todos', FilmeController::class, 'showTodosPage');
$router->addRoute('GET', '/filme/{id}', FilmeController::class, 'showFilmeDetailPage');

// Rotas de Pagamento (API)
$router->addRoute('POST', '/pagamentos', PagamentoController::class, 'store');

// Rotas de Comentários (API)
$router->addRoute('GET', '/api/filmes/{id}/comments', CommentController::class, 'index');
$router->addRoute('POST', '/api/filmes/{id}/comments', CommentController::class, 'store');

// Rotas de Banners (API)
$router->addRoute('GET', '/api/banners', BannerController::class, 'index');
$router->addRoute('GET', '/api/active-banners', BannerController::class, 'activeBanners');
$router->addRoute('GET', '/api/banners/{id}', BannerController::class, 'show');
$router->addRoute('POST', '/api/banners', BannerController::class, 'store');
$router->addRoute('POST', '/api/banners/update/{id}', BannerController::class, 'update');
$router->addRoute('DELETE', '/api/banners/{id}', BannerController::class, 'destroy');

// Rota de Banners (Admin View)
$router->addRoute('GET', '/admin/banners', BannerController::class, 'adminBanners');

$router->dispatch();