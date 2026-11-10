-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 26-Jun-2024 às 15:29
-- Versão do servidor: 8.0.36
-- versão do PHP: 8.2.19
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto`
--
-- --------------------------------------------------------
--
-- Estrutura da tabela `andamento`
--
CREATE TABLE
  `andamento` (
    `nome` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `email` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `pergunta_local` varchar(20) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `problema` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `importante` char(30) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `id` int NOT NULL,
      `hora_timestamp` datetime DEFAULT NULL,
      `andamento_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `pdd_number` int DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `armazem`
--
CREATE TABLE
  `armazem` (
    `nome` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `email` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `pergunta_local` varchar(20) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `problema` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `importante` char(30) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `id` int NOT NULL,
      `hora_timestamp` datetime DEFAULT NULL,
      `andamento_timestamp` datetime DEFAULT NULL,
      `feito_timestamp` datetime DEFAULT NULL,
      `pdd_number` int DEFAULT NULL,
      `resolve` varchar(300) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `feito`
--
CREATE TABLE
  `feito` (
    `nome` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `email` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `pergunta_local` varchar(20) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `problema` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `importante` char(30) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `id` int NOT NULL,
      `hora_timestamp` datetime DEFAULT NULL,
      `andamento_timestamp` datetime DEFAULT NULL,
      `feito_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `pdd_number` int DEFAULT NULL,
      `resolve` varchar(300) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `form`
--
CREATE TABLE
  `form` (
    `nome` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `email` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `pergunta_local` varchar(20) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `problema` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `importante` char(30) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `id` int NOT NULL,
      `hora_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
      `pdd_number` int DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `item`
--
CREATE TABLE
  `item` (
    `id` int NOT NULL,
    `nome` varchar(100) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `quantidade` int DEFAULT NULL,
      `utilidade` varchar(80) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `detalhes` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `item_creat` datetime DEFAULT CURRENT_TIMESTAMP
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `pessoal`
--
CREATE TABLE
  `pessoal` (
    `nome` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `email` varchar(50) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `pergunta_local` varchar(20) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `problema` varchar(200) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `importante` char(30) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
      `id` int NOT NULL,
      `hora_timestamp` datetime DEFAULT NULL,
      `andamento_timestamp` datetime DEFAULT NULL,
      `feito_timestamp` datetime DEFAULT NULL,
      `pdd_number` int DEFAULT NULL,
      `resolve` varchar(300) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
--
-- Estrutura da tabela `users`
--
CREATE TABLE
  `users` (
    `id` int NOT NULL,
    `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `role` enum ('admin', 'user') COLLATE utf8mb4_general_ci DEFAULT 'user',
    `email` varchar(80) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--
INSERT INTO
  `users` (`id`, `username`, `password`, `role`, `email`)
VALUES
  (
    4,
    'admin',
    '$2y$10$OoGYVeSZ5w4.wHdPArRCeu6tvfLrk9BfGETVRoZwv3yNK1MQU2n4a',
    'admin',
    'sml.ticket@outlook.pt'
  ),
  (
    5,
    'guilherme',
    '$2y$10$38QGi5QqZt77k7880CKuDOiGiVdxIpmcOSYz5lCIrtH.Vkv92Cao.',
    'user',
    'guilhermefernandes@sml.com'
  );

--
-- Índices para tabelas despejadas
--
--
-- Índices para tabela `andamento`
--
ALTER TABLE `andamento` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `armazem`
--
ALTER TABLE `armazem` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `feito`
--
ALTER TABLE `feito` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `form`
--
ALTER TABLE `form` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `item`
--
ALTER TABLE `item` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pessoal`
--
ALTER TABLE `pessoal` ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de tabelas despejadas
--
--
-- AUTO_INCREMENT de tabela `andamento`
--
ALTER TABLE `andamento` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 27;

--
-- AUTO_INCREMENT de tabela `armazem`
--
ALTER TABLE `armazem` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT de tabela `feito`
--
ALTER TABLE `feito` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 24;

--
-- AUTO_INCREMENT de tabela `form`
--
ALTER TABLE `form` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 23;

--
-- AUTO_INCREMENT de tabela `item`
--
ALTER TABLE `item` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 13;

--
-- AUTO_INCREMENT de tabela `pessoal`
--
ALTER TABLE `pessoal` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;