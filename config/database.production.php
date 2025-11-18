<?php
/**
 * Configuração de Conexão com Banco de Dados - PRODUÇÃO
 * Assados Delivery
 * 
 * INSTRUÇÕES:
 * 1. Após fazer upload dos arquivos para o servidor de hospedagem
 * 2. Renomeie este arquivo de "database.production.php" para "database.php"
 * 3. Preencha as credenciais do banco fornecidas pela hospedagem
 * 4. IMPORTANTE: NUNCA commite este arquivo com credenciais reais no Git!
 */

// ================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// ================================================
// Substitua pelos valores fornecidos pela sua hospedagem

define('DB_HOST', 'seu_host_aqui');           // Ex: sql123.infinityfree.net
define('DB_NAME', 'seu_banco_aqui');          // Ex: epiz_12345678_assados
define('DB_USER', 'seu_usuario_aqui');        // Ex: epiz_12345678
define('DB_PASS', 'sua_senha_aqui');          // Senha fornecida pela hospedagem
define('DB_CHARSET', 'utf8mb4');

// ================================================
// EXEMPLO DE CONFIGURAÇÃO INFINITYFREE:
// ================================================
// define('DB_HOST', 'sql123.infinityfree.net');
// define('DB_NAME', 'epiz_12345678_assados_delivery');
// define('DB_USER', 'epiz_12345678');
// define('DB_PASS', 'SuaSenhaAqui123!');
// define('DB_CHARSET', 'utf8mb4');

// ================================================
// EXEMPLO DE CONFIGURAÇÃO 000WEBHOST:
// ================================================
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'id12345678_assados');
// define('DB_USER', 'id12345678_admin');
// define('DB_PASS', 'SuaSenhaAqui123!');
// define('DB_CHARSET', 'utf8mb4');

/**
 * Classe Database - Singleton Pattern
 * Gerencia a conexão com o banco de dados usando PDO
 */
class Database {
    private static $instance = null;
    private $connection;

    /**
     * Construtor privado para implementar Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // Em produção, NÃO exiba detalhes do erro
            error_log("Erro de conexão com banco: " . $e->getMessage());
            die("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
        }
    }

    /**
     * Retorna a instância única da classe (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Previne clonagem da instância
     */
    private function __clone() {}

    /**
     * Previne deserialização da instância
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
