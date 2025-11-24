<?php

class UserModel extends Model {

    // --- ADMIN METHODS ---

    public function getAllUsers() {
        // Busca todos os usuários convertendo ID binário para texto
        $sql = "SELECT LOWER(HEX(id)) as id, name, email, role, profile_picture_url FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function promoteUser($userId) {
        try {
            $sql = "UPDATE users SET role = 'admin' WHERE id = UNHEX(:id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error promoting user: " . $e->getMessage());
            return false;
        }
    }

    // --- AUTH & PROFILE METHODS ---

    // Nome antigo: saveUser (era createUser)
    public function saveUser($data) {
        try {
            $sql = "INSERT INTO users (id, name, email, password, role) 
                    VALUES (UNHEX(REPLACE(UUID(),'-','')), :name, :email, :password, 'user')";
            
            $stmt = $this->db->prepare($sql); 

            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':password', $password_hash);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error saving user: " . $e->getMessage());
            return false;
        }
    }

    // Nome antigo: findByEmail
    public function findByEmail($email) {
        $sql = "SELECT LOWER(HEX(id)) as id, name, email, password, role, profile_picture_url 
                FROM users WHERE email = :email";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Nome antigo: findById
    public function findById($id) {
        try {
            $sql = "SELECT LOWER(HEX(id)) as id, name, email, password, role, profile_picture_url 
                    FROM users 
                    WHERE id = UNHEX(:id)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $data) {
        try {
            $fields = [];
            $params = [];

            // Adaptação para aceitar array $data ou parâmetros soltos
            $name = $data['name'] ?? null;
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if ($name) {
                $fields[] = "name = :name";
                $params[':name'] = $name;
            }
            if ($email) {
                $fields[] = "email = :email";
                $params[':email'] = $email;
            }
            if ($password) {
                $fields[] = "password = :password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if (empty($fields)) {
                return true; 
            }

            $params[':id'] = $id;

            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = UNHEX(:id)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);

        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function updateProfilePicture($id, $path) {
        try {
            $sql = "UPDATE users SET profile_picture_url = :path WHERE id = UNHEX(:id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':path', $path);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating profile picture: " . $e->getMessage());
            return false;
        }
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Compatibilidade
    public function getUserById($id) {
        return $this->findById($id);
    }
    public function getUserByEmail($email) {
        return $this->findByEmail($email);
    }
}
