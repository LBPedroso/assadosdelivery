<?php
require_once 'config/config.php';
require_once 'controllers/ProdutoController.php';

$produtoController = new ProdutoController();
$categoriaModel = new Categoria();

// Buscar categorias
$categorias = $categoriaModel->findAtivas();

// Filtros
$categoria_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : null;

// Buscar produtos
$produtos = $produtoController->buscarCardapio($categoria_id, $busca);

// Se filtrado por categoria, pegar info da categoria
$categoriaAtual = null;
if ($categoria_id) {
    $categoriaAtual = $categoriaModel->findById($categoria_id);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body>
    <!-- HEADER -->
    <?php include 'views/partials/header.php'; ?>

    <!-- BREADCRUMB -->
    <section style="background: var(--cor-clara); padding: 1rem 0;">
        <div class="container">
            <p>
                <a href="index.php" style="color: var(--cor-primaria); text-decoration: none;">Início</a> 
                » Cardápio
                <?php if ($categoriaAtual): ?>
                    » <?php echo htmlspecialchars($categoriaAtual['nome']); ?>
                <?php endif; ?>
            </p>
        </div>
    </section>

    <!-- CARDÁPIO -->
    <section>
        <div class="container">
            <h1 class="section-title">
                <?php echo $categoriaAtual ? htmlspecialchars($categoriaAtual['nome']) : 'Nosso Cardápio'; ?>
            </h1>

            <!-- FILTROS -->
            <div style="margin-bottom: 2rem;">
                <form method="GET" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <select name="categoria" style="padding: 0.8rem; border-radius: 5px; border: 2px solid #ddd;">
                        <option value="">Todas as Categorias</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $categoria_id == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="text" name="busca" placeholder="Buscar produtos..." 
                           value="<?php echo htmlspecialchars($busca ?? ''); ?>"
                           style="padding: 0.8rem; border-radius: 5px; border: 2px solid #ddd; min-width: 300px;">
                    
                    <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">
                        🔍 Buscar
                    </button>
                    
                    <?php if ($categoria_id || $busca): ?>
                        <a href="cardapio.php" class="btn btn-secondary" style="padding: 0.8rem 2rem;">
                            ✖ Limpar Filtros
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- PRODUTOS -->
            <?php if (empty($produtos)): ?>
                <div style="text-align: center; padding: 4rem 0;">
                    <p style="font-size: 1.2rem; color: #666;">
                        😕 Nenhum produto encontrado
                    </p>
                    <a href="cardapio.php" class="btn btn-primary" style="margin-top: 1rem;">
                        Ver todos os produtos
                    </a>
                </div>
            <?php else: ?>
                <div class="produtos-grid">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="produto-card">
                            <img src="<?php echo SITE_URL; ?>/public/assets/img/produtos/<?php echo $produto['imagem'] ?? 'placeholder.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                 class="produto-img">
                            
                            <div class="produto-info">
                                <div class="produto-categoria">
                                    <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                </div>
                                
                                <h3 class="produto-nome">
                                    <?php echo htmlspecialchars($produto['nome']); ?>
                                </h3>
                                
                                <p class="produto-descricao">
                                    <?php echo htmlspecialchars($produto['descricao']); ?>
                                </p>
                                
                                <div class="produto-footer">
                                    <span class="produto-preco">
                                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                        <?php if (!empty($produto['unidade'])): ?>
                                            <span style="font-size: 0.85em; color: #666;"> / <?php echo htmlspecialchars($produto['unidade']); ?></span>
                                        <?php endif; ?>
                                    </span>
                                    
                                    <?php if ($produto['estoque'] > 0): ?>
                                        <button onclick="adicionarAoCarrinho(<?php echo $produto['id']; ?>)" 
                                                class="btn-adicionar">
                                            🛒 Adicionar
                                        </button>
                                    <?php else: ?>
                                        <span style="color: #999; font-size: 0.9rem;">
                                            Indisponível
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <?php include 'views/partials/footer.php'; ?>

    <!-- JAVASCRIPT -->
    <script src="public/assets/js/carrinho.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            atualizarContadorCarrinho();
        });
    </script>
</body>
</html>
