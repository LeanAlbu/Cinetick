<?php

class PagamentoController extends Controller {

    public function __construct() {
        // Apenas usuários logados podem acessar
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    // Mostra o formulário de pagamento
    public function create() {
        $this->view('pagamento/form');
    }

    // Processa o pagamento
    public function store() {
        $data = [
            'user_id' => $_SESSION['user_id'],
            'cpf'     => filter_input(INPUT_POST, 'cpf', FILTER_DEFAULT),
            'cartao'  => filter_input(INPUT_POST, 'cartao', FILTER_DEFAULT),
            'valor'   => filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'status'  => 'aprovado' // Simulação de pagamento aprovado
        ];

        // Validação simples
        if (empty($data['cpf']) || empty($data['cartao']) || empty($data['valor'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos.';
            header('Location: ' . BASE_URL . '/pagamento/create');
            exit;
        }

        $pagamentoModel = new PagamentoModel();
        $success = $pagamentoModel->savePagamento($data);

        if ($success) {
            // Redireciona para página de sucesso
            header('Location: ' . BASE_URL . '/pagamento/sucesso');
        } else {
            $_SESSION['error_message'] = 'Erro ao processar pagamento.';
            header('Location: ' . BASE_URL . '/pagamento/create');
        }
        exit;
    }

    // Página de sucesso
    public function sucesso() {
        $this->view('pagamento/sucesso');
    }
}
