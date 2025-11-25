<?php

class BomboniereModel extends Model {

    private function validateAndCleanId($id) {
        $clean_id = str_replace('-', '', $id);
        if (strlen($clean_id) !== 32 || !ctype_xdigit($clean_id)) {
            return false;
        }
        return $clean_id;
    }

    public function getAllItems() {
        $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, name, description, price, image_url FROM bomboniere_items";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById($id) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }

            $binary_id = hex2bin($clean_id);

            $sql = "SELECT LOWER(CONCAT(SUBSTR(HEX(id), 1, 8), '-', SUBSTR(HEX(id), 9, 4), '-', SUBSTR(HEX(id), 13, 4), '-', SUBSTR(HEX(id), 17, 4), '-', SUBSTR(HEX(id), 21))) as id, name, description, price, image_url FROM bomboniere_items WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error getting item by id: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in getItemById: " . $e->getMessage());
            return false;
        }
    }

    public function saveItem($data) {
        try {
            $uuid_string = Utils::generateUuidV4();
            $binary_uuid = hex2bin(str_replace('-', '', $uuid_string));

            $sql = "INSERT INTO bomboniere_items (id, name, description, price, image_url) VALUES (:id, :name, :description, :price, :image_url)";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_uuid);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':image_url', $data['image_url']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving item: " . $e->getMessage());
            return false;
        }
    }

    public function updateItem($id, $data) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }

            $binary_id = hex2bin($clean_id);

            $sql = "UPDATE bomboniere_items SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);

            $stmt->bindParam(':id', $binary_id);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':image_url', $data['image_url']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating item: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in updateItem: " . $e->getMessage());
            return false;
        }
    }

    public function deleteItem($id) {
        try {
            $clean_id = $this->validateAndCleanId($id);
            if ($clean_id === false) {
                return false;
            }
            
            $binary_id = hex2bin($clean_id);

            $sql = "DELETE FROM bomboniere_items WHERE id = :id";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bindParam(':id', $binary_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting item: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in deleteItem: " . $e->getMessage());
            return false;
        }
    }
}
