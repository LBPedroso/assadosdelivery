<?php
/**
 * Model: Usuario (Administrador)
 * Gerencia os usuários do painel administrativo
 */

require_once __DIR__ . '/Model.php';

class Usuario extends Model {
    protected $table = 'usuarios';
    
    /**
     * Buscar usuário por email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Verificar login
     */
    public function verificarLogin($email, $senha) {
        $usuario = $this->findByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha']) && $usuario['ativo']) {
            // Atualizar último acesso
            $this->atualizarUltimoAcesso($usuario['id']);
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Criar novo usuário
     */
    public function criar($dados) {
        // Hash da senha
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        
        return $this->create($dados);
    }
    
    /**
     * Atualizar último acesso
     */
    private function atualizarUltimoAcesso($id) {
        $sql = "UPDATE {$this->table} SET ultimo_acesso = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Alterar senha
     */
    public function alterarSenha($id, $senha_nova) {
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        return $this->update($id, ['senha' => $senha_hash]);
    }
}
