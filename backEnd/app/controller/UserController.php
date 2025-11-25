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
        error_log("updateProfilePicture: Início do método.");
        try {
            if (!isset($_SESSION['user_id'])) {
                error_log("updateProfilePicture: Erro - Sessão não encontrada.");
                $this->sendJsonError('Não autorizado', 401);
                return;
            }
            error_log("updateProfilePicture: Sessão verificada.");

            if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE   => "O arquivo excede a diretiva upload_max_filesize em php.ini.",
                    UPLOAD_ERR_FORM_SIZE  => "O arquivo excede a diretiva MAX_FILE_SIZE especificada no formulário HTML.",
                    UPLOAD_ERR_PARTIAL    => "O arquivo foi apenas parcialmente carregado.",
                    UPLOAD_ERR_NO_FILE    => "Nenhum arquivo foi carregado.",
                    UPLOAD_ERR_NO_TMP_DIR => "Faltando uma pasta temporária.",
                    UPLOAD_ERR_CANT_WRITE => "Falha ao escrever o arquivo no disco.",
                    UPLOAD_ERR_EXTENSION  => "Uma extensão do PHP interrompeu o upload do arquivo.",
                ];
                $error_code = $_FILES['profile_picture']['error'] ?? UPLOAD_ERR_NO_FILE;
                error_log("updateProfilePicture: Erro de upload - Código: " . $error_code);
                $this->sendJsonError($upload_errors[$error_code] ?? 'Erro desconhecido no upload.', 400);
                return;
            }
            error_log("updateProfilePicture: Verificação de erro de upload passou.");

            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['profile_picture']['type'], $allowed_mime_types)) {
                error_log("updateProfilePicture: Erro - Tipo de arquivo inválido: " . $_FILES['profile_picture']['type']);
                $this->sendJsonError('Tipo de arquivo inválido. Apenas JPG, PNG e GIF são permitidos.', 400);
                return;
            }
            error_log("updateProfilePicture: Verificação de tipo de arquivo passou.");

            $upload_dir = BASE_PATH . '/public/uploads/avatars/';
            if (!is_dir($upload_dir)) {
                error_log("updateProfilePicture: Diretório de upload não existe, tentando criar.");
                if (!mkdir($upload_dir, 0777, true)) {
                    throw new Exception("Falha ao criar o diretório de uploads.");
                }
            }
            error_log("updateProfilePicture: Diretório de upload verificado.");

            $filename = $_SESSION['user_id'] . '_' . time() . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $filepath = $upload_dir . $filename;
            $file_url = '/uploads/avatars/' . $filename;
            error_log("updateProfilePicture: Tentando mover o arquivo para: " . $filepath);

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filepath)) {
                error_log("updateProfilePicture: Arquivo movido com sucesso.");
                $userModel = new UserModel();
                $userModel->updateUser($_SESSION['user_id'], ['profile_picture_url' => $file_url]);
                $this->sendJsonResponse(['message' => 'Foto de perfil atualizada com sucesso.', 'profile_picture_url' => $file_url]);
            } else {
                throw new Exception('Falha ao mover o arquivo carregado. Verifique as permissões do diretório: ' . $upload_dir);
            }

        } catch (Exception $e) {
            error_log("Erro em updateProfilePicture: " . $e->getMessage());
            $this->sendJsonError('Ocorreu um erro interno no servidor ao processar a imagem.', 500);
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