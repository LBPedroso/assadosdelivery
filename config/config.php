<?php
/**
 * Configurações Gerais do Sistema
 * Assados Delivery
 */

// Configurações do Site
define('SITE_NAME', 'Assados Delivery');
define('SITE_SLOGAN', 'Seu almoço, sem esforço no final de semana');
define('SITE_URL', 'http://localhost:8080/assados-delivery');

// Informações de Contato
define('SITE_TELEFONE', '(44) 99968-0220');
define('SITE_EMAIL', 'contato@assadosdelivery.com');
define('SITE_CIDADE', 'Campo Mourão');
define('SITE_ESTADO', 'PR');

// Configurações de Sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Diretórios
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', SITE_URL . '/public/assets');

// Configurações de Horário de Funcionamento
define('DIAS_FUNCIONAMENTO', ['Sábado', 'Domingo']);
define('HORARIO_INICIO', '10:00');
define('HORARIO_FIM', '15:00');

// Configurações de Entrega
define('TAXA_ENTREGA', 5.00);
define('PEDIDO_MINIMO', 30.00);

// Cores da Identidade Visual
define('COR_PRIMARIA', '#E63946');   // Vermelho
define('COR_SECUNDARIA', '#F77F00'); // Laranja
define('COR_TERCIARIA', '#8B4513');  // Marrom

// Auto-load de classes
spl_autoload_register(function($class) {
    $paths = [
        BASE_PATH . '/models/' . $class . '.php',
        BASE_PATH . '/controllers/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Incluir conexão com banco
require_once BASE_PATH . '/config/database.php';
