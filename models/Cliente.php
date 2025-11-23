<?php
/**
 * Model: Cliente
 * Gerencia os clientes do sistema
 */

require_once __DIR__ . '/Model.php';

class Cliente extends Model {
    protected $table = 'clientes';
    
    /**
     * Buscar cliente por email
     */
    public function findByEmail($email) {
        if (empty($email)) {
            return null;
        }
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar cliente por telefone
     */
    public function findByTelefone($telefone) {
        if (empty($telefone)) {
            return null;
        }
        $sql = "SELECT * FROM {$this->table} WHERE telefone = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$telefone]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar cliente por email OU telefone
     */
    public function findByEmailOrTelefone($emailOrTelefone) {
        if (empty($emailOrTelefone)) {
            return null;
        }
        
        // Normalizar telefone: se não for email, formatar
        $telefoneFormatado = $emailOrTelefone;
        if (!filter_var($emailOrTelefone, FILTER_VALIDATE_EMAIL)) {
            // Remove tudo que não é número
            $numeros = preg_replace('/\D/', '', $emailOrTelefone);
            
            // Se tem 11 dígitos, formatar como (XX) XXXXX-XXXX
            if (strlen($numeros) === 11) {
                $telefoneFormatado = '(' . substr($numeros, 0, 2) . ') ' . 
                                    substr($numeros, 2, 5) . '-' . 
                                    substr($numeros, 7, 4);
            }
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE email = ? OR telefone = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$emailOrTelefone, $telefoneFormatado]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar cliente por CPF
     */
    public function findByCPF($cpf) {
        $sql = "SELECT * FROM {$this->table} WHERE cpf = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cpf]);
        return $stmt->fetch();
    }
    
    /**
     * Verificar login
     */
    public function verificarLogin($email, $senha) {
        $cliente = $this->findByEmail($email);
        
        if ($cliente && password_verify($senha, $cliente['senha'])) {
            return $cliente;
        }
        
        return false;
    }
    
    /**
     * Registrar novo cliente
     */
    public function registrar($dados) {
        // Hash da senha
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        
        return $this->create($dados);
    }
    
    /**
     * Buscar clientes recentes
     */
    public function findRecentes($limite = 10) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE ativo = 1 
                ORDER BY criado_em DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
    
    /**
     * Contar pedidos do cliente
     */
    public function contarPedidos($cliente_id) {
        $sql = "SELECT COUNT(*) as total FROM pedidos WHERE cliente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Total gasto pelo cliente
     */
    public function totalGasto($cliente_id) {
        $sql = "SELECT SUM(total) as total_gasto 
                FROM pedidos 
                WHERE cliente_id = ? AND status != 'cancelado'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        $result = $stmt->fetch();
        return $result['total_gasto'] ?? 0;
    }
}
