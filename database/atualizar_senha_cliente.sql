-- Atualizar senha do cliente João Silva para 123456
UPDATE clientes 
SET senha = '$2y$12$/GM4lrLe2n3d4J1Z58FhOedBiUK.ngMH8A6b6Drfk2J0YwIMXFreW'
WHERE email = 'cliente@teste.com';
