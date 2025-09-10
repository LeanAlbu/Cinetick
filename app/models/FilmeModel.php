<?php

class FilmeModel extends Model {
    public function getAllFilmes() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description FROM filmes";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmeById($id) {
        try {
            $binary_id = hex2bin(str_replace('-', '', $id));
            $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description FROM filmes WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting filme by id: " . $e->getMessage());
            return false;
        }
    }

    public function saveFilme($data) {
        $sql = "INSERT INTO filmes (title, release_year, director, description) VALUES (:title, :release_year, :director, :description)";
        $stmt = $this->db_connection->prepare($sql);

        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':release_year', $data['release_year']);
        $stmt->bindParam(':director', $data['director']);
        $stmt->bindParam(':description', $data['description']);

        return $stmt->execute();
    }
}
