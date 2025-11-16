-- Reinserir categorias com encoding correto
USE assados_delivery;

-- Desabilitar verificação de chave estrangeira temporariamente
SET FOREIGN_KEY_CHECKS = 0;

-- Deletar categorias existentes
DELETE FROM categorias;

-- Reinserir com textos corretos
INSERT INTO categorias (id, nome, descricao, ativo) VALUES
(1, 'Carnes Assadas', 'Carnes preparadas artesanalmente e assadas lentamente', 1),
(2, 'Acompanhamentos', 'Deliciosos acompanhamentos para completar sua refeição', 1),
(3, 'Combos', 'Combos completos que servem a família toda', 1),
(4, 'Bebidas', 'Bebidas para acompanhar seu almoço', 1),
(5, 'Conveniência', 'Itens extras para seu churrasco', 1);

-- Reabilitar verificação de chave estrangeira
SET FOREIGN_KEY_CHECKS = 1;

-- Verificar
SELECT id, nome, descricao FROM categorias ORDER BY id ASC;
