-- ============================================
-- DADOS INICIAIS - INFINITYFREE
-- (SEM USE DATABASE)
-- ============================================

-- ============================================
-- INSERIR CATEGORIAS
-- ============================================
INSERT INTO categorias (nome, descricao) VALUES
('Carnes Assadas', 'Carnes preparadas artesanalmente e assadas lentamente'),
('Acompanhamentos', 'Deliciosos acompanhamentos para completar sua refeição'),
('Combos', 'Combos completos que servem a família toda'),
('Bebidas', 'Bebidas para acompanhar seu almoço'),
('Conveniência', 'Itens extras para seu churrasco');

-- ============================================
-- INSERIR PRODUTOS - CARNES ASSADAS
-- ============================================
INSERT INTO produtos (codigo, nome, descricao, preco, estoque, unidade, categoria_id) VALUES
('C001', 'Costela Assada', 'Costela bovina assada lentamente, suculenta e macia', 89.90, 50, 'Porção (1kg)', 1),
('C002', 'Frango Assado', 'Frango inteiro assado com tempero da casa', 45.90, 100, 'Unidade', 1),
('C003', 'Linguiça Artesanal', 'Linguiça suína ou de frango, assada na brasa', 32.90, 80, '500g', 1),
('C004', 'Pernil Assado', 'Pernil suíno assado com ervas e alho', 79.90, 40, 'Porção (1kg)', 1),
('C005', 'Peixe Assado', 'Peixe inteiro assado com limão e ervas', 55.90, 30, 'Unidade', 1);

-- ============================================
-- INSERIR PRODUTOS - ACOMPANHAMENTOS
-- ============================================
INSERT INTO produtos (codigo, nome, descricao, preco, estoque, unidade, categoria_id) VALUES
('A001', 'Arroz Branco', 'Arroz soltinho, ideal para acompanhar carnes', 12.90, 150, 'Porção (500g)', 2),
('A002', 'Maionese Caseira', 'Batata com maionese temperada e legumes', 18.90, 120, 'Porção (500g)', 2),
('A003', 'Farofa Crocante', 'Farofa com bacon e cebola', 15.90, 100, 'Porção (300g)', 2),
('A004', 'Vinagrete', 'Tomate, cebola e pimentão no tempero especial', 10.90, 130, 'Porção (300g)', 2);

-- ============================================
-- INSERIR PRODUTOS - COMBOS
-- ============================================
INSERT INTO produtos (codigo, nome, descricao, preco, estoque, unidade, categoria_id) VALUES
('CB01', 'Combo Família', '1kg Costela + Arroz + Maionese + Farofa + Vinagrete', 139.90, 40, 'Serve 4 pessoas', 3),
('CB02', 'Combo Frango', '1 Frango Assado + Arroz + Maionese', 69.90, 60, 'Serve 3 pessoas', 3),
('CB03', 'Combo Misto', 'Pernil + Linguiça + Arroz + Maionese + Farofa', 149.90, 35, 'Serve 4 pessoas', 3),
('CB04', 'Combo do Mar', 'Peixe Assado + Arroz + Vinagrete + Farofa', 89.90, 25, 'Serve 2 pessoas', 3);

-- ============================================
-- INSERIR PRODUTOS - BEBIDAS
-- ============================================
INSERT INTO produtos (codigo, nome, descricao, preco, estoque, unidade, categoria_id) VALUES
('B001', 'Cerveja Lata', 'Lata 350ml (Skol, Brahma, Itaipava)', 4.50, 300, 'Unidade', 4),
('B002', 'Refrigerante 2L', 'Coca-Cola, Guaraná, Fanta', 9.90, 150, 'Unidade', 4),
('B003', 'Suco Natural', 'Suco de laranja, uva ou maracujá', 12.90, 80, '500ml', 4);

-- ============================================
-- INSERIR PRODUTOS - CONVENIÊNCIA
-- ============================================
INSERT INTO produtos (codigo, nome, descricao, preco, estoque, unidade, categoria_id) VALUES
('B004', 'Gelo', 'Pacote de gelo', 8.00, 100, '2kg', 5),
('B005', 'Carvão', 'Pacote de carvão', 25.00, 50, '5kg', 5);

-- ============================================
-- CRIAR USUÁRIO ADMINISTRADOR PADRÃO
-- Usuário: admin@assados.com
-- Senha: admin123 (hash bcrypt)
-- ============================================
INSERT INTO usuarios_admin (nome, email, senha) VALUES
('Administrador', 'admin@assados.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- CLIENTE DE TESTE
-- Email: cliente@teste.com
-- Senha: 123456
-- ============================================
INSERT INTO clientes (nome, email, senha, telefone, cpf, endereco_rua, endereco_numero, endereco_bairro, endereco_cidade, endereco_estado, endereco_cep) VALUES
('João Silva', 'cliente@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 98765-4321', '123.456.789-00', 'Rua das Flores', '123', 'Centro', 'São Paulo', 'SP', '01234-567');
