<?php

require_once '../app/core/Utils.php';

class BannerModel extends Model {

    private function validateAndCleanId($id) {
        $clean_id = str_replace('-', '', $id);
        if (strlen($clean_id) !== 32 || !ctype_xdigit($clean_id)) {
            return false;
        }
        return $clean_id;
    }

    public function getAllBanners() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title,imagem_path, link_url, ativo FROM banners";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveBanners() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title,imagem_path, link_url, ativo FROM banners WHERE ativo = 1";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBannerById($id) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }

            $binary_id = hex2bin($clean_id);

            $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, title,imagem_path, link_url, ativo FROM banners WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getting banner by id: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in getBannerById: " . $e->getMessage());
            return false;
        }
    }

    public function saveBanner($data) {
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO banners (id, title, imagem_path, link_url, ativo) VALUES (:id, :title, :imagem_path, :link_url, :ativo)";
            $stmt = $this->db_connection->prepare($sql);

            $ativo = isset($data['ativo']) && $data['ativo'] ? 1 : 0;

            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':imagem_path', $data['imagem_path']);
            $stmt->bindParam(':link_url', $data['link_url']);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving banner: " . $e->getMessage());
            return false;
        }
    }

    public function updateBanner($id, $data) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }
    
            $binary_id = hex2bin($clean_id);
    
            $fields = [];
            if (isset($data['title'])) $fields['title'] = $data['title'];
            if (isset($data['imagem_path'])) $fields['imagem_path'] = $data['imagem_path'];
            if (array_key_exists('link_url', $data)) $fields['link_url'] = $data['link_url'];
            if (isset($data['ativo'])) $fields['ativo'] = $data['ativo'] ? 1 : 0;
    
            if (empty($fields)) {
                return true; // Nothing to update
            }
    
            $setClauses = [];
            foreach ($fields as $key => $value) {
                $setClauses[] = "$key = :$key";
            }
            $sql = "UPDATE banners SET " . implode(', ', $setClauses) . " WHERE id = :id";
    
            $stmt = $this->db_connection->prepare($sql);
    
            foreach ($fields as $key => &$value) {
                if ($key === 'ativo') {
                    $stmt->bindParam(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam(":$key", $value);
                }
            }
            $stmt->bindParam(':id', $binary_id);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating banner: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in updateBanner: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBanner($id) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }
            
            $binary_id = hex2bin($clean_id);

            $sql = "DELETE FROM banners WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting banner: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in deleteBanner: " . $e->getMessage());
            return false;
        }
    }
}
