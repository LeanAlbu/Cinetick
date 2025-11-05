CREATE DATABASE IF NOT EXISTS `cinetickDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cinetickDB`;


CREATE TABLE IF NOT EXISTS `filmes`(
   `id` BINARY(16) NOT NULL,
   `title` VARCHAR(255) NOT NULL,
   `release_year` YEAR,
   `director` VARCHAR(255),
   `description` TEXT,
   `imagem_url` VARCHAR(255) NULL,
   PRIMARY KEY (id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users`(
   `id` BINARY(16) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `email` VARCHAR(255) NOT NULL,
   `password` VARCHAR(255) NOT NULL,
   `role` VARCHAR(255) NOT NULL DEFAULT 'user',
   PRIMARY KEY (id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pagamentos`(
   `id` BINARY(16) NOT NULL,
   `user_id` BINARY(16) NOT NULL,
   `filme_id` BINARY(16) NOT NULL,
   `cpf` VARCHAR(255) NOT NULL,
   `cartao` VARCHAR(255) NOT NULL,
   `valor` DECIMAL(10, 2) NOT NULL,
   `status` VARCHAR(255) NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (filme_id) REFERENCES filmes(id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comments`(
   `id` INT AUTO_INCREMENT NOT NULL,
   `filme_id` BINARY(16) NOT NULL,
   `user_id` BINARY(16) NOT NULL,
   `comment` TEXT NOT NULL,
   `rating` INT NOT NULL,
   `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (id),
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (filme_id) REFERENCES filmes(id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(UNHEX(REPLACE(UUID(),'-','')), 'admin', 'admin@cinetick.com', '$2y$12$Zt2TvC7fRQURKN3ZIe3ABOI9hokUJX.MeNV3NDmGRyZuwqSrjEulm', 'admin');
