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

INSERT INTO filmes (id, title, release_year, director, description) VALUES
(UNHEX(REPLACE(UUID(),'-','')), 'Filme de Teste', 2023, 'Diretor de Teste', 'Este é um filme de teste para verificar a funcionalidade.');

INSERT INTO filmes (id, title, release_year, director, description) VALUES
(UNHEX(REPLACE(UUID(),'-','')), 'Gato de Botas 2', 2022, 'Joel Crawford', 'O Gato de Botas descobre que sua paixão pela aventura cobrou seu preço: ele queimou oito de suas nove vidas. Ele parte em uma jornada épica para encontrar o mítico Último Desejo e restaurar suas nove vidas.'),
(UNHEX(REPLACE(UUID(),'-','')), 'Avatar: O Caminho da Água', 2022, 'James Cameron', 'Jake Sully e Ney\'tiri formaram uma família e estão fazendo de tudo para permanecerem juntos. No entanto, eles devem sair de casa e explorar as regiões de Pandora quando uma antiga ameaça ressurge.'),
(UNHEX(REPLACE(UUID(),'-','')), 'Super Mario Bros. O Filme', 2023, 'Aaron Horvath, Michael Jelenic', 'Mario é um encanador qualquer no Brooklyn que, junto com seu irmão Luigi, vai parar no reino dos cogumelos, governado pela Princesa Peach. Mas o reino está em perigo: o malvado Bowser quer destruir tudo e se casar com Peach.'),
(UNHEX(REPLACE(UUID(),'-','')), 'Homem-Aranha: Através do Aranhaverso', 2023, 'Joaquim Dos Santos, Kemp Powers, Justin K. Thompson', 'Miles Morales é transportado para o multiverso, onde encontra uma equipe de Pessoas-Aranha encarregada de proteger sua própria existência. Quando os heróis discordam sobre como lidar com uma nova ameaça, Miles se vê confrontando as outras Aranhas e deve redefinir o que significa ser um herói.');
