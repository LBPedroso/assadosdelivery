-- Reorganizar categorias na ordem correta
-- Execute este arquivo no DBeaver ou via MySQL

USE assados_delivery;

-- Atualizar IDs temporariamente para evitar conflitos
UPDATE categorias SET id = 100 WHERE nome = 'Carnes Assadas';
UPDATE categorias SET id = 101 WHERE nome = 'Acompanhamentos';
UPDATE categorias SET id = 102 WHERE nome = 'Combos';
UPDATE categorias SET id = 103 WHERE nome = 'Bebidas';
UPDATE categorias SET id = 104 WHERE nome = 'Conveniência';

-- Atualizar para IDs corretos na ordem desejada
UPDATE categorias SET id = 1 WHERE id = 100; -- Carnes Assadas
UPDATE categorias SET id = 2 WHERE id = 101; -- Acompanhamentos
UPDATE categorias SET id = 3 WHERE id = 102; -- Combos
UPDATE categorias SET id = 4 WHERE id = 103; -- Bebidas
UPDATE categorias SET id = 5 WHERE id = 104; -- Conveniência

-- Verificar resultado
SELECT id, nome FROM categorias ORDER BY id ASC;
