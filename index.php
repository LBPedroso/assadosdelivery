<?php
require_once 'config/config.php';

// Buscar categorias
$categoriaModel = new Categoria();
$categorias = $categoriaModel->findAtivas();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo SITE_SLOGAN; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css?v=20251116134148">
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="header-top">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    📞 Contato: (44) 99968-0220 | 📧 contato@assadosdelivery.com
                </div>
                <div>
                    ⏰ Aberto apenas aos Sábados e Domingos (10h às 15h), agendamento a semana toda
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="header-content">
                <div>
                    <a href="index.php" class="logo">
                        <span class="logo-icon">🔥</span>
                        <div>
                            <div><?php echo SITE_NAME; ?></div>
                            <div class="slogan"><?php echo SITE_SLOGAN; ?></div>
                        </div>
                    </a>
                </div>
                
                <nav>
                    <ul>
                        <li><a href="index.php">Início</a></li>
                        <li><a href="cardapio.php">Cardápio</a></li>
                        <li><a href="sobre.php">Sobre</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <?php if (isset($_SESSION['cliente_id'])): ?>
                            <li><a href="minha-conta.php">Minha Conta</a></li>
                            <li><a href="logout.php">Sair</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Entrar</a></li>
                        <?php endif; ?>
                        <li>
                            <a href="carrinho.php" class="btn-carrinho">
                                🛒 Carrinho
                                <span class="carrinho-count" id="carrinho-count">0</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <h1>🍖 Assados Deliciosos Direto na Sua Casa!</h1>
            <p>Saboreie o melhor da culinária artesanal aos finais de semana</p>
            <a href="cardapio.php" class="btn btn-primary">Ver Cardápio</a>
            <a href="#categorias" class="btn btn-secondary">Nossas Especialidades</a>
        </div>
    </section>

    <!-- CATEGORIAS -->
    <section id="categorias" class="bg-light">
        <div class="container">
            <h2 class="section-title">Nossas Categorias</h2>
            
            <div class="categorias-grid">
                <?php 
                // Mapear ícones pelo NOME da categoria para garantir que está correto
                $iconesPorNome = [
                    'Carnes Assadas' => '🥩',
                    'Acompanhamentos' => '🍚',
                    'Combos' => '🍱',
                    'Bebidas' => '🍻',
                    'Conveniência' => '🔥'
                ];
                
                foreach ($categorias as $categoria): 
                    $icone = isset($iconesPorNome[$categoria['nome']]) ? $iconesPorNome[$categoria['nome']] : '🍽️';
                ?>
                <a href="cardapio.php?categoria=<?php echo $categoria['id']; ?>" class="categoria-card" style="order: <?php echo $categoria['id']; ?>">
                    <div class="categoria-icon"><?php echo $icone; ?></div>
                    <h3><?php echo htmlspecialchars($categoria['nome']); ?></h3>
                    <p><?php echo htmlspecialchars($categoria['descricao']); ?></p>
                </a>
                <?php 
                endforeach; 
                ?>
            </div>
        </div>
    </section>

    <!-- COMO FUNCIONA -->
    <section class="bg-light">
        <div class="container">
            <h2 class="section-title">Como Funciona</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: center;">
                <div>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📱</div>
                    <h3 style="color: var(--cor-primaria); margin-bottom: 0.5rem;">1. Escolha</h3>
                    <p>Navegue pelo nosso cardápio e selecione seus produtos favoritos</p>
                </div>
                
                <div>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🛒</div>
                    <h3 style="color: var(--cor-secundaria); margin-bottom: 0.5rem;">2. Faça o Pedido</h3>
                    <p>Adicione ao carrinho e finalize seu pedido para sábado ou domingo</p>
                </div>
                
                <div>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🚗</div>
                    <h3 style="color: var(--cor-terciaria); margin-bottom: 0.5rem;">3. Receba em Casa</h3>
                    <p>Entregamos quentinho no horário escolhido, das 10h às 15h</p>
                </div>
                
                <div>
                    <div style="font-size: 4rem; margin-bottom: 1rem;">😋</div>
                    <h3 style="color: var(--cor-primaria); margin-bottom: 0.5rem;">4. Aproveite!</h3>
                    <p>Saboreie seu almoço sem preocupação e curta o final de semana</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sobre Nós</h3>
                    <p>A Assados Delivery oferece refeições completas com carnes assadas artesanais, entregues quentinhas aos finais de semana.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Links Rápidos</h3>
                    <ul>
                        <li><a href="cardapio.php">Cardápio</a></li>
                        <li><a href="sobre.php">Sobre Nós</a></li>
                        <li><a href="contato.php">Contato</a></li>
                        <li><a href="admin/">Área Administrativa</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Horário de Funcionamento</h3>
                    <p><strong>Sábado e Domingo:</strong><br>
                    10:00 às 15:00</p>
                    <p><strong>Segunda a Sexta:</strong><br>
                    Somente Agendamentos</p>
                </div>
                
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>📞 (44) 99968-0220<br>
                    📧 contato@assadosdelivery.com<br>
                    📍 Campo Mourão, PR</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Assados Delivery - Campo Mourão, PR | Desenvolvido por LBP-StartWeb</p>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT -->
    <script src="public/assets/js/carrinho.js"></script>
    <script>
        // Atualizar contador do carrinho quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            atualizarContadorCarrinho();
        });
    </script>
</body>
</html>
