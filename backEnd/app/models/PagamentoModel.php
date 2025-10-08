<?php

require_once '../app/core/Utils.php';

class PagamentoModel extends Model {
    public function savePagamento($data) {
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO pagamentos (id, user_id, filme_id, cpf, valor, status) VALUES (:id, :user_id, :filme_id, :cpf, :valor, :status)";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':filme_id', $data['filme_id']);
            $stmt->bindParam(':cpf', $data['cpf']);
            $stmt->bindParam(':valor', $data['valor']);
            $stmt->bindParam(':status', $data['status']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving pagamento: " . $e->getMessage());
            return false;
        }
    }
}
