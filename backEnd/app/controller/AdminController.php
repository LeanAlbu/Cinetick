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
}
