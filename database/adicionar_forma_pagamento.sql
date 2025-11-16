-- Adicionar coluna forma_pagamento na tabela pedidos
USE assados_delivery;

ALTER TABLE pedidos 
ADD COLUMN forma_pagamento VARCHAR(50) DEFAULT 'dinheiro' AFTER total;
