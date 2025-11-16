# Assados Delivery

Sistema de E-commerce para Delivery de Assados

**Projeto Acadêmico** - Disciplinas: Desenvolvimento Web Avançado e Banco de Dados Avançado

---

## Sobre

Este projeto consiste em um sistema web completo para gerenciamento de delivery de assados artesanais, com foco na experiência do usuário e na eficiência operacional. O sistema foi desenvolvido utilizando a arquitetura MVC (Model-View-Controller) e implementa recursos avançados de banco de dados para garantir performance e integridade dos dados

---

## Funcionalidades

### Área Pública (Cliente)
- Catálogo de produtos organizado por categorias
- Sistema de carrinho de compras
- Cadastro e autenticação de clientes
- Realização de pedidos
- Histórico de compras
- Interface responsiva

### Painel Administrativo
- Autenticação de administradores
- Dashboard com métricas e indicadores
- CRUD completo de Produtos
- CRUD completo de Categorias
- Gerenciamento de Clientes
- Controle de Pedidos
- Relatórios gerenciais

### Recursos de Banco de Dados
- **TRIGGER**: Auditoria automática de alterações de preço
- **STORED PROCEDURE**: Inserção em lote de produtos
- **FUNCTION**: Validação de disponibilidade de estoque
- **ÍNDICES**: Otimização de consultas em tabelas principais

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
2. **produtos** - Cardápio completo
3. **clientes** - Cadastro de clientes
4. **usuarios_admin** - Administradores do sistema
5. **pedidos** - Pedidos realizados
6. **pedidos_itens** - Itens de cada pedido
7. **auditoria_precos** - Log de alterações de preço (TRIGGER)

### Recursos Avançados

### Recursos Avançados Implementados

#### TRIGGER - Auditoria de Preços
Registra automaticamente todas as alterações de preço dos produtos, incluindo valor anterior, novo valor e usuário responsável.

#### STORED PROCEDURE - Inserção em Lote
Permite a inserção de múltiplos produtos simultaneamente através de um objeto JSON, otimizando operações de cadastro em massa.

#### FUNCTION - Validação de Estoque
Verifica a disponibilidade de estoque antes da finalização do pedido, retornando TRUE ou FALSE conforme disponibilidade.

#### Índices de Otimização
- Índice composto em produtos (categoria_id, ativo)
- Índice em pedidos (data_entrega, status)
- Índice full-text para busca textual em produtos
- Índices em todas as chaves estrangeiras

---

## Instruções de Instalação

### Requisitos
- XAMPP ou WAMP (Apache + MySQL + PHP 7.4 ou superior)
- DBeaver (opcional, para gerenciar banco)
- Navegador moderno

### Passo 1: Configurar o Banco de Dados

1. Abra o **DBeaver** ou **phpMyAdmin**
2. Execute o arquivo `database/schema.sql` para criar o banco e tabelas
- DBeaver (opcional, para gerenciamento visual do banco)
- Navegador web moderno

### Configuração do Banco de Dados

1. Inicie o MySQL através do XAMPP
2. Acesse o phpMyAdmin ou DBeaver
3. Execute o script `database/schema.sql` para criar a estrutura
4. Execute o script `database/seed.sql` para popular com dados iniciais

### Configuração da Aplicação

1. Copie o projeto para a pasta `htdocs` do XAMPP
2. Verifique as credenciais do banco em `config/database.php`
3. Inicie o Apache através do XAMPP Control Panel
4. Acesse através do navegador

### Acessos Padrão

**Cliente de Teste:**
- Email: cliente@teste.com
- Senha: 123456

**Administrador:**
- Email: admin@assados.com
- Senha: admin123

---

## Tecnologias Utilizadas

- PHP 7.4+ (back-end)
- MySQL 8.0 (banco de dados)
- HTML5, CSS3, JavaScript (front-end)
- PDO (camada de abstração de banco)
- Arquitetura MVC

---

## Modelo de Negócio

O sistema foi desenvolvido para um delivery fictício de assados artesanais que opera exclusivamente aos finais de semana. O modelo contempla:

- Catálogo com carnes, acompanhamentos, combos e bebidas
- Agendamento de pedidos para sábados e domingos
- Horário de funcionamento: 10h às 15h
- Sistema de gestão completo para o administrador

---

## Considerações Finais

Este projeto foi desenvolvido como trabalho acadêmico para as disciplinas de Desenvolvimento Web Avançado e Banco de Dados Avançado. Todos os requisitos da rubrica de avaliação foram contemplados, incluindo a implementação de recursos avançados de banco de dados (triggers, procedures e functions) e a construção de um painel administrativo com dashboard de indicadores.

O código foi estruturado seguindo boas práticas de programação, com separação de responsabilidades através do padrão MVC e utilização de prepared statements para segurança contra SQL injection.

---

## Contato

📞 (44) 99968-0220  
📧 contato@assadosdelivery.com  
📍 Campo Mourão-PR

---

**Desenvolvido por:** LBP-StartWeb  
**Data:** Novembro de 2025  
**Ferramentas:** XAMPP, DBeaver, VS Code, Git
