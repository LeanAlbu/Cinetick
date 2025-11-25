<?php

class AdminController extends Controller {

    public function __construct() {
        // Middleware para verificar se o usuário é admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redireciona para a página inicial ou de erro se não for admin
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index() {
        // Lógica para o painel de administração
        $this->view('admin/index');
    }

    public function users() {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        $this->view('admin/users', ['users' => $users]);
    }

    public function filmes() {
        $filmeModel = new FilmeModel();
        $filmes = $filmeModel->getAllFilmes();
        $this->view('admin/filmes', ['filmes' => $filmes]);
    }

    public function bomboniere() {
        $bomboniereModel = new BomboniereModel();
        $items = $bomboniereModel->getAllItems();
        $this->view('admin/bomboniere', ['items' => $items]);
    }

    public function createFilme() {
        $this->view('filme/form');
    }

    public function edit($id) {
        $filmeModel = new FilmeModel();
        $filme = $filmeModel->getFilmeById($id);
        if ($filme) {
            $this->view('admin/edit-filme', ['filme' => $filme]);
        } else {
            // Lidar com filme não encontrado
            $this->view('common/error', ['message' => 'Filme não encontrado.']);
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filmeModel = new FilmeModel();
            $filmeModel->updateFilme($id, $_POST);
        }
        header('Location: ' . BASE_URL . '/admin/filmes');
        exit;
    }

    public function deleteFilme($id) {
        $filmeModel = new FilmeModel();
        $filmeModel->deleteFilme($id);
        header('Location: ' . BASE_URL . '/admin/filmes');
        exit;
    }

    public function promote() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            if ($userId) {
                $userModel = new UserModel();
                $userModel->promoteUser($userId);
            }
        }
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function createBomboniereItem() {
        $this->view('bomboniere/form');
    }

    public function editBomboniereItem($id) {
        $bomboniereModel = new BomboniereModel();
        $item = $bomboniereModel->getItemById($id);
        if ($item) {
            $this->view('bomboniere/form', ['item' => $item]);
        } else {
            $this->view('common/error', ['message' => 'Item não encontrado.']);
        }
    }

    public function storeBomboniereItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bomboniereModel = new BomboniereModel();
            $bomboniereModel->saveItem($_POST);
        }
        header('Location: ' . BASE_URL . '/admin/bomboniere');
        exit;
    }

    public function updateBomboniereItem($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bomboniereModel = new BomboniereModel();
            $bomboniereModel->updateItem($id, $_POST);
        }
        header('Location: ' . BASE_URL . '/admin/bomboniere');
        exit;
    }

    public function deleteBomboniereItem($id) {
        $bomboniereModel = new BomboniereModel();
        $bomboniereModel->deleteItem($id);
        header('Location: ' . BASE_URL . '/admin/bomboniere');
        exit;
    }
}
