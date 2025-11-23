-- ============================================
-- ASSADOS DELIVERY - TABELAS APENAS
-- Para importar em hospedagem (sem CREATE DATABASE)
-- ============================================

-- ============================================
-- TABELA: categorias
-- Armazena as categorias dos produtos
-- ============================================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: produtos
-- Armazena todos os produtos do cardápio
-- ============================================
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT DEFAULT 0,
    unidade VARCHAR(50),
    categoria_id INT NOT NULL,
    imagem VARCHAR(255),
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    INDEX idx_categoria (categoria_id),
    INDEX idx_ativo (ativo),
    INDEX idx_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: usuarios_admin
-- Armazena os usuários administradores do painel
-- ============================================
CREATE TABLE usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: clientes
-- Armazena os dados dos clientes
-- ============================================
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    cpf VARCHAR(14) UNIQUE,
    endereco_rua VARCHAR(200),
    endereco_numero VARCHAR(10),
    endereco_complemento VARCHAR(100),
    endereco_bairro VARCHAR(100),
    endereco_cidade VARCHAR(100),
    endereco_estado VARCHAR(2),
    endereco_cep VARCHAR(9),
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_cpf (cpf),
    CONSTRAINT chk_contato CHECK (email IS NOT NULL OR telefone IS NOT NULL)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: pedidos
-- Armazena os pedidos realizados
-- ============================================
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_entrega DATE NOT NULL,
    horario_entrega VARCHAR(20),
    status ENUM('pendente', 'confirmado', 'em_preparo', 'saiu_entrega', 'entregue', 'cancelado') DEFAULT 'pendente',
    subtotal DECIMAL(10,2) NOT NULL,
    taxa_entrega DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    forma_pagamento VARCHAR(50) DEFAULT 'dinheiro',
    observacoes TEXT,
    endereco_entrega TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_data_entrega (data_entrega)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: pedidos_itens
-- Armazena os itens de cada pedido
-- ============================================
CREATE TABLE pedidos_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    produto_nome VARCHAR(150) NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE RESTRICT,
    INDEX idx_pedido (pedido_id),
    INDEX idx_produto (produto_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABELA: auditoria_precos
-- Registra todas as alterações de preços (TRIGGER)
-- ============================================
CREATE TABLE auditoria_precos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    produto_nome VARCHAR(150),
    preco_anterior DECIMAL(10,2),
    preco_novo DECIMAL(10,2),
    usuario VARCHAR(100),
    data_alteracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    INDEX idx_produto (produto_id),
    INDEX idx_data (data_alteracao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TRIGGER: Auditoria de Alteração de Preços
-- ============================================
DELIMITER $$

CREATE TRIGGER trg_auditoria_preco_update
AFTER UPDATE ON produtos
FOR EACH ROW
BEGIN
    IF OLD.preco != NEW.preco THEN
        INSERT INTO auditoria_precos (produto_id, produto_nome, preco_anterior, preco_novo, usuario)
        VALUES (NEW.id, NEW.nome, OLD.preco, NEW.preco, USER());
    END IF;
END$$

DELIMITER ;

-- ============================================
-- PROCEDURE: Inserção Massiva de Produtos
-- ============================================
DELIMITER $$

CREATE PROCEDURE sp_inserir_produtos_massivo(
    IN p_categoria_id INT,
    IN p_produtos_json JSON
)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE total INT;
    
    SET total = JSON_LENGTH(p_produtos_json);
    
    WHILE i < total DO
        INSERT INTO produtos (
            codigo, 
            nome, 
            descricao, 
            preco, 
            estoque, 
            unidade, 
            categoria_id
        )
        VALUES (
            JSON_UNQUOTE(JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].codigo'))),
            JSON_UNQUOTE(JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].nome'))),
            JSON_UNQUOTE(JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].descricao'))),
            JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].preco')),
            JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].estoque')),
            JSON_UNQUOTE(JSON_EXTRACT(p_produtos_json, CONCAT('$[', i, '].unidade'))),
            p_categoria_id
        );
        
        SET i = i + 1;
    END WHILE;
    
    SELECT CONCAT(total, ' produtos inseridos com sucesso!') AS resultado;
END$$

DELIMITER ;

-- ============================================
-- FUNCTION: Verificar Disponibilidade de Estoque
-- ============================================
DELIMITER $$

CREATE FUNCTION fn_verificar_estoque(
    p_produto_id INT,
    p_quantidade INT
) RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_estoque_atual INT;
    
    SELECT estoque INTO v_estoque_atual
    FROM produtos
    WHERE id = p_produto_id AND ativo = TRUE;
    
    IF v_estoque_atual IS NULL THEN
        RETURN FALSE;
    END IF;
    
    IF v_estoque_atual >= p_quantidade THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END$$

DELIMITER ;

-- ============================================
-- ÍNDICES ADICIONAIS
-- ============================================
CREATE INDEX idx_produto_categoria_ativo ON produtos(categoria_id, ativo);
CREATE INDEX idx_pedido_data_status ON pedidos(data_entrega, status);
CREATE INDEX idx_cliente_ativo ON clientes(ativo, email);
CREATE FULLTEXT INDEX idx_produto_busca ON produtos(nome, descricao);
