<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../models/Categoria.php';

AuthController::requireAdmin();

$produtoModel = new Produto();
$categoriaModel = new Categoria();

// Processar ações
$mensagem = '';
$tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'criar') {
        $dados = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => $_POST['preco'],
            'categoria_id' => $_POST['categoria_id'],
            'estoque' => $_POST['estoque'],
            'ativo' => isset($_POST['ativo']) ? 1 : 0,
            'destaque' => isset($_POST['destaque']) ? 1 : 0,
            'imagem' => 'placeholder.jpg'
        ];
        
        if ($produtoModel->create($dados)) {
            $mensagem = 'Produto criado com sucesso!';
            $tipo = 'sucesso';
        } else {
            $mensagem = 'Erro ao criar produto.';
            $tipo = 'erro';
        }
    } elseif ($acao === 'editar') {
        $id = $_POST['id'];
        $dados = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => $_POST['preco'],
            'categoria_id' => $_POST['categoria_id'],
            'estoque' => $_POST['estoque'],
            'ativo' => isset($_POST['ativo']) ? 1 : 0,
            'destaque' => isset($_POST['destaque']) ? 1 : 0
        ];
        
        if ($produtoModel->update($id, $dados)) {
            $mensagem = 'Produto atualizado com sucesso!';
            $tipo = 'sucesso';
        } else {
            $mensagem = 'Erro ao atualizar produto.';
            $tipo = 'erro';
        }
    } elseif ($acao === 'excluir') {
        $id = $_POST['id'];
        if ($produtoModel->delete($id)) {
            $mensagem = 'Produto excluído com sucesso!';
            $tipo = 'sucesso';
        } else {
            $mensagem = 'Erro ao excluir produto.';
            $tipo = 'erro';
        }
    }
}

// Buscar todos os produtos e categorias
$produtos = $produtoModel->findAll();
$categorias = $categoriaModel->findAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .container-admin {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #E63946 0%, #C5303A 100%);
            color: white;
            padding: 20px;
        }
        
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .sidebar nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        
        .sidebar nav a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar nav a.active {
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header-admin h1 {
            color: #333;
            font-size: 28px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #E63946;
            color: white;
        }
        
        .btn-primary:hover {
            background: #C5303A;
        }
        
        .btn-secondary {
            background: #F77F00;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #D66D00;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            margin-right: 5px;
        }
        
        .mensagem {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .mensagem.sucesso {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .mensagem.erro {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .tabela-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        th {
            font-weight: 600;
            color: #495057;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-ativo {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inativo {
            background: #f8d7da;
            color: #721c24;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-header h2 {
            color: #333;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }
        
        .close:hover {
            color: #000;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 5px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container-admin">
        <aside class="sidebar">
            <h2>🔥 Admin</h2>
            <nav>
                <a href="index.php">📊 Dashboard</a>
                <a href="produtos.php" class="active">🥩 Produtos</a>
                <a href="categorias.php">📁 Categorias</a>
                <a href="pedidos.php">📦 Pedidos</a>
                <a href="clientes.php">👥 Clientes</a>
                <a href="../logout.php">🚪 Sair</a>
            </nav>
        </aside>
        
        <main class="main-content">
            <div class="header-admin">
                <h1>Gerenciar Produtos</h1>
                <button class="btn btn-primary" onclick="abrirModal('criar')">+ Novo Produto</button>
            </div>
            
            <?php if ($mensagem): ?>
            <div class="mensagem <?php echo $tipo; ?>">
                <?php echo $mensagem; ?>
            </div>
            <?php endif; ?>
            
            <div class="tabela-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Destaque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo $produto['id']; ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria_nome'] ?? 'N/A'); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $produto['estoque']; ?></td>
                            <td>
                                <span class="status-badge <?php echo $produto['ativo'] ? 'status-ativo' : 'status-inativo'; ?>">
                                    <?php echo $produto['ativo'] ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </td>
                            <td><?php echo $produto['destaque'] ? '⭐' : ''; ?></td>
                            <td>
                                <button class="btn btn-secondary btn-small" onclick='editarProduto(<?php echo json_encode($produto); ?>)'>Editar</button>
                                <button class="btn btn-danger btn-small" onclick="excluirProduto(<?php echo $produto['id']; ?>, '<?php echo addslashes($produto['nome']); ?>')">Excluir</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Modal Criar/Editar -->
    <div id="modalProduto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitulo">Novo Produto</h2>
                <span class="close" onclick="fecharModal()">&times;</span>
            </div>
            <form id="formProduto" method="POST">
                <input type="hidden" name="acao" id="acao" value="criar">
                <input type="hidden" name="id" id="produtoId">
                
                <div class="form-group">
                    <label for="nome">Nome do Produto *</label>
                    <input type="text" name="nome" id="nome" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea name="descricao" id="descricao"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoria *</label>
                    <select name="categoria_id" id="categoria_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (R$) *</label>
                    <input type="number" name="preco" id="preco" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="estoque">Estoque *</label>
                    <input type="number" name="estoque" id="estoque" min="0" required>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="ativo" id="ativo" checked>
                            Produto Ativo
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="destaque" id="destaque">
                            Produto em Destaque
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Salvar Produto</button>
                    <button type="button" class="btn btn-secondary" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Excluir -->
    <div id="modalExcluir" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Confirmar Exclusão</h2>
                <span class="close" onclick="fecharModalExcluir()">&times;</span>
            </div>
            <p id="mensagemExcluir" style="margin-bottom: 20px;"></p>
            <form method="POST">
                <input type="hidden" name="acao" value="excluir">
                <input type="hidden" name="id" id="excluirId">
                <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                <button type="button" class="btn btn-secondary" onclick="fecharModalExcluir()">Cancelar</button>
            </form>
        </div>
    </div>
    
    <script>
        function abrirModal(acao) {
            document.getElementById('modalProduto').style.display = 'block';
            document.getElementById('formProduto').reset();
            document.getElementById('acao').value = 'criar';
            document.getElementById('modalTitulo').textContent = 'Novo Produto';
            document.getElementById('ativo').checked = true;
        }
        
        function editarProduto(produto) {
            document.getElementById('modalProduto').style.display = 'block';
            document.getElementById('acao').value = 'editar';
            document.getElementById('modalTitulo').textContent = 'Editar Produto';
            document.getElementById('produtoId').value = produto.id;
            document.getElementById('nome').value = produto.nome;
            document.getElementById('descricao').value = produto.descricao || '';
            document.getElementById('categoria_id').value = produto.categoria_id;
            document.getElementById('preco').value = produto.preco;
            document.getElementById('estoque').value = produto.estoque;
            document.getElementById('ativo').checked = produto.ativo == 1;
            document.getElementById('destaque').checked = produto.destaque == 1;
        }
        
        function fecharModal() {
            document.getElementById('modalProduto').style.display = 'none';
        }
        
        function excluirProduto(id, nome) {
            document.getElementById('modalExcluir').style.display = 'block';
            document.getElementById('excluirId').value = id;
            document.getElementById('mensagemExcluir').textContent = `Tem certeza que deseja excluir o produto "${nome}"?`;
        }
        
        function fecharModalExcluir() {
            document.getElementById('modalExcluir').style.display = 'none';
        }
        
        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modalProduto = document.getElementById('modalProduto');
            const modalExcluir = document.getElementById('modalExcluir');
            if (event.target == modalProduto) {
                fecharModal();
            }
            if (event.target == modalExcluir) {
                fecharModalExcluir();
            }
        }
    </script>
</body>
</html>
