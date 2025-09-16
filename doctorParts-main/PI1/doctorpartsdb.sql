-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 02:42 AM
-- Server version: 11.8.2-MariaDB
-- PHP Version: 8.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctorpartsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrinho`
--

CREATE TABLE `carrinho` (
  `id_carrinho` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT 1,
  `data_adicionado` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carrinho`
--

INSERT INTO `carrinho` (`id_carrinho`, `id_usuario`, `id_produto`, `quantidade`, `data_adicionado`) VALUES
(69, 17, 2, 5, '2025-09-15 00:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome`) VALUES
(1, 'Bauletos'),
(2, 'Óleos e lubrificantes'),
(3, 'Pneus'),
(4, 'Roupas'),
(5, 'Capacetes'),
(6, 'Escapamentos'),
(7, 'Espelhos'),
(8, 'Guidões');

-- --------------------------------------------------------

--
-- Table structure for table `enderecos`
--

CREATE TABLE `enderecos` (
  `id_endereco` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `cep` varchar(255) DEFAULT NULL,
  `rua` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `padrao` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enderecos`
--

INSERT INTO `enderecos` (`id_endereco`, `id_usuario`, `numero`, `cep`, `rua`, `bairro`, `cidade`, `estado`, `complemento`, `padrao`) VALUES
(8, 17, '335', '99711-102', 'Rua Léo Neuls', 'Espírito Santo', 'Erechim', 'RS', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_itens_pedido` int(11) NOT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `itens_pedido`
--

INSERT INTO `itens_pedido` (`id_itens_pedido`, `id_pedido`, `id_produto`, `quantidade`, `preco_unitario`) VALUES
(1, 17, 1, 1, 29.00),
(2, 18, 2, 1, 39.00),
(3, 18, 3, 1, 49.00),
(4, 19, 6, 5, 79.00),
(5, 20, 4, 1, 59.00),
(6, 21, 5, 1, 69.00),
(7, 21, 2, 1, 39.00),
(8, 22, 3, 1, 49.00),
(9, 23, 1, 1, 29.00),
(10, 23, 3, 1, 49.00),
(11, 24, 2, 1, 39.00),
(12, 24, 3, 1, 49.00),
(13, 25, 3, 1, 49.00),
(14, 26, 2, 1, 39.00),
(15, 27, 19, 2, 349.90),
(16, 28, 20, 1, 599.00);

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_endereco` int(11) DEFAULT NULL,
  `data_pedido` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','pago','enviado','entregue','cancelado') DEFAULT 'pendente',
  `total` decimal(10,2) DEFAULT NULL,
  `forma_pagamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_usuario`, `id_endereco`, `data_pedido`, `status`, `total`, `forma_pagamento`) VALUES
(1, 17, 8, '2025-09-14 20:57:29', 'pago', 88.00, 'cartao'),
(2, 17, 8, '2025-09-14 20:59:26', 'pago', 118.00, 'cartao'),
(3, 17, 8, '2025-09-14 21:01:01', 'pago', 49.00, 'boleto'),
(4, 17, 8, '2025-09-14 21:03:02', 'pago', 29.00, 'cartao'),
(5, 17, 8, '2025-09-14 21:04:38', 'pago', 49.00, 'cartao'),
(6, 17, 8, '2025-09-14 21:04:55', 'pago', 39.00, 'boleto'),
(7, 17, 8, '2025-09-14 21:05:13', 'pago', 59.00, 'pix'),
(8, 17, 8, '2025-09-14 21:07:22', 'pago', 39.00, 'cartao'),
(9, 17, 8, '2025-09-14 21:13:12', 'pago', 29.00, 'cartao'),
(10, 17, 8, '2025-09-14 21:16:06', 'pago', 69.00, 'boleto'),
(11, 17, 8, '2025-09-14 21:16:28', 'pago', 59.00, 'pix'),
(12, 17, 8, '2025-09-14 21:20:47', 'pago', 39.00, 'cartao'),
(13, 17, 8, '2025-09-14 21:21:01', 'pago', 49.00, 'pix'),
(14, 17, 8, '2025-09-14 21:21:35', 'pago', 79.00, 'cartao'),
(15, 17, 8, '2025-09-14 21:22:49', 'pago', 49.00, 'cartao'),
(16, 17, 8, '2025-09-14 21:31:14', 'pago', 395.00, 'cartao'),
(17, 17, 8, '2025-09-14 21:37:56', 'pago', 29.00, 'cartao'),
(18, 17, 8, '2025-09-14 21:38:11', 'pago', 88.00, 'boleto'),
(19, 17, 8, '2025-09-14 21:38:30', 'pago', 395.00, 'pix'),
(20, 17, 8, '2025-09-14 21:40:23', 'pago', 59.00, 'pix'),
(21, 17, 8, '2025-09-14 21:52:52', 'pago', 108.00, 'boleto'),
(22, 17, 8, '2025-09-14 22:04:53', 'pago', 49.00, 'pix'),
(23, 17, 8, '2025-09-14 22:11:56', 'pago', 98.00, 'cartao'),
(24, 17, 8, '2025-09-14 22:12:45', 'pago', 108.00, 'cartao'),
(25, 17, 8, '2025-09-14 22:15:48', 'pago', 69.00, 'cartão'),
(26, 17, 8, '2025-09-14 22:19:10', 'pago', 59.00, 'cartão'),
(27, 17, 8, '2025-09-14 23:55:09', 'pago', 719.80, 'cartão'),
(28, 17, 8, '2025-09-14 23:56:01', 'pago', 619.00, 'cartão');

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produtos`
--

INSERT INTO `produtos` (`id_produto`, `id_categoria`, `nome`, `descricao`, `preco`, `estoque`, `image_url`, `data_criacao`) VALUES
(1, 1, 'Bauleto', 'Bauleto preto', 29.00, 10, '../../../assets/images/bauletoBraz5Adventure56LPreto.png', '2025-07-27 20:33:46'),
(2, 5, 'Capacete', 'Capacete HJC', 39.00, 10, '../../../assets/images/capaceteHJC.jpg', '2025-07-27 20:45:53'),
(3, 6, 'Escapamento', 'Escapamento GSX', 49.00, 10, '../../../assets/images/escapeGSX-R1000.jpg', '2025-07-27 20:45:53'),
(4, 7, 'Espelho', 'Espelho Risoma Triangular', 59.00, 10, '../../../assets/images/espelhoRisomaTriangular.png', '2025-07-27 20:45:53'),
(5, 8, 'Guidão', 'Guidão Renthal', 69.00, 10, '../../../assets/images/guidaoRenthal.jpg', '2025-07-27 20:45:53'),
(6, 4, 'Jaqueta', 'Jaqueta Alpine Star', 79.00, 10, '../../../assets/images/jaquetaAlpineStar.png', '2025-07-27 20:45:53'),
(7, 2, 'Óleo 2t510', 'oleo 2t510', 29.90, 10, '../../../assets/images/oleo2t510.png', '2025-09-14 23:07:15'),
(8, 2, 'Óleo 2t800', 'oleo 2t800', 34.50, 10, '../../../assets/images/oleo2t800.png', '2025-09-14 23:07:15'),
(9, 2, 'Óleo 4t300v-5w40', 'oleo 4t300v-5w40', 62.90, 10, '../../../assets/images/oleo4t300v-5w40.png', '2025-09-14 23:07:15'),
(10, 2, 'Óleo 4t3000-20w50', 'oleo 4t3000-20w50', 39.00, 10, '../../../assets/images/oleo4t3000-20w50.png', '2025-09-14 23:07:15'),
(11, 2, 'Óleo 4t5000-10w30', 'oleo 4t5000-10w30', 43.90, 10, '../../../assets/images/oleo4t5000-10w30.png', '2025-09-14 23:07:15'),
(12, 2, 'Óleo 4t7100-15w50', 'oleo 4t7100-15w50', 59.90, 10, '../../../assets/images/oleo4t7100-15w50.png', '2025-09-14 23:07:15'),
(13, 3, 'Pneu Michelin Power 6', 'Pneu Michelin Power 6', 749.90, 10, '../../../assets/images/pneuMichelinPower6.png', '2025-09-14 23:13:59'),
(14, 3, 'Pneu Michelin Power Cup 2', 'Pneu Michelin Power Cup 2', 899.00, 10, '../../../assets/images/pneuMichelinPowerCup2.png', '2025-09-14 23:13:59'),
(15, 3, 'Pneu Michelin Power Supermoto', 'Pneu Michelin Power Supermoto', 1029.90, 10, '../../../assets/images/pneuMichelinPowerSupermoto.png', '2025-09-14 23:13:59'),
(16, 3, 'Pneu Pirelli Diablo Supercorsa SC-V4', 'Pneu Pirelli Diablo Supercorsa SC-V4', 1199.90, 10, '../../../assets/images/pneuPirelliDiabloSupercorsaSC-V4.png', '2025-09-14 23:13:59'),
(17, 3, 'Pneu Pirelli Scorpion Rally STR', 'Pneu Pirelli Scorpion Rally STR', 849.90, 10, '../../../assets/images/pneuPirelliScorpionRallySTR.png', '2025-09-14 23:13:59'),
(18, 3, 'Pneu Pirelli Scorpion Trail-III', 'Pneu Pirelli Scorpion Trail-III', 789.00, 10, '../../../assets/images/pneuPirelliScorpionTrail-III.png', '2025-09-14 23:13:59'),
(19, 4, 'Luva Alpinestars', 'Luva Alpinestars', 349.90, 10, '../../../assets/images/roupaAlpinestars.webp', '2025-09-14 23:31:19'),
(20, 4, 'Macacão InsaneX', 'Roupa completa InsaneX', 599.00, 10, '../../../assets/images/roupaInsaneX.webp', '2025-09-14 23:31:19'),
(21, 4, 'Luva Qcpj', 'Luva Qcpj', 189.90, 10, '../../../assets/images/roupaQcpj.webp', '2025-09-14 23:31:19'),
(22, 4, 'Jaqueta Sulaite', 'Jaqueta Sulaite', 429.90, 10, '../../../assets/images/roupaSulaite.webp', '2025-09-14 23:31:19'),
(23, 4, 'Macacão Volson', 'Roupa térmica Volson', 299.00, 10, '../../../assets/images/roupaVolson.webp', '2025-09-14 23:31:19'),
(24, 4, 'Luva X11', 'Luva X11 Racing', 229.90, 10, '../../../assets/images/roupaX11.webp', '2025-09-14 23:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `cpf` varchar(21) DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `contato`, `senha`, `data_criacao`, `cpf`, `isadmin`) VALUES
(15, 'bolsonaro', 'bolsonaro@gmail.com', '(65) 46465-4564', '$2y$12$vyzwevBh4j3vRWVl5HKKT.OMulGGPPRqfEd3CeDN9hNc0c89gdUMq', '2025-07-29 21:06:05', '456.465.564-64', 1),
(17, 'lucas bertuol', 'lucas@email.com', '(54) 32132-1321', '$2y$10$v51NjqDiANXJIp2v2jMRxO90EIrexpSavEssvXAeYtJqOJwf0P0zC', '2025-08-24 23:08:19', '030.458.120-82', 0),
(18, 'as', 'asdasd@gmail.com', '12312312312312', '$2y$12$cd/1Vlexl3fwzbvEU6LVw.5LvJMfet1ReeEbDGSh2eMLmIsyYKd6O', '2025-09-15 19:18:47', '123123123123123123123', 0),
(19, 'awdawd', 'asdasd@gmail.com', '435345345345345', '$2y$12$TmAmGsr5Vl219EOwDxDv3eE6XYaU0LD6tPZ8.M4oIr0DWydrJIe3i', '2025-09-15 19:44:41', '45345345345', 0),
(20, 'bolsonaro', 'bernardo@gmail.com', '(23) 42342-3423', '$2y$12$8xHekxHaSyLfBqMlGXC2f.CJra9T.Ijlz/rUvBxXfcYTyxbfBngTq', '2025-09-15 21:16:42', '231.423.423-42', 0),
(21, '12312312', 'berna@gmail.com', '(12) 31231-2312', '$2y$12$lpjs88JIdMYZEkiNF9yEauUmBLB2b8zpoq/v0.Pya3oMqNPhkd9km', '2025-09-15 21:23:37', '123.123.231-23', 0),
(22, 'simo', 'simo@gmail.com', '(12) 31231-2312', '$2y$12$tX07vj5/5HyJKcSvHpjQBef3eD1lpKRjr33bMbOnHhsTGs2HPuSEO', '2025-09-15 21:29:36', '456.465.564-64', 0),
(23, 'bolsonaro', 'bolso@gmail.com', '(90) 28394-2038', '$2y$12$RS8It3Iz9Sbx1vZ75nSWmehPkkocuLLizK8HFRhvKYDJESZVQg4Qe', '2025-09-15 21:34:25', '923.481.209-38', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`id_carrinho`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_itens_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_produto` (`id_produto`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_endereco` (`id_endereco`);

--
-- Indexes for table `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `id_carrinho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id_endereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id_itens_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Constraints for table `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `enderecos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`);

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_endereco`) REFERENCES `enderecos` (`id_endereco`);

--
-- Constraints for table `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
