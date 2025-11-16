<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PedidoController.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o cliente está logado
if (!AuthController::isCliente()) {
    $_SESSION['checkout_redirect'] = true;
    header('Location: login.php');
    exit;
}

// Processar finalização do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoController = new PedidoController();
    
    // Dados do pedido
    $dados = [
        'cliente_id' => $_SESSION['cliente_id'],
        'observacoes' => $_POST['observacoes'] ?? '',
        'forma_pagamento' => $_POST['forma_pagamento'] ?? 'dinheiro',
        'data_entrega' => $_POST['data_entrega'] ?? date('Y-m-d', strtotime('+1 day'))
    ];
    
    // Criar pedido
    $pedidoId = $pedidoController->criarPedido($dados);
    
    if ($pedidoId) {
        // Limpar carrinho
        echo "<script>localStorage.removeItem('carrinho');</script>";
        $_SESSION['pedido_sucesso'] = $pedidoId;
        header('Location: pedido-confirmado.php?id=' . $pedidoId);
        exit;
    } else {
        $erro = "Erro ao processar pedido. Tente novamente.";
    }
}

$clienteNome = $_SESSION['cliente_nome'] ?? 'Cliente';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pedido - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 20px;
        }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .checkout-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #E63946;
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
            padding: 1.5rem 0;
            margin-top: 1rem;
            border-top: 2px solid #E63946;
            font-size: 1.3rem;
            font-weight: bold;
            color: #E63946;
        }
        .item-pedido {
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .item-pedido:last-child {
            border-bottom: none;
        }
        .item-nome {
            font-weight: 600;
            color: #333;
        }
        .item-detalhes {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            color: #666;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'views/partials/header.php'; ?>

    <div class="checkout-container">
        <h1>Finalizar Pedido</h1>
        <p style="color: #666; margin-top: 0.5rem;">Olá, <?php echo htmlspecialchars($clienteNome); ?>! Revise seu pedido abaixo.</p>

        <?php if (isset($erro)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="checkout-grid" id="form-checkout">
            <input type="hidden" name="carrinho_json" id="carrinho_json">
            <div>
                <div class="checkout-section">
                    <h2 style="margin-bottom: 1.5rem;">Informações de Entrega</h2>
                    
                    <div class="form-group">
                        <label>Data de Entrega *</label>
                        <input type="date" name="data_entrega" required 
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        <small style="color: #666;">Entregas apenas aos Sábados e Domingos</small>
                    </div>

                    <div class="form-group">
                        <label>Forma de Pagamento *</label>
                        <select name="forma_pagamento" required>
                            <option value="dinheiro">💵 Dinheiro</option>
                            <option value="pix">📱 PIX</option>
                            <option value="cartao_debito">💳 Cartão de Débito</option>
                            <option value="cartao_credito">💳 Cartão de Crédito</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Observações</label>
                        <textarea name="observacoes" rows="4" 
                                  placeholder="Alguma observação sobre seu pedido? (opcional)"></textarea>
                    </div>
                </div>
            </div>

            <div>
                <div class="checkout-section">
                    <h2 style="margin-bottom: 1.5rem;">Resumo do Pedido</h2>
                    
                    <div id="itens-pedido"></div>
                    
                    <div class="resumo-item" style="margin-top: 1rem;">
                        <span>Subtotal</span>
                        <span id="subtotal-valor">R$ 0,00</span>
                    </div>
                    
                    <div class="resumo-item">
                        <span>Taxa de Entrega</span>
                        <span id="frete-valor">R$ 0,00</span>
                    </div>
                    
                    <div class="resumo-total">
                        <span>Total</span>
                        <span id="total-valor">R$ 0,00</span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">
                        Confirmar Pedido
                    </button>
                    
                    <a href="carrinho.php" class="btn btn-secondary" 
                       style="width: 100%; padding: 0.8rem; text-align: center; margin-top: 1rem; display: block;">
                        Voltar ao Carrinho
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php include 'views/partials/footer.php'; ?>

    <script src="public/assets/js/carrinho.js"></script>
    <script>
        // Renderizar resumo do pedido
        function renderizarResumo() {
            const carrinho = obterCarrinho();
            
            if (carrinho.length === 0) {
                window.location.href = 'carrinho.php';
                return;
            }

            let subtotal = 0;
            let itensHTML = '';

            carrinho.forEach(item => {
                const itemTotal = item.preco * item.quantidade;
                subtotal += itemTotal;

                itensHTML += `
                    <div class="item-pedido">
                        <div class="item-nome">${item.nome}</div>
                        <div class="item-detalhes">
                            <span>${item.quantidade}x R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                            <span>R$ ${itemTotal.toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                `;
            });

            document.getElementById('itens-pedido').innerHTML = itensHTML;

            const frete = subtotal >= 50 ? 0 : 5;
            const total = subtotal + frete;

            document.getElementById('subtotal-valor').textContent = 
                'R$ ' + subtotal.toFixed(2).replace('.', ',');
            document.getElementById('frete-valor').textContent = 
                frete === 0 ? 'GRÁTIS' : 'R$ ' + frete.toFixed(2).replace('.', ',');
            document.getElementById('total-valor').textContent = 
                'R$ ' + total.toFixed(2).replace('.', ',');
        }

        // Renderizar ao carregar
        renderizarResumo();

        // Definir data mínima (próximo sábado ou domingo)
        const hoje = new Date();
        const diaSemana = hoje.getDay();
        let diasAteProximoFimDeSemana;

        if (diaSemana === 0) { // Domingo
            diasAteProximoFimDeSemana = 6; // Próximo sábado
        } else if (diaSemana === 6) { // Sábado
            diasAteProximoFimDeSemana = 1; // Amanhã (domingo)
        } else {
            diasAteProximoFimDeSemana = 6 - diaSemana; // Próximo sábado
        }

        const dataMinima = new Date(hoje);
        dataMinima.setDate(hoje.getDate() + diasAteProximoFimDeSemana);
        
        const inputData = document.querySelector('input[name="data_entrega"]');
        inputData.value = dataMinima.toISOString().split('T')[0];
        inputData.min = dataMinima.toISOString().split('T')[0];

        // Validar apenas sábado e domingo
        inputData.addEventListener('change', function() {
            const dataSelecionada = new Date(this.value + 'T00:00:00');
            const dia = dataSelecionada.getDay();
            
            if (dia !== 0 && dia !== 6) {
                alert('Entregas apenas aos Sábados e Domingos!');
                this.value = dataMinima.toISOString().split('T')[0];
            }
        });

        // Ao submeter o form, adicionar carrinho ao campo hidden
        document.getElementById('form-checkout').addEventListener('submit', function() {
            const carrinho = obterCarrinho();
            document.getElementById('carrinho_json').value = JSON.stringify(carrinho);
        });
    </script>
</body>
</html>
