<?php

class Model {
    // Mantém a variável antiga para não quebrar os outros controllers
    protected $db_connection;
    
    // Adiciona a variável nova para funcionar com o BannerModel e UserModel
    protected $db;

    public function __construct($dbConnection = null) {
        // Lógica original de conexão
        if ($dbConnection) {
            $connection = $dbConnection;
        } else {
            require_once BASE_PATH . '/app/core/Database.php';
            $connection = Database::getInstance();
        }

        $this->db_connection = $connection; // Para o código antigo
        $this->db = $connection;            // Para o código novo
    }
}
