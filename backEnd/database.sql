-- Cria o banco se não existir
CREATE DATABASE IF NOT EXISTS `cinetickDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cinetickDB`;

-- ========================================================
-- LIMPEZA (Reseta as tabelas para corrigir a estrutura)
-- ========================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `pagamentos`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `filmes`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `bomboniere_items`;
SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- 1. TABELA DE USUÁRIOS
-- ========================================================
CREATE TABLE `users`(
   `id` BINARY(16) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `email` VARCHAR(255) NOT NULL,
   `password` VARCHAR(255) NOT NULL,
   `role` VARCHAR(255) NOT NULL DEFAULT 'user',
   `profile_picture_url` VARCHAR(255) NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 2. TABELA DE FILMES
-- ========================================================
CREATE TABLE `filmes`(
   `id` BINARY(16) NOT NULL,
   `title` VARCHAR(255) NOT NULL,
   `release_year` YEAR,
   `director` VARCHAR(255),
   `description` TEXT,
   `imagem_url` VARCHAR(255) NULL,
   `em_cartaz` BOOLEAN NOT NULL DEFAULT FALSE,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 3. TABELA DE BANNERS (CORRIGIDA: imagem_path)
-- ========================================================
CREATE TABLE `banners`(
   `id` BINARY(16) NOT NULL,
   `title` VARCHAR(255) NOT NULL,
   `imagem_path` VARCHAR(255) NOT NULL, -- Aqui estava o erro, agora está certo!
   `link_url` VARCHAR(255) NULL,
   `ativo` BOOLEAN NOT NULL DEFAULT FALSE,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 4. TABELA DE PAGAMENTOS
-- ========================================================
CREATE TABLE `pagamentos`(
   `id` BINARY(16) NOT NULL,
   `user_id` BINARY(16) NOT NULL,
   `filme_id` BINARY(16) NOT NULL,
   `cpf` VARCHAR(255) NOT NULL,
   `cartao` VARCHAR(255) NOT NULL,
   `valor` DECIMAL(10, 2) NOT NULL,
   `status` VARCHAR(255) NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
   FOREIGN KEY (filme_id) REFERENCES filmes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 5. TABELA DE COMENTÁRIOS
-- ========================================================
CREATE TABLE `comments`(
   `id` INT AUTO_INCREMENT NOT NULL,
   `filme_id` BINARY(16) NOT NULL,
   `user_id` BINARY(16) NOT NULL,
   `comment` TEXT NOT NULL,
   `rating` INT NOT NULL,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (id),
   FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
   FOREIGN KEY (filme_id) REFERENCES filmes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 6. TABELA DE ITENS DA BOMBONIERE
-- ========================================================
CREATE TABLE `bomboniere_items`(
   `id` BINARY(16) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `description` TEXT,
   `price` DECIMAL(10, 2) NOT NULL,
   `image_url` VARCHAR(255) NULL,
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- INSERIR ADMIN PADRÃO
-- ========================================================
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(UNHEX(REPLACE(UUID(),'-','')), 'admin', 'admin@cinetick.com', '$2y$12$Zt2TvC7fRQURKN3ZIe3ABOI9hokUJX.MeNV3NDmGRyZuwqSrjEulm', 'admin');
