<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // FORÇAR A EXIBIÇÃO DO ERRO PARA DEPURAÇÃO
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Falha na conexão com o banco de dados.', 'details' => $e->getMessage()]);
            exit();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}