<?php
class UserController extends Controller {
    public function show_user_form(){
        $this->view('user/form');
    }

    public function store_user(){
        $errors = [];

        // validar nome
        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        if (empty($name)){
            $errors[] = "O nome é obrigatório";
        }

        // validar email
        $email = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
        if(empty($email)){
            $errors[] = "O email é obrigatório";
        }

        // validar senha
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        if(empty($password)){
            $errors[] = "A senha é obrigatória";
        } else {
            if(!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{8,}$/u', $password)){
                $errors[] = "A senha deve ter pelo menos 8 caracteres, incluindo letras e números";
            }
        }

        if (!empty($errors)) {
            error_log("Validation errors: " . implode(", ", $errors));
            // Redirect back with errors
            $_SESSION['errors'] = $errors;
            // Store old input except password
            $_SESSION['old_input'] = [
                'name' => $name,
                'email' => $email,
            ];
            $this->redirect('/user/create');
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $userModel = new UserModel();
        $success = $userModel->saveUser($data);

        error_log("User save success: " . ($success ? 'true' : 'false'));

        if ($success) {
            $this->redirect('/');
        } else {
            $_SESSION['errors'] = ['Erro ao salvar usuário.'];
            $this->redirect('/user/create');
        }
        exit;
    }

    public function show_login_form() {
        $this->view('user/login');
    }

    public function login() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        if (!$email || !$password) {
            $_SESSION['error_message'] = 'Email e senha são obrigatórios.';
            $this->redirect('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION['user_id'] = $user['uuid'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            $this->redirect('/');
        } else {
            $_SESSION['error_message'] = 'Email ou senha inválidos.';
            $this->redirect('/login');
        }
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }
}

