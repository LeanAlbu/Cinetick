<?php

class FilmeController extends ApiController {
    private $filmeModel;
    private $commentModel;

    public function __construct() {
        $this->filmeModel = new FilmeModel();
        $this->commentModel = new CommentModel();
    }

    private function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    // GET /filmes
    public function index() {
        $filmes = $this->filmeModel->getAllFilmes();
        $this->sendJsonResponse($filmes);
    }

    // GET /filmes/{id}
    public function show($id) {
        $filme = $this->filmeModel->getFilmeById($id);
        if ($filme) {
            $this->sendJsonResponse($filme);
        } else {
            $this->sendJsonError('Filme não encontrado.', 404);
        }
    }

    // POST /filmes (Admin Only)
    public function store() {
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        $data = $this->getJsonInput();
        $required_fields = ['title', 'release_year', 'director', 'description', 'imagem_url'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $this->sendJsonError("O campo '{$field}' é obrigatório.");
                return;
            }
        }

        if ($this->filmeModel->saveFilme($data)) {
            $this->sendJsonResponse(['message' => 'Filme criado com sucesso.'], 201);
        } else {
            $this->sendJsonError('Erro ao salvar o filme.', 500);
        }
    }

    // PUT /filmes/{id} (Admin Only)
    public function update($id) {
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        $data = $this->getJsonInput();
        if (empty($data)) {
            $this->sendJsonError('Dados inválidos.');
            return;
        }

        if ($this->filmeModel->updateFilme($id, $data)) {
            $this->sendJsonResponse(['message' => 'Filme atualizado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao atualizar o filme.', 500);
        }
    }

    // DELETE /filmes/{id} (Admin Only)
    public function destroy($id) {
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        if ($this->filmeModel->deleteFilme($id)) {
            $this->sendJsonResponse(['message' => 'Filme deletado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao deletar o filme.', 500);
        }
    }

    // VIEW RENDERERS

    public function showEmCartazPage() {
        $this->view('filme/em-cartaz', ['page_script' => 'em-cartaz.js']);
    }

    public function showFuturosLancamentosPage() {
        $this->view('filme/futuros-lancamentos', ['page_script' => 'futuros-lancamentos.js']);
    }

    public function showTodosPage() {
        $this->view('filme/todos', ['page_script' => 'todos-os-filmes.js']);
    }

    public function showFilmeDetailPage($id) {
        $filme = $this->filmeModel->getFilmeById($id);
        $comments = $this->commentModel->getCommentsByFilmeId($id);

        $this->view('filme/index', ['id' => $id, 'filme' => $filme, 'comments' => $comments, 'page_script' => 'filme.js']);
    }


    // GET /em-cartaz
    public function emCartaz() {
        try {
            $filmes = $this->filmeModel->getFilmesEmCartaz();
            error_log("Filmes em cartaz: " . print_r($filmes, true));
            $this->sendJsonResponse($filmes);
        } catch (Throwable $e) {
            error_log("Erro ao buscar filmes em cartaz: " . $e->getMessage());
            $this->sendJsonError('Erro ao buscar filmes em cartaz: ' . $e->getMessage(), 500);
        }
    }

    public function futurosLancamentos() {
        try {
            $currentYear = date('Y');
            $filmes = $this->filmeModel->getFilmesLancamentoMaiorQue($currentYear);
            $this->sendJsonResponse($filmes);
        } catch (Throwable $e) {
            $this->sendJsonError('Erro ao buscar futuros lançamentos: ' . $e->getMessage(), 500);
        }
    }



    public function todos() {
        $filmes = $this->filmeModel->getAllFilmes();
        error_log("FilmeController::todos - Sending JSON response.");
        $this->sendJsonResponse($filmes);
    }
}
