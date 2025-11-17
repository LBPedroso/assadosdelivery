<?php
// Iniciar sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="header-top">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                � 
                <a href="https://wa.me/5544999680220?text=Olá!%20Gostaria%20de%20fazer%20um%20pedido." 
                   target="_blank" 
                   style="color: white; text-decoration: none; font-weight: 600;">
                    (44) 99968-0220
                </a>
                 | 📧 
                <a href="mailto:contato@assadosdelivery.com" 
                   style="color: white; text-decoration: none;">
                    contato@assadosdelivery.com
                </a>
            </div>
            <div>
                📅 Seg-Sex: Pedidos | 🚚 Sáb-Dom: Entregas e Retiradas
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="header-content">
            <div>
                <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                    <span class="logo-icon">🔥</span>
                    <div>
                        <div><?php echo SITE_NAME; ?></div>
                        <div class="slogan"><?php echo SITE_SLOGAN; ?></div>
                    </div>
                </a>
            </div>
            
            <nav>
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php">Início</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/cardapio.php">Cardápio</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/sobre.php">Sobre</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contato.php">Contato</a></li>
                    <?php if (isset($_SESSION['cliente_id'])): ?>
                        <li><a href="<?php echo SITE_URL; ?>/minha-conta.php">Minha Conta</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/logout.php">Sair</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo SITE_URL; ?>/login.php">Entrar</a></li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo SITE_URL; ?>/carrinho.php" class="btn-carrinho">
                            🛒 Carrinho
                            <span class="carrinho-count" id="carrinho-count">0</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
