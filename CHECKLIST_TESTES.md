# ✅ Checklist de Testes - Assados Delivery

## 🌐 Ambiente de Teste
**URL Base:** http://localhost:8080/assados-delivery/

---

## 1️⃣ ÁREA PÚBLICA (Cliente)

### 1.1 Página Inicial (`index.php`)
- [ ] Carrega corretamente
- [ ] Menu de navegação funciona
- [ ] Links do header funcionam (Início, Cardápio, Sobre, Contato)
- [ ] Botão "Ver Cardápio" redireciona
- [ ] Rodapé com informações corretas
- [ ] Design responsivo (redimensionar navegador)

### 1.2 Cardápio (`cardapio.php`)
- [ ] Lista todos os produtos ativos
- [ ] Produtos organizados por categoria
- [ ] Imagens dos produtos carregam
- [ ] Preços formatados corretamente (R$)
- [ ] Unidades de medida aparecem (kg, un, pct, etc)
- [ ] Botão "Adicionar ao Carrinho" funciona
- [ ] Produtos inativos NÃO aparecem
- [ ] Filtro por categoria funciona
- [ ] Busca por nome funciona

### 1.3 Carrinho (`carrinho.php`)
- [ ] Mostra produtos adicionados
- [ ] Quantidade pode ser alterada (+/-)
- [ ] Subtotal calcula corretamente
- [ ] Botão remover item funciona
- [ ] Total atualiza automaticamente
- [ ] Botão "Finalizar Pedido" funciona
- [ ] Carrinho vazio mostra mensagem
- [ ] Dados persistem ao recarregar página (LocalStorage)

### 1.4 Login/Cadastro (`login.php`)
#### Cadastro Novo Cliente
- [ ] Formulário de cadastro visível
- [ ] Máscara de telefone funciona: (XX) XXXXX-XXXX
- [ ] Máscara de CPF funciona: XXX.XXX.XXX-XX
- [ ] Máscara de CEP funciona: XXXXX-XXX
- [ ] Validação: Nome obrigatório
- [ ] Validação: Email OU Telefone obrigatório
- [ ] Validação: Senha obrigatória
- [ ] Validação: Endereço completo obrigatório
- [ ] Cadastro com sucesso redireciona
- [ ] Erro de email duplicado exibe mensagem

#### Login Cliente Existente
- [ ] Login com email funciona
- [ ] Login com telefone formatado funciona: (44) 99968-0220
- [ ] Senha incorreta exibe erro
- [ ] Usuário não cadastrado exibe erro
- [ ] Login com sucesso redireciona para cardápio

### 1.5 Checkout (`checkout.php`)
- [ ] Requer login (redireciona se não logado)
- [ ] Mostra resumo do carrinho
- [ ] Endereço do cliente pré-preenchido
- [ ] Pode editar endereço de entrega
- [ ] Seletor de data de entrega funciona
- [ ] Validação: Apenas Sábado e Domingo permitidos
- [ ] Forma de pagamento selecionável (Dinheiro, PIX, Cartão)
- [ ] Cálculo de frete correto:
  - [ ] Grátis se total >= R$ 50
  - [ ] R$ 5,00 se total < R$ 50
- [ ] Total final correto (subtotal + frete)
- [ ] Botão "Confirmar Pedido" funciona
- [ ] Valida estoque antes de confirmar

### 1.6 Pedido Confirmado (`pedido-confirmado.php`)
- [ ] Mostra número do pedido
- [ ] Exibe resumo do pedido
- [ ] Lista todos os itens
- [ ] Mostra data de entrega
- [ ] Mostra forma de pagamento
- [ ] Mostra total pago
- [ ] Botão "Voltar ao Cardápio" funciona

### 1.7 Minha Conta (`minha-conta.php`)
#### Aba Meus Dados
- [ ] Dados do cliente carregam corretamente
- [ ] Email exibe corretamente (ou "Não informado")
- [ ] Telefone formatado corretamente
- [ ] CPF formatado corretamente
- [ ] Pode editar nome
- [ ] Pode editar email
- [ ] Pode editar telefone
- [ ] Pode editar endereço
- [ ] Botão "Atualizar Dados" funciona
- [ ] Mensagem de sucesso aparece

#### Alterar Senha
- [ ] Campo nova senha funciona
- [ ] Campo confirmar senha funciona
- [ ] Validação: senhas devem ser iguais
- [ ] Senha alterada com sucesso

#### Aba Meus Pedidos
- [ ] Lista todos os pedidos do cliente
- [ ] Pedidos mais recentes primeiro
- [ ] Status do pedido correto (Pendente, Confirmado, etc)
- [ ] Data formatada corretamente
- [ ] Total formatado (R$)
- [ ] Botão "Ver Detalhes" funciona
- [ ] Modal com itens do pedido abre
- [ ] Endereço de entrega aparece

### 1.8 Contato (`contato.php`)
#### Visitante (Não Logado)
- [ ] Formulário carrega
- [ ] Campos: Nome, Email, Telefone, Assunto, Mensagem
- [ ] Validação: Nome obrigatório
- [ ] Validação: Email OU Telefone obrigatório
- [ ] Validação: Assunto obrigatório
- [ ] Validação: Mensagem obrigatória
- [ ] Máscara de telefone funciona
- [ ] Envio salva no banco (verificar em admin/mensagens.php)
- [ ] Mensagem de sucesso aparece

#### Cliente Logado
- [ ] Nome pré-preenchido
- [ ] Email pré-preenchido (se houver)
- [ ] Telefone pré-preenchido
- [ ] Mensagem vincula ao cliente_id automaticamente
- [ ] Envio funciona

### 1.9 Sobre (`sobre.php`)
- [ ] Página carrega
- [ ] Informações da empresa visíveis
- [ ] Layout correto

---

## 2️⃣ PAINEL ADMINISTRATIVO

### 2.1 Login Admin (`admin/login.php`)
- [ ] Formulário de login visível
- [ ] Login com credenciais corretas funciona:
  - Email: admin@assados.com
  - Senha: admin123
- [ ] Login com senha errada exibe erro
- [ ] Redireciona para dashboard após login
- [ ] Logout funciona

### 2.2 Dashboard (`admin/index.php`)
- [ ] Requer autenticação (redireciona se não logado)
- [ ] Sidebar de navegação visível
- [ ] Card "Total de Pedidos" mostra número correto
- [ ] Card "Vendas Totais" mostra valor correto (R$)
- [ ] Card "Pedidos Pendentes" conta correto
- [ ] Card "Vendas do Mês" calcula correto
- [ ] Card "Vendas Hoje" calcula correto
- [ ] Card "Novos Clientes Este Mês" conta correto
- [ ] Produtos mais vendidos listados
- [ ] Produtos em estoque baixo (< 10) aparecem

### 2.3 CRUD de Produtos (`admin/produtos.php`)
#### Listagem
- [ ] Produtos em grid com imagens
- [ ] Ordenação por ID funciona (ASC/DESC)
- [ ] Ordenação por Nome funciona
- [ ] Ordenação por Categoria funciona
- [ ] Ordenação por Preço funciona
- [ ] Ordenação por Unidade funciona
- [ ] Ordenação por Estoque funciona
- [ ] Ordenação por Status funciona
- [ ] Filtro por nome funciona
- [ ] Filtro por categoria funciona
- [ ] Filtro por status (ativo/inativo) funciona
- [ ] Filtro por unidade funciona
- [ ] Filtro por destaque funciona
- [ ] Múltiplos filtros combinados funcionam

#### Criar Produto
- [ ] Modal de criar abre
- [ ] Campos obrigatórios validados
- [ ] Upload de imagem funciona
- [ ] Preview da imagem aparece
- [ ] Validação: apenas JPG, PNG, GIF
- [ ] Validação: tamanho máximo 2MB
- [ ] Preço aceita decimais (vírgula ou ponto)
- [ ] Estoque aceita apenas números
- [ ] Destaque checkbox funciona
- [ ] Produto criado aparece na lista
- [ ] Imagem salva em `/public/assets/img/produtos/`

#### Editar Produto
- [ ] Botão "Editar" abre modal
- [ ] Dados pré-preenchidos corretamente
- [ ] Pode alterar todos os campos
- [ ] Pode trocar a imagem
- [ ] Preview da nova imagem funciona
- [ ] Imagem antiga removida ao trocar
- [ ] Alteração salva corretamente
- [ ] **TRIGGER**: Mudança de preço registra em `auditoria_precos`

#### Deletar Produto
- [ ] Botão "Deletar" funciona
- [ ] Confirmação antes de deletar
- [ ] Produto removido da lista
- [ ] Imagem removida do servidor

### 2.4 CRUD de Categorias (`admin/categorias.php`)
#### Listagem
- [ ] Todas as categorias listadas
- [ ] Contador de produtos por categoria correto

#### Criar Categoria
- [ ] Formulário de criar funciona
- [ ] Nome obrigatório
- [ ] Descrição opcional
- [ ] Categoria criada aparece

#### Editar Categoria
- [ ] Dados pré-preenchidos
- [ ] Alteração salva

#### Deletar Categoria
- [ ] Confirmação aparece
- [ ] Não permite deletar se tiver produtos vinculados
- [ ] Deleta se não tiver produtos

### 2.5 Gerenciamento de Clientes (`admin/clientes.php`)
- [ ] Lista todos os clientes
- [ ] Dados exibidos:
  - [ ] Nome completo
  - [ ] Email (ou "Não informado")
  - [ ] Telefone formatado
  - [ ] CPF formatado
  - [ ] Endereço completo
  - [ ] Data de cadastro
- [ ] Total de pedidos por cliente
- [ ] Total gasto (R$)
- [ ] Contador de mensagens
- [ ] Badge de mensagens não lidas (vermelho)
- [ ] Botão "Ver Pedidos" funciona
- [ ] Botão "💬 Mensagens" funciona
- [ ] Busca por nome/email/telefone funciona

### 2.6 Gerenciamento de Pedidos (`admin/pedidos.php`)
#### Listagem
- [ ] Todos os pedidos listados
- [ ] Pedidos mais recentes primeiro
- [ ] Status colorido (Pendente=amarelo, Confirmado=azul, etc)
- [ ] Cliente do pedido aparece
- [ ] Data de entrega formatada
- [ ] Total formatado (R$)
- [ ] Filtro por status funciona
- [ ] Filtro por data funciona

#### Detalhes do Pedido
- [ ] Botão "Ver Detalhes" abre modal
- [ ] Lista todos os itens
- [ ] Quantidade, preço unitário, subtotal corretos
- [ ] Endereço de entrega completo
- [ ] Forma de pagamento exibida
- [ ] Total do pedido correto

#### Alterar Status
- [ ] Dropdown de status funciona
- [ ] Mudança de status salva
- [ ] Status atualiza na listagem
- [ ] Estoque NÃO duplica descontagem

### 2.7 Sistema de Mensagens (`admin/mensagens.php`)
#### Listagem
- [ ] Todas as mensagens aparecem
- [ ] Card de estatísticas mostra:
  - [ ] Total de mensagens
  - [ ] Mensagens não lidas
- [ ] Filtro "📋 Todas" funciona
- [ ] Filtro "🔴 Não Lidas" funciona
- [ ] Filtro "✅ Lidas" funciona
- [ ] Contadores nos filtros corretos
- [ ] Mensagens de clientes mostram nome do cliente
- [ ] Mensagens anônimas mostram "👤 Visitante"
- [ ] Badge "NÃO LIDA" aparece nas não lidas

#### Ações nas Mensagens
- [ ] Botão "✓ Marcar como Lida" funciona
- [ ] Botão "↺ Marcar como Não Lida" funciona
- [ ] Estado muda imediatamente
- [ ] Contadores atualizam
- [ ] Filtro permanece ativo após ação
- [ ] Botão "📧 Responder por E-mail" abre cliente de email
- [ ] Botão "💬 WhatsApp" abre WhatsApp Web (se telefone existe)
- [ ] Botão "🗑️ Excluir" funciona
- [ ] Confirmação antes de excluir
- [ ] Mensagem removida da lista

#### Integração com Clientes
- [ ] Mensagem de cliente logado vincula ao `cliente_id`
- [ ] Contador em admin/clientes.php atualiza
- [ ] Botão "💬 Mensagens" em clientes.php mostra mensagens corretas

---

## 3️⃣ BANCO DE DADOS AVANÇADO

### 3.1 TRIGGER - Auditoria de Preços
**Teste:**
1. [ ] Acessar admin/produtos.php
2. [ ] Editar um produto e alterar o preço
3. [ ] Acessar phpMyAdmin
4. [ ] Verificar tabela `auditoria_precos`
5. [ ] Confirmar que registro foi criado com:
   - [ ] produto_id correto
   - [ ] preco_antigo correto
   - [ ] preco_novo correto
   - [ ] data_alteracao preenchida
   - [ ] usuario preenchido

### 3.2 STORED PROCEDURE - Inserção em Lote
**Teste via phpMyAdmin:**
```sql
CALL inserir_produtos_lote('[
    {
        "nome": "Produto Teste 1",
        "categoria_id": 1,
        "preco": 25.00,
        "estoque": 100,
        "unidade": "kg"
    },
    {
        "nome": "Produto Teste 2",
        "categoria_id": 2,
        "preco": 15.00,
        "estoque": 50,
        "unidade": "un"
    }
]');
```
- [ ] Procedure executa sem erro
- [ ] Produtos inseridos na tabela
- [ ] Dados corretos

### 3.3 FUNCTION - Verificação de Estoque
**Teste via phpMyAdmin:**
```sql
-- Verificar produto com estoque suficiente
SELECT verificar_estoque_disponivel(1, 5); -- Deve retornar 1 (TRUE)

-- Verificar produto com estoque insuficiente
SELECT verificar_estoque_disponivel(1, 999999); -- Deve retornar 0 (FALSE)
```
- [ ] Function retorna 1 quando há estoque
- [ ] Function retorna 0 quando não há estoque
- [ ] Checkout usa a function antes de confirmar pedido

### 3.4 ÍNDICES - Verificação
**No phpMyAdmin:**
1. [ ] Abrir tabela `produtos`
2. [ ] Aba "Estrutura" → Ver índices
3. [ ] Confirmar índices:
   - [ ] PRIMARY em `id`
   - [ ] INDEX em `categoria_id`
   - [ ] INDEX composto `idx_categoria_ativo` (categoria_id, ativo)
   - [ ] FULLTEXT em `nome, descricao`
4. [ ] Abrir tabela `clientes`
5. [ ] Confirmar:
   - [ ] UNIQUE em `email`
   - [ ] UNIQUE em `cpf`
6. [ ] Abrir tabela `pedidos`
7. [ ] Confirmar:
   - [ ] INDEX em `cliente_id`
   - [ ] INDEX em `data_entrega, status`

**Teste de Performance:**
```sql
-- Consulta SEM índice (criar produto_teste sem índice)
EXPLAIN SELECT * FROM produtos WHERE categoria_id = 1 AND ativo = 1;
-- Verificar: deve usar índice idx_categoria_ativo
```
- [ ] Query usa índice (não é "Full Table Scan")

---

## 4️⃣ SEGURANÇA E VALIDAÇÕES

### 4.1 Autenticação
- [ ] Páginas admin redirecionam se não logado
- [ ] Logout destrói sessão corretamente
- [ ] Senha com BCrypt (verificar no banco: hash começa com `$2y$`)

### 4.2 SQL Injection (Proteção)
**Teste (NÃO deve quebrar o sistema):**
- [ ] Login com email: `admin' OR '1'='1`
- [ ] Busca de produto: `<script>alert('XSS')</script>`
- [ ] Comentário: `'; DROP TABLE produtos; --`

Resultado esperado: Dados tratados como string, não executam código.

### 4.3 Upload de Arquivos
- [ ] Upload de .php é rejeitado
- [ ] Upload de arquivo > 2MB é rejeitado
- [ ] Upload de .txt é rejeitado
- [ ] Apenas JPG, PNG, GIF aceitos

---

## 5️⃣ RESPONSIVIDADE E UX

### 5.1 Design Responsivo
**Testar em diferentes resoluções:**
- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)
- [ ] Menu mobile funciona
- [ ] Imagens redimensionam
- [ ] Tabelas adaptam
- [ ] Formulários usáveis

### 5.2 Usabilidade
- [ ] Mensagens de sucesso aparecem
- [ ] Mensagens de erro aparecem
- [ ] Loading states (se houver)
- [ ] Botões desabilitam após clique
- [ ] Confirmações antes de deletar
- [ ] Máscaras ajudam no preenchimento

---

## 6️⃣ PERFORMANCE

### 6.1 Carregamento
- [ ] Página inicial carrega < 2s
- [ ] Imagens otimizadas (< 500KB cada)
- [ ] CSS minificado (se houver)
- [ ] Sem erros 404 no console

### 6.2 Consultas ao Banco
- [ ] Dashboard carrega rápido (< 1s)
- [ ] Listagem de produtos rápida
- [ ] Sem queries N+1
- [ ] Joins eficientes

---

## 7️⃣ TESTES FINAIS

### 7.1 Fluxo Completo do Cliente
1. [ ] Acessar site
2. [ ] Ver cardápio
3. [ ] Adicionar 3 produtos ao carrinho
4. [ ] Criar conta nova
5. [ ] Fazer login
6. [ ] Finalizar pedido
7. [ ] Ver pedido em "Minha Conta"
8. [ ] Enviar mensagem de contato

### 7.2 Fluxo Completo do Admin
1. [ ] Fazer login no admin
2. [ ] Criar novo produto
3. [ ] Criar nova categoria
4. [ ] Ver pedidos pendentes
5. [ ] Alterar status de um pedido
6. [ ] Ver mensagens de contato
7. [ ] Marcar mensagem como lida
8. [ ] Ver clientes cadastrados
9. [ ] Fazer logout

### 7.3 Verificações no Banco
- [ ] Tabela `produtos`: dados corretos
- [ ] Tabela `clientes`: senhas com hash
- [ ] Tabela `pedidos`: pedidos salvos
- [ ] Tabela `pedidos_itens`: itens vinculados
- [ ] Tabela `auditoria_precos`: triggers funcionando
- [ ] Tabela `contatos`: mensagens salvas

---

## 📊 RESULTADO FINAL

**Total de Testes:** ~200 itens

**Testes Passados:** _____ / 200  
**Testes Falhados:** _____  
**Taxa de Sucesso:** _____%

---

## 🐛 BUGS ENCONTRADOS

| # | Descrição | Severidade | Status |
|---|-----------|------------|--------|
| 1 |           | Alta/Média/Baixa | Pendente/Corrigido |
| 2 |           |            |        |

---

## ✅ APROVAÇÃO PARA ENTREGA

- [ ] Todos os testes críticos passaram
- [ ] Sem bugs de severidade alta
- [ ] README.md atualizado
- [ ] Código versionado no Git
- [ ] Banco de dados com dados de exemplo
- [ ] Credenciais de teste documentadas

**Data do Teste:** ___/___/2025  
**Testado por:** _________________  
**Ambiente:** Localhost XAMPP

---

**Observações:**
_______________________________________________________________________
_______________________________________________________________________
_______________________________________________________________________
