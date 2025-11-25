<?php

class BomboniereController extends ApiController {
    private $bomboniereModel;

    public function __construct() {
        $this->bomboniereModel = new BomboniereModel();
    }

    private function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    // GET /bomboniere
    public function index() {
        $items = $this->bomboniereModel->getAllItems();
        $this->sendJsonResponse($items);
    }

    // GET /bomboniere/{id}
    public function show($id) {
        $item = $this->bomboniereModel->getItemById($id);
        if ($item) {
            $this->sendJsonResponse($item);
        } else {
            $this->sendJsonError('Item não encontrado.', 404);
        }
    }

    // POST /bomboniere (Admin Only)
    public function store() {
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        $data = $this->getJsonInput();
        $required_fields = ['name', 'description', 'price', 'image_url'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $this->sendJsonError("O campo '{$field}' é obrigatório.");
                return;
            }
        }

        if ($this->bomboniereModel->saveItem($data)) {
            $this->sendJsonResponse(['message' => 'Item criado com sucesso.'], 201);
        } else {
            $this->sendJsonError('Erro ao salvar o item.', 500);
        }
    }

    // PUT /bomboniere/{id} (Admin Only)
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

        if ($this->bomboniereModel->updateItem($id, $data)) {
            $this->sendJsonResponse(['message' => 'Item atualizado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao atualizar o item.', 500);
        }
    }

    // DELETE /bomboniere/{id} (Admin Only)
    public function destroy($id) {
        if (!$this->isAdmin()) {
            $this->sendJsonError('Acesso negado.', 403);
            return;
        }

        if ($this->bomboniereModel->deleteItem($id)) {
            $this->sendJsonResponse(['message' => 'Item deletado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao deletar o item.', 500);
        }
    }

    public function showBombonierePage() {
        $this->view('bomboniere/index', ['page_script' => 'bomboniere.js']);
    }
}
