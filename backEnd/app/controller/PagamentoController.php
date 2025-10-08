<?php

class PagamentoController extends ApiController {

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonError('Autenticação necessária.', 401);
            exit;
        }
    }

    // POST /pagamentos
    public function store() {
        $this->requireLogin();

        $data = $this->getJsonInput();

        $filme_id = $data['filme_id'] ?? null;
        $valor = $data['valor'] ?? null;
        $cpf = $data['cpf'] ?? null;
        $cartao = $data['cartao'] ?? null; // Em um app real, isso seria tratado com muito mais segurança (ex: tokenização)

        // Validação
        if (empty($cpf) || empty($cartao) || empty($valor) || empty($filme_id)) {
            $this->sendJsonError('Todos os campos são obrigatórios: filme_id, valor, cpf, cartao.');
            return;
        }

        if (strlen($cpf) != 11) {
            $this->sendJsonError('CPF inválido.');
            return;
        }

        if (strlen($cartao) != 16) {
            $this->sendJsonError('Número de cartão inválido.');
            return;
        }

        $filmeModel = new FilmeModel();
        if (!$filmeModel->getFilmeById($filme_id)) {
            $this->sendJsonError('Filme não encontrado.', 404);
            return;
        }

        $pagamentoData = [
            'user_id' => $_SESSION['user_id'],
            'filme_id' => $filme_id,
            'cpf'     => $cpf,
            'valor'   => $valor,
            'status'  => 'aprovado' // Simulação de pagamento aprovado
        ];

        $pagamentoModel = new PagamentoModel();
        $success = $pagamentoModel->savePagamento($pagamentoData);

        if ($success) {
            $this->sendJsonResponse(['message' => 'Pagamento processado com sucesso.'], 201);
        } else {
            $this->sendJsonError('Erro ao processar pagamento.', 500);
        }
    }
}
