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
        $filmeModel = new FilmeModel();
        $data['filmes'] = $filmeModel->getFilmeDeTeste();
        $this->view('filme/em-cartaz', $data);
    }

    public function futurosLancamentos()
    {
        $filmeModel = new FilmeModel();
        $data['filmes'] = $filmeModel->getUpcomingReleases();
        $this->view('filme/futuros-lancamentos', $data);
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
            'description' => filter_input(INPUT_POST, 'description', FILTER_DEFAULT),
            'imagem_url' => null // Default value
        ];

        // Basic validation
        if (empty($data['title'])) {
            $_SESSION['error_message'] = 'O título é obrigatório.';
            header('Location: ' . BASE_URL . '/filmes/create');
            exit;
        }

        // --- INÍCIO DA LÓGICA DE UPLOAD ---
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'img/filmes/'; // Diretório dentro da pasta 'public'
            // Garanta que o diretório de upload exista
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $targetPath = $uploadDir . $fileName;

            // Move o arquivo do diretório temporário para o diretório de destino
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $targetPath)) {
                // Salva o caminho relativo para ser usado no src da tag <img>
                $data['imagem_url'] = '/' . $targetPath;
            } else {
                // Opcional: Lidar com falha no upload
                $_SESSION['error_message'] = 'Erro ao fazer upload da imagem.';
                header('Location: ' . BASE_URL . '/filmes/create');
                exit;
            }
        }
        // --- FIM DA LÓGICA DE UPLOAD ---

        $filmeModel = new FilmeModel();
        // Você precisará ajustar o método saveFilme para aceitar 'imagem_url'
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
