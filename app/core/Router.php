<?php

class Router
{
    protected $routes = [];

    /**
     * Adiciona uma rota
     */
    public function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'path' => $path,
            'controller' => $controller,
            'method' => strtoupper($method),
            'action' => $action,
        ];
    }

    /**
     * Despacha a rota e chama o controller/action corretos
     */
    public function dispatch()
    {
        $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $request_method = $_SERVER['REQUEST_METHOD'];

        // Ajusta caso o sistema não esteja na raiz
        $base_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($base_path !== '/') {
            $request_uri = substr($request_uri, strlen($base_path));
        }
        if (empty($request_uri)) {
            $request_uri = '/';
        }

        foreach ($this->routes as $route) {
            // Substitui parâmetros dinâmicos {id} → (?P<id>[a-zA-Z0-9_]+)
            $route_pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<$1>[a-zA-Z0-9_]+)', $route['path']);
            $route_pattern = '#^' . $route_pattern . '$#';

            // Verifica se a rota bate com a URL
            if (preg_match($route_pattern, $request_uri, $matches) && $request_method === $route['method']) {
                $controller_name = $route['controller'];
                $action = $route['action'];

                // Valida controller
                if (!class_exists($controller_name)) {
                    $this->sendNotFound("Controller {$controller_name} não encontrado.");
                    return;
                }

                $controller = new $controller_name();

                // Valida método da action
                if (!method_exists($controller, $action)) {
                    $this->sendNotFound("Método {$action} não encontrado no controller {$controller_name}.");
                    return;
                }

                // Remove os índices numéricos dos matches e chama a action
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Se nenhuma rota casar → 404
        $this->sendNotFound();
    }

    /**
     * Exibe página 404 customizada
     */
    private function sendNotFound($message = 'Página não encontrada')
    {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>{$message}</p>";
    }
}
