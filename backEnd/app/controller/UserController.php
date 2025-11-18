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

            $this->sendJsonResponse([header('Content-Type: application/json; charset=utf-8'),
                'message' => 'Login bem-sucedido.',
                'user' => [
                    'id' => $user['uuid'],
                    'email' => $user['email'],
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

        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);

        if (!$user) {
            // Handle case where user is not found, maybe redirect to login or show an error
            $_SESSION = [];
            session_destroy();
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->view('user/profile', ['user' => $user]);
    }

    public function getProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError('Não autorizado', 401);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);

        if (!$user) {
            $this->sendJsonError('Usuário não encontrado', 404);
            return;
        }

        $this->sendJsonResponse($user);
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError('Não autorizado', 401);
            return;
        }

        $data = $this->getJsonInput();
        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;

        if (!$name || !$email) {
            $this->sendJsonError('Nome e email são obrigatórios', 400);
            return;
        }

        $userModel = new UserModel();
        $success = $userModel->updateUser($_SESSION['user_id'], ['name' => $name, 'email' => $email]);

        if ($success) {
            // Also update the user in the session
            $_SESSION['user_name'] = $name;
            $this->sendJsonResponse(['message' => 'Perfil atualizado com sucesso.']);
        } else {
            $this->sendJsonError('Erro ao atualizar o perfil.', 500);
        }
    }

    public function updateProfilePicture() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError('Não autorizado', 401);
            return;
        }

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['profile_picture']['type'], $allowed_mime_types)) {
                $upload_dir = BASE_PATH . '/public/uploads/avatars/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $filename = $_SESSION['user_id'] . '_' . time() . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                $filepath = $upload_dir . $filename;
                $file_url = '/uploads/avatars/' . $filename;

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filepath)) {
                    $userModel = new UserModel();
                    $userModel->updateUser($_SESSION['user_id'], ['profile_picture_url' => $file_url]);
                    $this->sendJsonResponse(['message' => 'Foto de perfil atualizada com sucesso.', 'profile_picture_url' => $file_url]);
                } else {
                    $this->sendJsonError('Erro ao mover o arquivo.', 500);
                }
            } else {
                $this->sendJsonError('Tipo de arquivo inválido.', 400);
            }
        } else {
            $this->sendJsonError('Nenhum arquivo enviado ou erro no upload.', 400);
        }
    }

    public function showPasswordChangeForm() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->view('user/change-password');
    }

    public function changePassword() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError('Não autorizado', 401);
            return;
        }

        $data = $this->getJsonInput();
        $old_password = $data['old_password'] ?? null;
        $new_password = $data['new_password'] ?? null;
        $confirm_password = $data['confirm_password'] ?? null;

        if (!$old_password || !$new_password || !$confirm_password || $new_password !== $confirm_password) {
            $this->sendJsonError('Dados inválidos.', 400);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findById($_SESSION['user_id']);
        
        $full_user = $userModel->findByEmail($user['email']);

        if ($full_user && password_verify($old_password, $full_user['password'])) {
            $userModel->updateUser($_SESSION['user_id'], ['password' => $new_password]);
            $this->sendJsonResponse(['message' => 'Senha alterada com sucesso.']);
        } else {
            $this->sendJsonError('Senha antiga incorreta.', 401);
        }
    }
}