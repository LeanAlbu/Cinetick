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
    public function create($filme_id) {
        $filmeModel = new FilmeModel();
        $filme = $filmeModel->getFilmeById($filme_id);

        if (!$filme) {
            // Handle movie not found
            $this->sendNotFound("Filme não encontrado.");
            return;
        }

        $this->view('pagamento/form', ['filme' => $filme]);
    }

    // Processa o pagamento
    public function store() {
        $filme_id = filter_input(INPUT_POST, 'filme_id', FILTER_DEFAULT);
        $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_DEFAULT);
        $cartao = filter_input(INPUT_POST, 'cartao', FILTER_DEFAULT);

        // Validação
        if (empty($cpf) || empty($cartao) || empty($valor) || empty($filme_id)) {
            $_SESSION['error_message'] = 'Preencha todos os campos.';
            header('Location: ' . BASE_URL . '/pagamento/create/' . $filme_id);
            exit;
        }

        if (strlen($cpf) != 11) {
            $_SESSION['error_message'] = 'CPF inválido.';
            header('Location: ' . BASE_URL . '/pagamento/create/' . $filme_id);
            exit;
        }

        if (strlen($cartao) != 16) {
            $_SESSION['error_message'] = 'Número de cartão inválido.';
            header('Location: ' . BASE_URL . '/pagamento/create/' . $filme_id);
            exit;
        }

        $data = [
            'user_id' => $_SESSION['user_id'],
            'filme_id' => $filme_id,
            'cpf'     => $cpf,
            'valor'   => $valor,
            'status'  => 'aprovado' // Simulação de pagamento aprovado
        ];

        $pagamentoModel = new PagamentoModel();
        $success = $pagamentoModel->savePagamento($data);

        if ($success) {
            // Redireciona para página de sucesso
            header('Location: ' . BASE_URL . '/pagamento/sucesso');
        } else {
            $_SESSION['error_message'] = 'Erro ao processar pagamento.';
            header('Location: ' . BASE_URL . '/pagamento/create/' . $filme_id);
        }
        exit;
    }

    // Página de sucesso
    public function sucesso() {
        $this->view('pagamento/sucesso');
    }
}
