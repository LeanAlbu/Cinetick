<?php

class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
        // Middleware para verificar se o usuário é admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redireciona para a página inicial ou de erro se não for admin
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index() {
        // Lógica para o painel de administração
        $this->renderView('admin/index');
    }

    public function users() {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        $this->renderView('admin/users', ['users' => $users]);
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
