<?php
class UserController extends ApiController {

    // POST /users
    public function store_user(){
        $data = $this->getJsonInput();
        $errors = [];

        // Validar nome
        if (empty($data['name'])){
            $errors[] = "O nome é obrigatório";
        }

        // Validar email
        if(empty($data['email'])){
            $errors[] = "O email é obrigatório";
        }

        // Validar senha
        if(empty($data['password'])){
            $errors[] = "A senha é obrigatória";
        } else {
            if(!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{8,}$/u', $data['password'])){
                $errors[] = "A senha deve ter pelo menos 8 caracteres, incluindo letras e números";
            }
        }

        if (!empty($errors)) {
            $this->sendJsonError(implode(", ", $errors));
            return;
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($data['email'])) {
            $this->sendJsonError("Este email já está em uso.", 409); // 409 Conflict
            return;
        }
        
        $success = $userModel->saveUser($data);

        if ($success) {
            $this->sendJsonResponse(['message' => 'Usuário criado com sucesso.'], 201);
        } else {
            $this->sendJsonError('Erro ao salvar usuário.', 500);
        }
    }

    // POST /login
    public function login() {
        $data = $this->getJsonInput();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            $this->sendJsonError('Email e senha são obrigatórios.', 400);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['user_id'] = $user['uuid'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'] ?? null;

            $this->sendJsonResponse([
                'message' => 'Login bem-sucedido.',
                'user' => [
                    'id' => $user['uuid'],
                    'name' => $user['name'],
                    'role' => $user['role'] ?? null
                ]
            ]);
        } else {
            $this->sendJsonError('Email ou senha inválidos.', 401); // 401 Unauthorized
        }
    }

    // POST /logout
    public function logout() {
        $_SESSION = [];
        session_destroy();
        $this->sendJsonResponse(['message' => 'Logout bem-sucedido.']);
    }

    // GET /profile
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->renderView('user/profile');
    }
}