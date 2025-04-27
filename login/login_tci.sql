-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/03/2025 às 18:39
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `login_tci`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbllogin`
--

CREATE TABLE `tbllogin` (
  `idUsuario` int(11) NOT NULL,
  `LoginUsuario` varchar(50) NOT NULL,
  `senhaUsuario` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbllogin`
--

INSERT INTO `tbllogin` (`idUsuario`, `LoginUsuario`, `senhaUsuario`) VALUES
(8, 'wesley', '$2y$10$MW1YcnWmWguHBaSTCNkeJu3AickjP6n/7mlWvx5TK.tMKwfZtPBN6'),
(9, 'Vinicius', '$2y$10$EkMZhQ14WbG.Yk5MiACWtegD4CBhxxGTrolgeJbNYFjgprIyqM.Gi');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbllogin`
--
ALTER TABLE `tbllogin`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbllogin`
--
ALTER TABLE `tbllogin`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
