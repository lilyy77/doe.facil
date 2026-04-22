-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.12.0.7122
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para cadastro
CREATE DATABASE IF NOT EXISTS `cadastro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `cadastro`;

-- Copiando estrutura para tabela cadastro.agendamento_doacao
CREATE TABLE IF NOT EXISTS `agendamento_doacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `data_agendamento` date NOT NULL,
  `horario_agendamento` time NOT NULL,
  `tipo_entrega` enum('ponto','coleta') NOT NULL,
  `observacao` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `agendamento_doacao_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.agendamento_doacao: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cadastro.carrinho
CREATE TABLE IF NOT EXISTS `carrinho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `data_adicionado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `produto_id` (`produto_id`),
  CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.carrinho: ~2 rows (aproximadamente)
INSERT INTO `carrinho` (`id`, `usuario_id`, `produto_id`, `quantidade`, `data_adicionado`) VALUES
	(3, 4, 1, 2, '2026-01-29 22:29:39'),
	(9, 5, 1, 1, '2026-02-01 19:39:06');

-- Copiando estrutura para tabela cadastro.coletas
CREATE TABLE IF NOT EXISTS `coletas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cidade` varchar(100) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `dia` varchar(50) NOT NULL,
  `horario` varchar(50) NOT NULL,
  `tempo_estimado` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.coletas: ~2 rows (aproximadamente)
INSERT INTO `coletas` (`id`, `cidade`, `bairro`, `dia`, `horario`, `tempo_estimado`) VALUES
	(1, 'canitar', 'jardim vitória', '01/02', '08:00 -  12:00', '13 as 14 Horas '),
	(2, 'cidade', 'centro', '02/02', '08:00 -  12:00', '12 as 14 Horas ');

-- Copiando estrutura para tabela cadastro.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.comentarios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cadastro.curtidas
CREATE TABLE IF NOT EXISTS `curtidas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.curtidas: ~8 rows (aproximadamente)
INSERT INTO `curtidas` (`id`, `post_id`) VALUES
	(1, 4),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1);

-- Copiando estrutura para tabela cadastro.paginas
CREATE TABLE IF NOT EXISTS `paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `texto` longtext DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pagina` (`pagina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.paginas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela cadastro.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `status` enum('ativo','urgente','inativo') DEFAULT 'ativo',
  `urgente` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.produtos: ~0 rows (aproximadamente)
INSERT INTO `produtos` (`id`, `nome`, `categoria`, `imagem`, `ativo`, `status`, `urgente`) VALUES
	(1, 'Alimento', 'Comida', 'uploads/1769726912_Mercado Livre.jfif', 1, 'ativo', 0);

-- Copiando estrutura para tabela cadastro.publicacoes
CREATE TABLE IF NOT EXISTS `publicacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `midia` varchar(255) DEFAULT NULL,
  `data_publicacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.publicacoes: ~3 rows (aproximadamente)
INSERT INTO `publicacoes` (`id`, `titulo`, `descricao`, `midia`, `data_publicacao`) VALUES
	(1, 'dia de doação', 'eu amei, qm amou também?', 'uploads/1771644476_ajuda.png', '2026-02-21 03:27:56'),
	(4, 'logo', 'amooo', 'uploads/1771645845_1763771231_logo.jpg', '2026-02-21 03:50:45');

-- Copiando estrutura para tabela cadastro.site_contato
CREATE TABLE IF NOT EXISTS `site_contato` (
  `id` int(11) NOT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `horario` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Copiando dados para a tabela cadastro.site_contato: ~0 rows (aproximadamente)
INSERT INTO `site_contato` (`id`, `telefone`, `email`, `whatsapp`, `endereco`, `instagram`, `youtube`, `horario`) VALUES
	(1, '14997425309', 'site2026@doefacil.com.br', '14997425308', 'Rua da Solidariedade, nº 777, Bairro Esperança, Jacarezinho – PR, CEP: 86400-000', 'https://www.instagram.com/doe.facil?igsh=YW1oMGJpNTAxam9r', 'https://www.youtube.com/doe.facil?igsh=YW1oMGJpNTAxam9r', '8Hrs - 12Hrs\r\n14Hrs - 17Hrs ');

-- Copiando estrutura para tabela cadastro.site_conteudo
CREATE TABLE IF NOT EXISTS `site_conteudo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo1` varchar(255) DEFAULT NULL,
  `texto1` text DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `titulo2` varchar(255) DEFAULT NULL,
  `texto2` text DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  `titulo3` varchar(255) DEFAULT NULL,
  `texto3` text DEFAULT NULL,
  `imagem3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.site_conteudo: ~0 rows (aproximadamente)
INSERT INTO `site_conteudo` (`id`, `titulo1`, `texto1`, `imagem1`, `titulo2`, `texto2`, `imagem2`, `titulo3`, `texto3`, `imagem3`) VALUES
	(1, 'Bem-vindo!', 'O Doe.Fácil conecta quem quer ajudar com quem mais precisa.\r\nCom pequenos gestos, podemos transformar vidas.\r\nDescubra como fazer parte dessa mudança!', '1763774778_1763771063_Doe.facil.png', 'Unidos por uma causa maior.', 'Por trás de cada campanha, há uma equipe dedicada,\r\nmovida pelo desejo de transformar o mundo.\r\nNo Doe.Fácil, cada ação nasce do amor e da união.', '1763771063_doação.jpg', 'Vale a pena cada esforço.', 'Quem doa, quem apoia, quem simplesmente se importa,\r\ntudo isso se une para transformar vidas. Cada gesto, cada ação, inspira\r\ne juntos criamos um mundo mais justo e solidário.', '1763774778_1763771063_ajuda.png');

-- Copiando estrutura para tabela cadastro.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `data_nascimento` date NOT NULL,
  `cep` varchar(10) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `telefone` (`telefone`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela cadastro.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `telefone`, `data_nascimento`, `cep`, `numero`, `rua`, `bairro`, `cidade`, `isAdmin`) VALUES
	(4, 'Lilian', 'lilian@email.com', '123', '14997425308', '2005-01-29', '12345-678', '10', 'Rua Teste3', 'Centro', 'Cidade', 0),
	(5, 'lilian rodrigues bento', 'lilyyadmin@doe.com.br', 'lilyyadmin@doe.com.br', '', '0000-00-00', '', '', '', '', '', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
