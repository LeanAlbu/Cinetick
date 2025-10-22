<?php

class FilmeController extends ApiController {
    private $filmeModel;

    public function __construct() {
        $this->filmeModel = new FilmeModel();
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

    // GET /em-cartaz
    public function emCartaz() {
        try {
            $currentYear = date('Y');
            $startYear = $currentYear - 40;
            $years = range($startYear, $currentYear);
            $filmes = $this->filmeModel->getFilmesByReleaseYears($years);
            $this->sendJsonResponse($filmes);
        } catch (Throwable $e) {
            $this->sendJsonError('Erro ao buscar filmes em cartaz: ' . $e->getMessage(), 500);
        }
    }

    // GET /futuros-lancamentos
    public function futurosLancamentos() {
        try {
            $filmes = $this->filmeModel->getUpcomingReleases();
            $this->sendJsonResponse($filmes);
        } catch (Throwable $e) {
            $this->sendJsonError('Erro ao buscar futuros lançamentos: ' . $e->getMessage(), 500);
        }
    }
}

