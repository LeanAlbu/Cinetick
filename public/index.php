<?php
define('BASE_PATH', dirname(__DIR__));

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

$router->addRoute('GET', '/', HomeController::class, "show_index");

