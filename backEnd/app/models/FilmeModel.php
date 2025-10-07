<?php

require_once '../app/core/Utils.php';

class FilmeModel extends Model {
    public function getAllFilmes() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmeById($id) {
        try {
            $binary_id = hex2bin(str_replace('-', '', $id));
            $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE id = :id";
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
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO filmes (id, title, release_year, director, description, imagem_url) VALUES (:id, :title, :release_year, :director, :description, :imagem_url)";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':release_year', $data['release_year']);
            $stmt->bindParam(':director', $data['director']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':imagem_url', $data['imagem_url']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving filme: " . $e->getMessage());
            return false;
        }
    }

    public function getFilmeDeTeste() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE title = 'Filme de Teste'";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllFilmesExcept($title) {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE title != :title";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUpcomingReleases() {
        $currentYear = date('Y');
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE release_year > :currentYear";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':currentYear', $currentYear);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmesByReleaseYear($year) {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE release_year = :year";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmesByReleaseYears($years) {
        $placeholders = implode(',', array_fill(0, count($years), '?'));
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE release_year IN ($placeholders)";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute($years);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateFilme($id, $data) {
        try {
            $binary_id = hex2bin(str_replace('-', '', $id));
            $sql = "UPDATE filmes SET title = :title, release_year = :release_year, director = :director, description = :description, imagem_url = :imagem_url WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':release_year', $data['release_year']);
            $stmt->bindParam(':director', $data['director']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':imagem_url', $data['imagem_url']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating filme: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFilme($id) {
        try {
            $binary_id = hex2bin(str_replace('-', '', $id));
            $sql = "DELETE FROM filmes WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting filme: " . $e->getMessage());
            return false;
        }
    }
}
