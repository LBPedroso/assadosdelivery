-- ========================================
-- SCRIPT DE CORREÇÃO - Banco de Dados Local
-- Adiciona recursos avançados faltantes
-- ========================================

-- 1. CRIAR ÍNDICES DE OTIMIZAÇÃO (SE NÃO EXISTIREM)
-- ========================================

-- Verificar e criar índice composto categoria_id + ativo
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
               WHERE table_schema = 'assados_delivery' 
               AND table_name = 'produtos' 
               AND index_name = 'idx_categoria_ativo');

SET @sqlstmt := IF(@exist = 0, 
    'CREATE INDEX idx_categoria_ativo ON produtos(categoria_id, ativo)', 
    'SELECT "Índice idx_categoria_ativo já existe" as info');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ========================================
-- 2. CRIAR STORED PROCEDURE
-- ========================================

DELIMITER $$

DROP PROCEDURE IF EXISTS inserir_produtos_lote$$

CREATE PROCEDURE inserir_produtos_lote(IN produtos_json JSON)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE total INT;
    DECLARE produto JSON;
    
    -- Contar quantos produtos no JSON
    SET total = JSON_LENGTH(produtos_json);
    
    -- Loop pelos produtos
    WHILE i < total DO
        SET produto = JSON_EXTRACT(produtos_json, CONCAT('$[', i, ']'));
        
        INSERT INTO produtos (
            nome,
            categoria_id,
            preco,
            estoque,
            unidade,
            descricao,
            ativo,
            destaque
        ) VALUES (
            JSON_UNQUOTE(JSON_EXTRACT(produto, '$.nome')),
            JSON_EXTRACT(produto, '$.categoria_id'),
            JSON_EXTRACT(produto, '$.preco'),
            COALESCE(JSON_EXTRACT(produto, '$.estoque'), 0),
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(produto, '$.unidade')), 'un'),
            COALESCE(JSON_UNQUOTE(JSON_EXTRACT(produto, '$.descricao')), ''),
            COALESCE(JSON_EXTRACT(produto, '$.ativo'), 1),
            COALESCE(JSON_EXTRACT(produto, '$.destaque'), 0)
        );
        
        SET i = i + 1;
    END WHILE;
    
    SELECT CONCAT('✅ ', total, ' produtos inseridos com sucesso!') as resultado;
END$$

DELIMITER ;

-- ========================================
-- 3. CRIAR FUNCTION DE VERIFICAÇÃO DE ESTOQUE
-- ========================================

DELIMITER $$

DROP FUNCTION IF EXISTS verificar_estoque_disponivel$$

CREATE FUNCTION verificar_estoque_disponivel(
    produto_id INT,
    quantidade_desejada INT
) 
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE estoque_atual INT;
    
    -- Buscar estoque atual do produto
    SELECT estoque INTO estoque_atual
    FROM produtos
    WHERE id = produto_id;
    
    -- Retornar TRUE se há estoque suficiente, FALSE caso contrário
    IF estoque_atual >= quantidade_desejada THEN
        RETURN TRUE;
    ELSE
        RETURN FALSE;
    END IF;
END$$

DELIMITER ;

-- ========================================
-- 4. VERIFICAÇÕES FINAIS
-- ========================================

-- Mostrar índices criados
SHOW INDEX FROM produtos;

-- Mostrar procedures
SHOW PROCEDURE STATUS WHERE Db = 'assados_delivery';

-- Mostrar functions
SHOW FUNCTION STATUS WHERE Db = 'assados_delivery';

-- Testar FUNCTION
SELECT verificar_estoque_disponivel(1, 5) as teste_com_estoque;
SELECT verificar_estoque_disponivel(1, 999999) as teste_sem_estoque;

SELECT '✅ Recursos avançados criados com sucesso!' as status;
