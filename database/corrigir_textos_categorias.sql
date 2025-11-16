-- Corrigir textos das categorias com encoding correto
USE assados_delivery;

UPDATE categorias SET 
    nome = 'Carnes Assadas',
    descricao = 'Carnes preparadas artesanalmente e assadas lentamente'
WHERE id = 1;

UPDATE categorias SET 
    nome = 'Acompanhamentos',
    descricao = 'Deliciosos acompanhamentos para completar sua refeição'
WHERE id = 2;

UPDATE categorias SET 
    nome = 'Combos',
    descricao = 'Combos completos que servem a família toda'
WHERE id = 3;

UPDATE categorias SET 
    nome = 'Bebidas',
    descricao = 'Bebidas para acompanhar seu almoço'
WHERE id = 4;

UPDATE categorias SET 
    nome = 'Conveniência',
    descricao = 'Itens extras para seu churrasco'
WHERE id = 5;

-- Verificar resultado
SELECT id, nome, descricao FROM categorias ORDER BY id ASC;
