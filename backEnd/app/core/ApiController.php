<?php

class ApiController extends Controller {
    /**
     * Envia uma resposta JSON padronizada.
     *
     * @param mixed $data Os dados para enviar.
     * @param int $statusCode O código de status HTTP.
     */
    protected function sendJsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Envia uma resposta de erro em JSON.
     *
     * @param string $message A mensagem de erro.
     * @param int $statusCode O código de status HTTP.
     */
    protected function sendJsonError($message, $statusCode = 400) {
        $this->sendJsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Obtém os dados de entrada JSON da requisição.
     *
     * @return array|null
     */
    protected function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
    }
}
