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

        // Log para debug (mantido do seu código original)
        error_log("ApiController::sendJsonResponse - Data: " . json_encode($data));

        // Envia o JSON
        echo json_encode($data);
        
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
