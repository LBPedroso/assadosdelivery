<?php
session_start();
require_once __DIR__ . '/config/config.php';

$mensagem = '';
$tipo = '';

// Processar envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagemTexto = $_POST['mensagem'] ?? '';
    
    // Validação simples
    if (empty($nome) || empty($email) || empty($mensagemTexto)) {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios.';
        $tipo = 'erro';
    } else {
        // Aqui você pode adicionar lógica para enviar email ou salvar no banco
        // Por enquanto, apenas simulamos sucesso
        $mensagem = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
        $tipo = 'sucesso';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Assados Delivery</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/public/assets/css/style.css">
    <style>
        .contato-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .contato-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }
        
        .contato-info {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .contato-info h2 {
            color: #E63946;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-item .icon {
            font-size: 24px;
            margin-right: 15px;
            color: #E63946;
        }
        
        .info-item .content h3 {
            color: #333;
            margin-bottom: 5px;
            font-size: 16px;
        }
        
        .info-item .content p {
            color: #666;
            font-size: 14px;
        }
        
        .contato-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .contato-form h2 {
            color: #E63946;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .mensagem-feedback {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .mensagem-feedback.sucesso {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .mensagem-feedback.erro {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn-enviar {
            background: linear-gradient(135deg, #E63946 0%, #C5303A 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .btn-enviar:hover {
            transform: translateY(-2px);
        }
        
        .mapa-container {
            margin-top: 40px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .mapa-container h2 {
            color: #E63946;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        @media (max-width: 768px) {
            .contato-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/views/partials/header.php'; ?>
    
    <main class="contato-container">
        <h1 style="font-size: 48px; color: #E63946; text-align: center; margin-bottom: 10px;">📞 Entre em Contato</h1>
        <p style="text-align: center; color: #666; font-size: 18px; margin-bottom: 30px;">
            Estamos à disposição para atendê-lo! Envie sua mensagem ou utilize um de nossos canais de contato.
        </p>
        
        <div class="contato-grid">
            <div class="contato-info">
                <h2>Informações de Contato</h2>
                
                <div class="info-item">
                    <div class="icon">📱</div>
                    <div class="content">
                        <h3>WhatsApp / Telefone</h3>
                        <p>(44) 99968-0220</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">📧</div>
                    <div class="content">
                        <h3>E-mail</h3>
                        <p>contato@assadosdelivery.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">📍</div>
                    <div class="content">
                        <h3>Localização</h3>
                        <p>Campo Mourão - PR</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🕐</div>
                    <div class="content">
                        <h3>Horário de Atendimento</h3>
                        <p><strong>Segunda a Sexta:</strong> Agendem Seus Pedidos</p>
                        <p><strong>Sábado e Domingo:</strong> Entregas e Retiradas no Local</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="icon">🚚</div>
                    <div class="content">
                        <h3>Como Funciona</h3>
                        <p>📅 <strong>Durante a semana:</strong> Faça seu pedido</p>
                        <p>🚚 <strong>Final de semana:</strong> Receba ou retire</p>
                        <p style="font-size: 13px; color: #666; margin-top: 8px;">Planeje seu churrasco com antecedência!</p>
                    </div>
                </div>
            </div>
            
            <div class="contato-form">
                <h2>Envie sua Mensagem</h2>
                
                <?php if ($mensagem): ?>
                <div class="mensagem-feedback <?php echo $tipo; ?>">
                    <?php echo $mensagem; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="(44) 99999-9999">
                    </div>
                    
                    <div class="form-group">
                        <label for="assunto">Assunto</label>
                        <select id="assunto" name="assunto">
                            <option value="">Selecione...</option>
                            <option value="duvida">Dúvida sobre produtos</option>
                            <option value="pedido">Informações sobre pedido</option>
                            <option value="entrega">Área de entrega</option>
                            <option value="sugestao">Sugestão</option>
                            <option value="reclamacao">Reclamação</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensagem">Mensagem *</label>
                        <textarea id="mensagem" name="mensagem" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-enviar">Enviar Mensagem</button>
                </form>
            </div>
        </div>
        
        <div class="mapa-container">
            <h2>Nossa Região de Atendimento</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Atendemos toda a região de Campo Mourão - PR. Entre em contato para confirmar se entregamos no seu bairro!
            </p>
            <div style="background: #f0f0f0; padding: 100px; text-align: center; border-radius: 5px; color: #999;">
                [Mapa de Campo Mourão - PR]
            </div>
        </div>
    </main>
    
    <?php include __DIR__ . '/views/partials/footer.php'; ?>
</body>
</html>
