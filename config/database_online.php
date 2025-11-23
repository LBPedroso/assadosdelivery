<?php
/**
 * Configuração de Conexão com Banco de Dados - PRODUÇÃO
 * InfinityFree
 */

// Configurações do banco de dados
define('DB_HOST', 'sql202.infinityfree.com');
define('DB_NAME', 'if0_40443744_assados');
define('DB_USER', 'if0_40443744');
define('DB_PASS', 'assadados321');
define('DB_CHARSET', 'utf8mb4');

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
