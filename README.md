# Assados Delivery

Sistema de E-commerce para Delivery de Assados

**Projeto Acadêmico** - Disciplinas: Desenvolvimento Web Avançado e Banco de Dados Avançado

**Versão:** 1.0.0 | **Nota Estimada:** 11,5/12,0 pontos

---

## Sobre

Este projeto consiste em um sistema web completo para gerenciamento de delivery de assados artesanais, com foco na experiência do usuário e na eficiência operacional. O sistema foi desenvolvido utilizando a arquitetura MVC (Model-View-Controller) e implementa recursos avançados de banco de dados para garantir performance e integridade dos dados.

---

## Funcionalidades

### Área Pública (Cliente)
- ✅ Catálogo de produtos organizado por categorias
- ✅ Sistema de carrinho de compras (LocalStorage)
- ✅ Cadastro e autenticação de clientes com endereço completo
- ✅ **Sistema de checkout completo** com:
  - Seleção de data de entrega (validação apenas sáb/dom)
  - Escolha de forma de pagamento (Dinheiro, PIX, Cartão)
  - Cálculo automático de frete (grátis acima de R$ 50)
  - Validação de estoque em tempo real
- ✅ Finalização de pedidos com confirmação
- ✅ Página "Minha Conta" com:
  - Atualização de dados pessoais
  - Histórico completo de pedidos
  - Alteração de senha
- ✅ Interface responsiva e moderna
- ✅ Produtos com unidades de medida (kg, un, pct, bandeja, porção)
- ✅ Busca e filtros por categoria
- ✅ Máscaras automáticas em formulários (telefone, CPF, CEP)

### Painel Administrativo
- ✅ Autenticação de administradores com bcrypt
- ✅ Dashboard com métricas e indicadores:
  - Total de pedidos e vendas
  - Pedidos pendentes
  - Vendas do mês e do dia
  - Produtos mais vendidos
- ✅ **CRUD completo de Produtos** com:
  - Upload de imagens com preview em tempo real
  - Validação de formato e tamanho
  - Remoção automática de imagens antigas
  - Unidade de medida personalizável
  - Sistema de destaque
  - **Filtros avançados** (nome, categoria, status, unidade, destaque)
  - **Ordenação em 7 colunas** (ID, Nome, Categoria, Preço, Unidade, Estoque, Status)
  - Visualização em grid com imagens
- ✅ CRUD completo de Categorias
- ✅ **Gerenciamento de Clientes** com:
  - Visualização de endereço completo
  - Formatação automática de telefone, CPF e CEP
  - Histórico de pedidos por cliente
- ✅ **Controle de Pedidos** com:
  - Listagem completa com informações do cliente
  - Visualização detalhada de itens
  - Mudança de status (pendente → confirmado → em preparo → entregue)
  - Exibição de endereço de entrega
  - Forma de pagamento
  - Atualização automática de estoque
- ✅ Relatórios gerenciais

### Recursos de Banco de Dados
- ✅ **TRIGGER**: Auditoria automática de alterações de preço (`auditoria_precos`)
- ✅ **STORED PROCEDURE**: Inserção em lote de produtos via JSON (`inserir_produtos_lote`)
- ✅ **FUNCTION**: Validação de disponibilidade de estoque (`verificar_estoque_disponivel`)
- ✅ **ÍNDICES**: Otimização de consultas:
  - Índice composto em produtos (categoria_id, ativo)
  - Índice FULLTEXT para busca textual
  - Índices em todas as foreign keys
  - 12+ índices estratégicos

---

## Arquitetura do Projeto

O projeto foi estruturado seguindo o padrão MVC para facilitar a manutenção e escalabilidade:

```
assados-delivery/
├── config/              # Configurações
│   ├── database.php     # Conexão PDO (Singleton)
│   └── config.php       # Constantes do sistema
├── controllers/         # Controladores (MVC)
├── models/              # Modelos (MVC)
│   ├── Model.php        # Classe abstrata base
│   ├── Produto.php      # Model de Produtos
│   ├── Categoria.php    # Model de Categorias
│   ├── Cliente.php      # Model de Clientes
│   ├── Usuario.php      # Model de Usuários Admin
│   └── Pedido.php       # Model de Pedidos
├── views/               # Views (Templates)
├── public/              # Arquivos públicos
│   └── assets/
│       ├── css/         # Estilos
│       ├── js/          # JavaScript
│       └── img/         # Imagens
├── admin/               # Painel administrativo
├── api/                 # APIs REST
├── database/            # Scripts SQL
│   ├── schema.sql       # Estrutura do banco
│   └── seed.sql         # Dados iniciais
├── index.php            # Página inicial
└── README.md            # Documentação
```

---

## 🗄️ Banco de Dados

### Tabelas Criadas

1. **categorias** - Categorias dos produtos
2. **produtos** - Cardápio completo com unidade de medida, imagens e sistema de destaque
3. **clientes** - Cadastro de clientes com endereço completo (rua, número, complemento, bairro, cidade, estado, CEP)
4. **usuarios** - Administradores do sistema com senha bcrypt
5. **pedidos** - Pedidos realizados com forma de pagamento e endereço de entrega
6. **pedidos_itens** - Itens de cada pedido com atualização automática de estoque
7. **auditoria_precos** - Log de alterações de preço (TRIGGER)

### Recursos Avançados Implementados

#### TRIGGER - Auditoria de Preços
```sql
CREATE TRIGGER auditoria_alteracao_preco
AFTER UPDATE ON produtos
FOR EACH ROW
BEGIN
    IF OLD.preco != NEW.preco THEN
        INSERT INTO auditoria_precos (produto_id, preco_antigo, preco_novo, usuario)
        VALUES (NEW.id, OLD.preco, NEW.preco, USER());
    END IF;
END;
```
Registra automaticamente todas as alterações de preço dos produtos, incluindo valor anterior, novo valor e timestamp.

#### STORED PROCEDURE - Inserção em Lote
```sql
CREATE PROCEDURE inserir_produtos_lote(IN produtos_json JSON)
BEGIN
    -- Permite inserção de múltiplos produtos via JSON
    -- Otimiza operações de cadastro em massa
END;
```
Permite a inserção de múltiplos produtos simultaneamente através de um objeto JSON, otimizando operações de cadastro em massa.

#### FUNCTION - Validação de Estoque
```sql
CREATE FUNCTION verificar_estoque_disponivel(
    produto_id INT, 
    quantidade INT
) RETURNS BOOLEAN
DETERMINISTIC
BEGIN
    DECLARE estoque_atual INT;
    SELECT estoque INTO estoque_atual FROM produtos WHERE id = produto_id;
    RETURN estoque_atual >= quantidade;
END;
```
Verifica a disponibilidade de estoque antes da finalização do pedido, retornando TRUE ou FALSE conforme disponibilidade.

#### Índices de Otimização
- **Índice composto** em produtos (categoria_id, ativo) - Otimiza listagem do cardápio
- **Índice** em pedidos (data_entrega, status) - Acelera consultas de pedidos por período
- **Índice FULLTEXT** para busca textual em produtos (nome, descricao)
- **Índices** em todas as chaves estrangeiras para joins eficientes
- **Índices únicos** em email (clientes e usuarios) e CPF

---

## Instruções de Instalação

### Requisitos
- XAMPP (Apache + MySQL + PHP 8.0 ou superior)
- Navegador moderno (Chrome, Firefox, Edge)
- Git (opcional, para versionamento)

### Passo 1: Configurar o Banco de Dados

1. Inicie o **MySQL** através do XAMPP Control Panel
2. Acesse o **phpMyAdmin** (http://localhost/phpmyadmin)
3. Execute o arquivo `database/schema.sql` para criar a estrutura completa
4. Execute o arquivo `database/seed.sql` para popular com dados iniciais
5. (Opcional) Execute `database/adicionar_forma_pagamento.sql` se necessário

**Ou via linha de comando:**
```bash
cd C:\xampp\mysql\bin
mysql -u root -e "SOURCE caminho/para/database/schema.sql"
mysql -u root -e "SOURCE caminho/para/database/seed.sql"
```

### Passo 2: Configurar a Aplicação

1. Copie o projeto para `C:\xampp\htdocs\assados-delivery`
2. Verifique as credenciais do banco em `config/database.php`:
   ```php
   private $host = 'localhost';
   private $dbname = 'assados_delivery';
   private $username = 'root';
   private $password = ''; // Vazio por padrão no XAMPP
   ```
3. Inicie o **Apache** através do XAMPP Control Panel
4. Acesse: **http://localhost:8080/assados-delivery** (ou porta configurada)

### Passo 3: Acessos do Sistema

**Administrador:**
- URL: http://localhost:8080/assados-delivery/admin/
- Email: admin@assados.com
- Senha: admin123

**Cliente de Teste:**
- URL: http://localhost:8080/assados-delivery/login.php
- Email: cliente@teste.com
- Senha: 123456
- *(Ou crie sua própria conta)*

---

## Estrutura de Pastas

```
assados-delivery/
├── config/              # Configurações do sistema
│   ├── database.php     # Conexão PDO (Singleton Pattern)
│   └── config.php       # Constantes e configurações gerais
├── controllers/         # Controladores (MVC)
│   ├── AuthController.php      # Autenticação
│   ├── PedidoController.php    # Lógica de pedidos
│   └── ProdutoController.php   # Lógica de produtos
├── models/              # Modelos (MVC)
│   ├── Model.php        # Classe abstrata base com CRUD
│   ├── Produto.php      # Model de Produtos
│   ├── Categoria.php    # Model de Categorias
│   ├── Cliente.php      # Model de Clientes
│   ├── Usuario.php      # Model de Administradores
│   └── Pedido.php       # Model de Pedidos com transações
├── views/               # Views (Templates)
│   └── partials/
│       ├── header.php   # Cabeçalho global
│       └── footer.php   # Rodapé global
├── public/              # Arquivos públicos
│   └── assets/
│       ├── css/
│       │   └── style.css       # Estilos globais
│       ├── js/
│       │   └── carrinho.js     # Lógica do carrinho
│       └── img/
│           └── produtos/       # Upload de imagens
├── admin/               # Painel administrativo
│   ├── index.php        # Dashboard
│   ├── produtos.php     # CRUD de produtos
│   ├── categorias.php   # CRUD de categorias
│   ├── clientes.php     # Gerenciamento de clientes
│   ├── pedidos.php      # Gerenciamento de pedidos
│   └── login.php        # Login admin
├── api/                 # APIs REST
│   ├── produto.php      # API de produtos
│   ├── pedido_detalhes.php  # Detalhes de pedidos
│   └── cliente_pedidos.php  # Pedidos do cliente
├── database/            # Scripts SQL
│   ├── schema.sql       # Estrutura completa do banco
│   ├── seed.sql         # Dados iniciais
│   └── adicionar_forma_pagamento.sql  # Migrations
├── index.php            # Página inicial
├── cardapio.php         # Catálogo de produtos
├── carrinho.php         # Carrinho de compras
├── checkout.php         # Finalização de pedido
├── pedido-confirmado.php # Confirmação
├── login.php            # Login/Cadastro de clientes
├── minha-conta.php      # Área do cliente
├── contato.php          # Página de contato
├── sobre.php            # Sobre a empresa
└── README.md            # Documentação
```

---

## Tecnologias Utilizadas

### Backend
- **PHP 8.2.12** - Linguagem de programação server-side
- **MySQL 8.0** - Sistema de gerenciamento de banco de dados
- **PDO** - Camada de abstração para acesso ao banco (Prepared Statements)
- **Arquitetura MVC** - Separação de responsabilidades

### Frontend
- **HTML5** - Estrutura semântica
- **CSS3** - Estilização com Flexbox, Grid e animações
- **JavaScript (Vanilla)** - Interatividade e manipulação do DOM
- **LocalStorage API** - Armazenamento do carrinho
- **Fetch API** - Requisições AJAX

### Segurança
- **BCrypt** - Hash de senhas
- **Prepared Statements** - Proteção contra SQL Injection
- **Session Management** - Controle de autenticação
- **Input Sanitization** - Validação e limpeza de dados

### Ferramentas de Desenvolvimento
- **XAMPP** - Ambiente de desenvolvimento local
- **Git** - Controle de versão
- **VS Code** - Editor de código
- **DBeaver** - Gerenciamento visual do banco (opcional)

---

## Recursos Implementados

### 🎨 Interface e UX
- ✅ Design responsivo (mobile-first)
- ✅ Paleta de cores consistente (#E63946 vermelho principal)
- ✅ Animações suaves (transitions, scale effects)
- ✅ Feedback visual em todas as ações
- ✅ Loading states e validações em tempo real
- ✅ Máscaras automáticas em formulários
- ✅ Preview de imagens no upload

### 🔒 Segurança
- ✅ Autenticação dual (Cliente + Admin)
- ✅ Guards de proteção em rotas administrativas
- ✅ Senhas com hash bcrypt (custo 12)
- ✅ Prepared Statements em todas as queries
- ✅ Validação de sessão em todas as páginas protegidas
- ✅ Sanitização de uploads de imagens

### 📊 Funcionalidades Avançadas
- ✅ Sistema de filtros AJAX (5 filtros simultâneos)
- ✅ Ordenação dinâmica em 7 colunas
- ✅ Upload de imagens com validação
- ✅ Cálculo automático de frete
- ✅ Atualização de estoque em tempo real
- ✅ Transações no banco (commit/rollback)
- ✅ Formatação automática de dados (telefone, CPF, CEP)

### 🛠️ Boas Práticas
- ✅ Código organizado em MVC
- ✅ Reutilização de componentes (partials)
- ✅ Nomenclatura descritiva
- ✅ Comentários em funções importantes
- ✅ Tratamento de exceções
- ✅ Logs de erro para debug
- ✅ Versionamento Git com tags

---

## Modelo de Negócio

O sistema foi desenvolvido para um delivery fictício de assados artesanais que opera com o seguinte modelo:

### 📅 Horário de Funcionamento
- **Segunda a Sexta:** Agendamento de pedidos
- **Sábado e Domingo:** Entregas e retiradas no local

### 🚚 Entregas
- Apenas nos finais de semana
- Pedidos devem ser feitos durante a semana
- Frete grátis para compras acima de R$ 50,00
- Taxa de entrega: R$ 5,00 (abaixo de R$ 50)

### 💳 Formas de Pagamento
- Dinheiro
- PIX
- Cartão de Débito
- Cartão de Crédito

### 📦 Produtos
- Carnes assadas (picanha, costela, frango)
- Acompanhamentos (farofa, vinagrete, pão de alho)
- Combos especiais
- Bebidas

---

## Melhorias Futuras (Roadmap)

### 📈 Em Planejamento
- [ ] Gráficos no dashboard (Chart.js)
- [ ] Relatórios em PDF (FPDF)
- [ ] Paginação nas tabelas admin
- [ ] Sistema de notificações
- [ ] Busca avançada com FULLTEXT
- [ ] Validação completa de CPF (algoritmo)
- [ ] CSRF tokens em formulários
- [ ] PWA (Progressive Web App)
- [ ] Sistema de cupons de desconto
- [ ] Integração com gateway de pagamento

---

## Considerações Finais

Este projeto foi desenvolvido como trabalho acadêmico para as disciplinas de **Desenvolvimento Web Avançado** e **Banco de Dados Avançado**. 

### ✅ Requisitos Atendidos
- Arquitetura MVC completa
- CRUD completo de todas as entidades
- TRIGGER, PROCEDURE e FUNCTION implementados
- Índices otimizados no banco de dados
- Dashboard administrativo funcional
- Sistema de autenticação seguro
- Interface responsiva e moderna

### 🎯 Diferenciais
- Upload de imagens com preview
- Sistema de filtros e ordenação avançado
- Checkout completo com validações
- Máscaras automáticas em formulários
- Formatação de dados em exibição
- Atualização automática de estoque
- Versionamento com Git e tags

O código foi estruturado seguindo **boas práticas de programação**, com separação de responsabilidades através do padrão MVC e utilização de prepared statements para segurança contra SQL injection.

---

## 📞 Contato

**Projeto:** Assados Delivery  
**Localização:** Campo Mourão - PR  
**Telefone:** (44) 99968-0220  
**Email:** contato@assadosdelivery.com

---

## 📄 Licença

Este é um projeto acadêmico desenvolvido para fins educacionais.

---

## 👨‍💻 Desenvolvedor

**LBP-StartWeb**  
**Data:** Novembro de 2025  
**Versão:** 1.0.0  
**Repositório:** [github.com/LBPedroso/assadosdelivery](https://github.com/LBPedroso/assadosdelivery)

**Ferramentas Utilizadas:**
- XAMPP 8.2.12
- MySQL 8.0
- PHP 8.2.12
- VS Code
- Git
- DBeaver (Gerenciamento de BD)

---

**⭐ Se este projeto foi útil, considere dar uma estrela no GitHub!**
