CREATE DATABASE IF NOT EXISTS `cinetickDB` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cinetickDB`;

CREATE TABLE IF NOT EXISTS `filmes`(
   `id` BINARY(16) NOT NULL,
   `title` VARCHAR(255) NOT NULL,
   `release_year` YEAR,
   `director` VARCHAR(255),
   `description` TEXT,
   PRIMARY KEY (id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users`(
   `id` BINARY(16) NOT NULL,
   `name` VARCHAR(255) NOT NULL,
   `email` VARCHAR(255) NOT NULL,
   `password` VARCHAR(255) NOT NULL,
   PRIMARY KEY (id)
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
