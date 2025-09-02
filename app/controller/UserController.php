<?php
class UserController extends Controller {
    public function show_user_form(){
        require_once BASE_PATH . '/app/views/user/form.php';
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
            if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)){
                $errors[] = "A senha deve ter pelo menos 8 caracteres, incluindo letras e números";
            }
        }

        // Se houver erros, redireciona
        if (!empty($errors)) {
            // Você pode salvar os erros na sessão se quiser exibi-los depois
            // $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . '/error'); 
            exit;
        }

        // Monta array de dados (não precisa do ID)
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $UserModel = new UserModel();
        $success = $UserModel->saveUser($data);

        if ($success) {
            header('Location: ' . BASE_URL . '/index'); 
        } else {
            header('Location: ' . BASE_URL . '/error'); 
        }
        exit;
    }
}

