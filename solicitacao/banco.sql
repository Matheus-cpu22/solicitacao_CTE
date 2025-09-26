CREATE DATABASE Site;
USE Site;


-- TABELA DAS REQUISIÇÕES
CREATE TABLE tblsolicitacao(
	id INT PRIMARY KEY AUTO_INCREMENT,
	nomeMotorista VARCHAR(130) NOT NULL,
	placaCavalo VARCHAR(8) NOT NULL,
	placaCarretas VARCHAR(20) NOT NULL,
	destino VARCHAR(20) NOT NULL,
	valorOperacao DECIMAL(10,2) NOT NULL,
	tipoOperacao ENUM('CT-e','MDF-e','CT-e E MDF-e') NOT NULL,
	observacaoSolicitacao TEXT NOT NULL,
	observacaoExtra TEXT NULL,
	arquivoPDF VARCHAR(255) NULL,
	status ENUM('Pendente','Concluído') DEFAULT 'Pendente' NOT NULL,
	dataSolicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	dataConclusao TIMESTAMP NULL
);

-- Indices para consultas mais otimizadas
CREATE INDEX idx_tipoOperacao ON tblsolicitacao(tipoOperacao);
CREATE INDEX idx_status ON tblsolicitacao(STATUS);

CREATE TABLE tblarquivado(
	id INT PRIMARY KEY,
	nomeMotorista VARCHAR(130) NOT NULL,
	placaCavalo VARCHAR(8) NOT NULL,
	placaCarretas VARCHAR(20) NOT NULL,
	destino VARCHAR(20) NOT NULL,
	valorOperacao DECIMAL(10,2) NOT NULL,
	tipoOperacao ENUM('CT-e','MDF-e','CT-e E MDF-e') NOT NULL,
	observacaoSolicitacao TEXT NOT NULL,
	observacaoExtra TEXT NULL,
	arquivoPDF VARCHAR(255) NULL,
	status ENUM('Pendente','Concluído') DEFAULT 'Concluído' NOT NULL,
	dataSolicitacao TIMESTAMP NOT NULL,
	dataConclusao TIMESTAMP NULL
) ENGINE=InnoDB ROW_FORMAT=COMPRESSED;

CREATE TABLE usuario (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(200) NOT NULL,
	email VARCHAR(200) NOT NULL UNIQUE,
	senha VARCHAR(255) NOT NULL
);


-- TABELAS DE CHAT
CREATE TABLE conversa (
	id INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(200) NOT NULL,
	criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE conversa_membros (
	id INT PRIMARY KEY AUTO_INCREMENT,
	id_conversa INT NOT NULL,
	id_usuario INT NOT NULL,
	entrada TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	FOREIGN KEY (id_conversa) REFERENCES conversa(id),
	FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);



-- PROCEDURE DE ARQUIVAMENTO
DELIMITER //
CREATE PROCEDURE arquivar_requerimentos()
BEGIN
    -- Mover concluidos com mais de 30 dias para arquivados (tblarquivado)
    INSERT INTO tblarquivado 
    (id, nomeMotorista, placaCavalo, placaCarretas, destino, valorOperacao, tipoOperacao, observacaoSolicitacao, observacaoExtra, arquivoPDF, status, dataSolicitacao, dataConclusao)
    SELECT id, nomeMotorista, placaCavalo, placaCarretas, destino, valorOperacao, tipoOperacao, observacaoSolicitacao, observacaoExtra, arquivoPDF, status, dataSolicitacao, dataConclusao
    FROM tblsolicitacao
    WHERE status = 'Concluído' 
    AND dataConclusao < DATE_SUB(NOW(), INTERVAL 30 DAY);
    
    -- Remover dos ativos
    DELETE FROM tblsolicitacao
    WHERE status = 'Concluído'
    AND dataConclusao < DATE_SUB(NOW(), INTERVAL 30 DAY);
END //

SET GLOBAL event_scheduler = ON;

-- AGENDAR EXECUÇÃO DIÁRIA
CREATE EVENT evt_arquivamento_automatico
ON SCHEDULE EVERY 1 DAY
STARTS TIMESTAMP(CURRENT_DATE + INTERVAL 1 DAY)
DO
BEGIN
    CALL arquivar_requerimentos();
END //
DELIMITER ;

-- Usuário base
INSERT INTO usuario (nome, email, senha) VALUES ("adm", "admin@empresa.com", "$2y$10$Akr4kI8jBU5t9x.ay4H41..OV83RF3g.EC9HfJmdR4mBYZA.39TqG"); -- TEMPORÁRIO!