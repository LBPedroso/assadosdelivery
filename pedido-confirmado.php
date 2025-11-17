<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o cliente está logado
AuthController::requireCliente();

$pedidoId = $_GET['id'] ?? null;

if (!$pedidoId) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .confirmacao-container {
            max-width: 700px;
            margin: 4rem auto;
            padding: 0 20px;
            text-align: center;
        }
        .confirmacao-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .check-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .pedido-numero {
            font-size: 2rem;
            font-weight: bold;
            color: #E63946;
            margin: 1rem 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: left;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-group a {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php include 'views/partials/header.php'; ?>

    <div class="confirmacao-container">
        <div class="confirmacao-card">
            <div class="check-icon">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h1 style="color: #28a745; margin-bottom: 1rem;">Pedido Confirmado!</h1>
            <p style="color: #666; font-size: 1.1rem;">Seu pedido foi recebido com sucesso.</p>

            <div class="pedido-numero">
                Pedido #<?php echo str_pad($pedidoId, 6, '0', STR_PAD_LEFT); ?>
            </div>

            <div class="info-box">
                <h3 style="margin-bottom: 1rem; color: #333;">Informações do Pedido</h3>
                <div class="info-item">
                    <span><strong>Status:</strong></span>
                    <span style="color: #ffc107; font-weight: 600;">⏳ Aguardando Confirmação</span>
                </div>
                <div class="info-item">
                    <span><strong>Forma de Pagamento:</strong></span>
                    <span>Informado no checkout</span>
                </div>
                <div class="info-item">
                    <span><strong>Previsão de Entrega:</strong></span>
                    <span>Sábado ou Domingo</span>
                </div>
            </div>

            <div style="background: #e7f3ff; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #0066cc; margin: 2rem 0; text-align: left;">
                <h4 style="margin-bottom: 0.5rem; color: #0066cc;">📱 Próximos Passos</h4>
                <ul style="margin: 0; padding-left: 1.5rem; color: #333;">
                    <li>Entraremos em contato para confirmar seu pedido</li>
                    <li>Você receberá atualizações sobre o status</li>
                    <li>Prepare-se para receber no fim de semana!</li>
                </ul>
            </div>

            <div class="btn-group">
                <a href="minha-conta.php" class="btn btn-primary">
                    Ver Meus Pedidos
                </a>
                <a href="cardapio.php" class="btn btn-secondary">
                    Continuar Comprando
                </a>
            </div>

            <p style="margin-top: 2rem; color: #999; font-size: 0.9rem;">
                Dúvidas? Entre em contato: 
                <a href="https://wa.me/5544999680220?text=Olá!%20Tenho%20dúvidas%20sobre%20meu%20pedido." 
                   target="_blank" 
                   style="color: #25D366; text-decoration: none; font-weight: 600;">
                    📱 (44) 99968-0220
                </a>
            </p>
        </div>
    </div>

    <?php include 'views/partials/footer.php'; ?>

    <script src="public/assets/js/carrinho.js"></script>
    <script>
        // Limpar carrinho após confirmação
        localStorage.removeItem('carrinho');
        atualizarContadorCarrinho();
    </script>
</body>
</html>
