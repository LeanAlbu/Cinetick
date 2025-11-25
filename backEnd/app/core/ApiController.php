<?php

class ApiController extends Controller {

    /**
     * Envia uma resposta JSON padronizada.
     *
     * @param mixed $data Os dados para enviar.
     * @param int $statusCode O código de status HTTP.
     */
    protected function sendJsonResponse($data, $statusCode = 200) {
        // --- FIX CRÍTICO: LIMPEZA DE BUFFER ---
        // Se houver qualquer "lixo" (espaços, enters, warnings) acumulado no buffer,
        // esta função joga fora para garantir que o JSON saia limpo.
        if (ob_get_length()) {
            ob_clean();
        }
        // --------------------------------------

        // Define o cabeçalho como JSON e charset UTF-8
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);

        try {
            // Envia o JSON
            echo json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'JSON encoding error: ' . $e->getMessage()]);
        }
        
        // Encerra o script imediatamente para evitar que mais nada seja impresso
        exit;
    }

    /**
     * Envia uma resposta de erro em JSON.
     *
     * @param string $message A mensagem de erro.
     * @param int $statusCode O código de status HTTP.
     */
    protected function sendJsonError($message, $statusCode = 400) {
        // Padroniza o erro num array. Note que usei 'message' ou 'error' dependendo do que seu front espera.
        // No seu código anterior estava 'error', mantive assim.
        $this->sendJsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Obtém os dados de entrada JSON da requisição.
     *
     * @return array|null
     */
    protected function getJsonInput() {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }
}
