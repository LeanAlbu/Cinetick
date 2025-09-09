<?php

class FilmeController extends Controller {

    /* public function __construct() {
        // Block access to the entire controller if the user is not logged in.
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    } */

    public function index() {
        $filmeModel = new FilmeModel();
        $data['filmes'] = $filmeModel->getAllFilmes();
        
        $this->view('filme/index', $data);
    }

    public function create() {
        // Admin-only check
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo "<h1>403 Forbidden</h1><p>Acesso negado. Apenas administradores podem adicionar filmes.</p>";
            exit;
        }

        $this->view('filme/form');
    }

    public function emCartaz() {
        require_once BASE_PATH . '/app/Views/filme/em-cartaz.php';
    }

    public function futurosLancamentos()
    {
        require_once BASE_PATH . '/app/Views/filme/futuros-lancamentos.php';
    }

    public function store() {
        // Admin-only check
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo "<h1>403 Forbidden</h1><p>Acesso negado.</p>";
            exit;
        }

        $data = [
            'title' => filter_input(INPUT_POST, 'title', FILTER_DEFAULT),
            'release_year' => filter_input(INPUT_POST, 'release_year', FILTER_SANITIZE_NUMBER_INT),
            'director' => filter_input(INPUT_POST, 'director', FILTER_DEFAULT),
            'description' => filter_input(INPUT_POST, 'description', FILTER_DEFAULT)
        ];

        // Basic validation
        if (empty($data['title'])) {
            $_SESSION['error_message'] = 'O título é obrigatório.';
            header('Location: ' . BASE_URL . '/filmes/create');
            exit;
        }

        $filmeModel = new FilmeModel();
        $success = $filmeModel->saveFilme($data);

        if ($success) {
            header('Location: ' . BASE_URL . '/filmes');
        } else {
            $_SESSION['error_message'] = 'Erro ao salvar o filme.';
            header('Location: ' . BASE_URL . '/filmes/create');
        }
        exit;
    }
}
