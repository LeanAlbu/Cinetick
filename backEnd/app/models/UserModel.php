<?php

require_once BASE_PATH . '/app/core/Utils.php';

class UserModel extends Model {
    public function saveUser($data) {
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO users (id, name, email, password) VALUES (:id, :name, :email, :password)";
            $stmt = $this->db_connection->prepare($sql);

            // Hash da senha
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            // Bind dos parâmetros
            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $password_hash);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving user: " . $e->getMessage());
            return false; // Return false on error
        }
    }

    public function getAllUsers() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, name, email FROM users";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $sql = "SELECT *, LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as uuid FROM users WHERE email = :email";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function promoteUser($userId) {
        $sql = "UPDATE users SET role = 'admin' WHERE id = UNHEX(REPLACE(:id, '-', ''))";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}