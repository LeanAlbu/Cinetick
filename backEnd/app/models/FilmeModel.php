<?php

require_once '../app/core/Utils.php';

class FilmeModel extends Model {

    /**
     * Valida e limpa um ID UUID (remove hífens e verifica a validade).
     * @param string $id O UUID vindo da URL.
     * @return string|false O ID limpo (32 caracteres) se for válido, ou false se for inválido.
     */
    private function validateAndCleanId($id) {
        // 1. Remove os hífens
        $clean_id = str_replace('-', '', $id);

        // 2. VALIDAÇÃO:
        // Verifica se o ID tem o comprimento correto (32 caracteres após limpar)
        // E se contém apenas caracteres hexadecimais.
        // Um UUID v4 sem hífens deve ter 32 caracteres.
        if (strlen($clean_id) !== 32 || !ctype_xdigit($clean_id)) {
            // Se o ID for inválido (comprimento errado ou caracteres não-hex),
            // ele não é um UUID válido.
            return false;
        }

        // 3. Retorna o ID limpo e validado
        return $clean_id;
    }

    public function getAllFilmes() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmeById($id) {
        try {
            // Valida o ID primeiro
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                // ID inválido, retorna false para indicar "Não Encontrado"
                return false;
            }

            // Agora a conversão é segura
            $binary_id = hex2bin($clean_id);

            $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url FROM filmes WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getting filme by id: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            // Pega qualquer outro erro (embora hex2bin agora esteja seguro)
            error_log("Error in getFilmeById: " . $e->getMessage());
            return false;
        }
    }

    public function saveFilme($data) {
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO filmes (id, title, release_year, director, description, imagem_url, em_cartaz) VALUES (:id, :title, :release_year, :director, :description, :imagem_url, :em_cartaz)";
            $stmt = $this->db_connection->prepare($sql);

            $em_cartaz = isset($data['em_cartaz']) && $data['em_cartaz'] ? 1 : 0;

            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':release_year', $data['release_year'], PDO::PARAM_INT);
            $stmt->bindParam(':director', $data['director']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':imagem_url', $data['imagem_url']);
            $stmt->bindParam(':em_cartaz', $em_cartaz, PDO::PARAM_INT);

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
            // Valida o ID primeiro
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }

            // Conversão segura
            $binary_id = hex2bin($clean_id);

            $em_cartaz = isset($data['em_cartaz']) && $data['em_cartaz'] ? 1 : 0;

            $sql = "UPDATE filmes SET title = :title, release_year = :release_year, director = :director, description = :description, imagem_url = :imagem_url, em_cartaz = :em_cartaz WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_id);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':release_year', $data['release_year']);
            $stmt->bindParam(':director', $data['director']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':imagem_url', $data['imagem_url']);
            $stmt->bindParam(':em_cartaz', $em_cartaz, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating filme: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in updateFilme: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFilme($id) {
        try {
            // Valida o ID primeiro
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }
            
            // Conversão segura
            $binary_id = hex2bin($clean_id);

            $sql = "DELETE FROM filmes WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting filme: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in deleteFilme: " . $e->getMessage());
            return false;
        }
    }

    public function getFilmesLancamentoMaiorQue($year) {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url, em_cartaz FROM filmes WHERE release_year > :year";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilmesEmCartaz() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title, release_year, director, description, imagem_url, em_cartaz FROM filmes WHERE em_cartaz = 1";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
