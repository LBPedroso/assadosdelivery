<?php
require_once 'config/config.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .carrinho-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }
        .carrinho-vazio {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .carrinho-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .carrinho-items {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .carrinho-item {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .carrinho-item:last-child {
            border-bottom: none;
        }
        .item-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .item-info h3 {
            color: var(--cor-escura);
            margin-bottom: 0.5rem;
        }
        .item-preco {
            font-size: 1.3rem;
            color: var(--cor-primaria);
            font-weight: bold;
        }
        .item-quantidade {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        .qty-btn {
            background: var(--cor-secundaria);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
        }
        .qty-btn:hover {
            background: var(--cor-primaria);
        }
        .qty-input {
            width: 60px;
            text-align: center;
            padding: 0.5rem;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        .btn-remover {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            padding: 0.5rem;
        }
        .resumo-pedido {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        .resumo-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #eee;
        }
        .resumo-total {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--cor-primaria);
        }
        @media (max-width: 768px) {
            .carrinho-grid {
                grid-template-columns: 1fr;
            }
            .carrinho-item {
                grid-template-columns: 80px 1fr;
            }
            .resumo-pedido {
                position: static;
            }
        }
    </style>
</head>
<body>
    <?php include 'views/partials/header.php'; ?>

    <div class="carrinho-container">
        <h1 class="section-title">Meu Carrinho</h1>

        <div id="carrinho-content">
            <!-- Conteúdo será carregado via JavaScript -->
            <div class="carrinho-vazio">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
                <h2>Carregando carrinho...</h2>
            </div>
        </div>
    </div>

    <?php include 'views/partials/footer.php'; ?>

    <script src="public/assets/js/carrinho.js"></script>
    <script>
        // Migração automática: corrigir caminhos de imagem antigos
        function migrarCarrinho() {
            let carrinho = obterCarrinho();
            let modificado = false;
            
            carrinho = carrinho.map(item => {
                // Se a imagem não tem o caminho completo, adicionar
                if (item.imagem && !item.imagem.includes('public/assets/img/produtos/')) {
                    item.imagem = 'public/assets/img/produtos/' + item.imagem;
                    modificado = true;
                }
                // Se não tem imagem, usar default
                if (!item.imagem || item.imagem === 'null' || item.imagem === 'NULL') {
                    item.imagem = 'public/assets/img/produtos/default.jpg';
                    modificado = true;
                }
                return item;
            });
            
            if (modificado) {
                salvarCarrinho(carrinho);
            }
        }
        
        // Executar migração ao carregar a página
        migrarCarrinho();
        
        // Renderizar carrinho
        function renderizarCarrinho() {
            const carrinho = obterCarrinho();
            const container = document.getElementById('carrinho-content');

            if (carrinho.length === 0) {
                container.innerHTML = `
                    <div class="carrinho-vazio">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
                        <h2>Seu carrinho está vazio</h2>
                        <p style="color: #666; margin: 1rem 0;">Adicione produtos do nosso cardápio!</p>
                        <a href="cardapio.php" class="btn btn-primary" style="margin-top: 1rem;">
                            Ver Cardápio
                        </a>
                    </div>
                `;
                return;
            }

            let subtotal = 0;
            let itensHTML = '';

            carrinho.forEach(item => {
                const itemTotal = item.preco * item.quantidade;
                subtotal += itemTotal;

                itensHTML += `
                    <div class="carrinho-item">
                        <img src="${item.imagem || 'public/assets/img/produtos/default.jpg'}" 
                             alt="${item.nome}" class="item-img">
                        
                        <div class="item-info">
                            <h3>${item.nome}</h3>
                            <p class="item-preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</p>
                            
                            <div class="item-quantidade">
                                <button class="qty-btn" onclick="alterarQuantidade(${item.id}, -1)">−</button>
                                <input type="number" class="qty-input" value="${item.quantidade}" 
                                       onchange="atualizarQuantidade(${item.id}, this.value)" min="1">
                                <button class="qty-btn" onclick="alterarQuantidade(${item.id}, 1)">+</button>
                                <span style="margin-left: 1rem; color: #666;">
                                    Subtotal: R$ ${itemTotal.toFixed(2).replace('.', ',')}
                                </span>
                            </div>
                        </div>
                        
                        <button class="btn-remover" onclick="removerDoCarrinho(${item.id})" title="Remover">
                            🗑️
                        </button>
                    </div>
                `;
            });

            const frete = subtotal >= 50 ? 0 : 8.00;
            const total = subtotal + frete;

            container.innerHTML = `
                <div class="carrinho-grid">
                    <div class="carrinho-items">
                        <h2 style="margin-bottom: 1.5rem;">Itens do Pedido</h2>
                        ${itensHTML}
                        
                        <div style="margin-top: 2rem;">
                            <a href="cardapio.php" style="color: var(--cor-secundaria); text-decoration: none;">
                                ← Continuar Comprando
                            </a>
                        </div>
                    </div>
                    
                    <div class="resumo-pedido">
                        <h2 style="margin-bottom: 1.5rem;">Resumo do Pedido</h2>
                        
                        <div class="resumo-item">
                            <span>Subtotal (${carrinho.length} ${carrinho.length === 1 ? 'item' : 'itens'})</span>
                            <span>R$ ${subtotal.toFixed(2).replace('.', ',')}</span>
                        </div>
                        
                        <div class="resumo-item">
                            <span>Taxa de Entrega</span>
                            <span>${frete === 0 ? 'GRÁTIS' : 'R$ ' + frete.toFixed(2).replace('.', ',')}</span>
                        </div>
                        
                        ${subtotal < 50 ? `
                            <div style="background: #fff3cd; padding: 1rem; border-radius: 5px; margin: 1rem 0; font-size: 0.9rem;">
                                <strong>💡 Dica:</strong> Faltam R$ ${(50 - subtotal).toFixed(2).replace('.', ',')} para frete grátis!
                            </div>
                        ` : ''}
                        
                        <div class="resumo-total">
                            <span>Total</span>
                            <span>R$ ${total.toFixed(2).replace('.', ',')}</span>
                        </div>
                        
                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; padding: 1rem; text-align: center; margin-top: 1rem;">
                            Finalizar Pedido
                        </a>
                        
                        <button onclick="limparCarrinho()" class="btn btn-secondary" 
                                style="width: 100%; padding: 0.8rem; margin-top: 1rem;">
                            Limpar Carrinho
                        </button>
                    </div>
                </div>
            `;

            atualizarContadorCarrinho();
        }

        // Alterar quantidade
        function alterarQuantidade(produtoId, delta) {
            const carrinho = obterCarrinho();
            const item = carrinho.find(i => i.id === produtoId);
            
            if (item) {
                item.quantidade = Math.max(1, item.quantidade + delta);
                salvarCarrinho(carrinho);
                renderizarCarrinho();
            }
        }

        // Atualizar quantidade direto
        function atualizarQuantidade(produtoId, novaQtd) {
            const quantidade = parseInt(novaQtd);
            if (quantidade < 1) return;
            
            const carrinho = obterCarrinho();
            const item = carrinho.find(i => i.id === produtoId);
            
            if (item) {
                item.quantidade = quantidade;
                salvarCarrinho(carrinho);
                renderizarCarrinho();
            }
        }

        // Limpar carrinho
        function limparCarrinho() {
            if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
                localStorage.removeItem('carrinho');
                renderizarCarrinho();
                atualizarContadorCarrinho();
            }
        }

        // Carregar ao abrir página
        document.addEventListener('DOMContentLoaded', function() {
            renderizarCarrinho();
        });
    </script>
</body>
</html>
