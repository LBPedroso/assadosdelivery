<?php
/**
 * Controller: Autenticação
 * Gerencia login, registro e sessões de usuários
 */

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $clienteModel;
    private $usuarioModel;
    
    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->usuarioModel = new Usuario();
        
        // Iniciar sessão se não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Login de cliente
     */
    public function loginCliente($emailOrTelefone, $senha) {
        $cliente = $this->clienteModel->findByEmailOrTelefone($emailOrTelefone);
        
        if (!$cliente) {
            return ['success' => false, 'message' => 'Email/telefone não cadastrado'];
        }
        
        if (!password_verify($senha, $cliente['senha'])) {
            return ['success' => false, 'message' => 'Senha incorreta'];
        }
        
        // Criar sessão
        $_SESSION['cliente_id'] = $cliente['id'];
        $_SESSION['cliente_nome'] = $cliente['nome'];
        $_SESSION['cliente_email'] = $cliente['email'];
        $_SESSION['tipo_usuario'] = 'cliente';
        
        return ['success' => true, 'message' => 'Login realizado com sucesso'];
    }
    
    /**
     * Login de administrador
     */
    public function loginAdmin($email, $senha) {
        $usuario = $this->usuarioModel->findByEmail($email);
        
        if (!$usuario) {
            return ['success' => false, 'message' => 'Email não cadastrado'];
        }
        
        if (!$usuario['ativo']) {
            return ['success' => false, 'message' => 'Usuário inativo'];
        }
        
        if (!password_verify($senha, $usuario['senha'])) {
            return ['success' => false, 'message' => 'Senha incorreta'];
        }
        
        // Criar sessão
        $_SESSION['admin_id'] = $usuario['id'];
        $_SESSION['admin_nome'] = $usuario['nome'];
        $_SESSION['admin_email'] = $usuario['email'];
        $_SESSION['tipo_usuario'] = 'admin';
        
        return ['success' => true, 'message' => 'Login realizado com sucesso'];
    }
    
    /**
     * Registrar novo cliente
     */
    public function registrarCliente($dados) {
        // Validar dados básicos
        if (empty($dados['nome']) || empty($dados['senha'])) {
            return ['success' => false, 'message' => 'Preencha nome e senha'];
        }
        
        // Validar que pelo menos email OU telefone foi informado
        if (empty($dados['email']) && empty($dados['telefone'])) {
            return ['success' => false, 'message' => 'Informe pelo menos um email ou telefone'];
        }
        
        // Verificar se email já existe (apenas se foi informado)
        if (!empty($dados['email']) && $this->clienteModel->findByEmail($dados['email'])) {
            return ['success' => false, 'message' => 'Este email já está cadastrado'];
        }
        
        // Validar email (apenas se foi informado)
        if (!empty($dados['email']) && !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email inválido'];
        }
        
        // Hash da senha
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        
        // Criar cliente
        try {
            $cliente_id = $this->clienteModel->create($dados);
            
            // Auto-login
            $cliente = $this->clienteModel->findById($cliente_id);
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nome'] = $cliente['nome'];
            $_SESSION['cliente_email'] = $cliente['email'];
            $_SESSION['tipo_usuario'] = 'cliente';
            
            return ['success' => true, 'message' => 'Cadastro realizado com sucesso'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro ao cadastrar: ' . $e->getMessage()];
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Logout realizado com sucesso'];
    }
    
    /**
     * Verificar se está logado como cliente
     */
    public static function isCliente() {
        return isset($_SESSION['cliente_id']) && $_SESSION['tipo_usuario'] === 'cliente';
    }
    
    /**
     * Verificar se está logado como admin
     */
    public static function isAdmin() {
        return isset($_SESSION['admin_id']) && $_SESSION['tipo_usuario'] === 'admin';
    }
    
    /**
     * Requerer login de cliente
     */
    public static function requireCliente() {
        if (!self::isCliente()) {
            header('Location: ' . SITE_URL . '/login.php');
            exit;
        }
    }
    
    /**
     * Requerer login de admin
     */
    public static function requireAdmin() {
        if (!self::isAdmin()) {
            header('Location: ' . SITE_URL . '/admin/login.php');
            exit;
        }
    }
}
