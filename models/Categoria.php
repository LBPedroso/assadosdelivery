<?php
/**
 * Model: Categoria
 * Gerencia as categorias de produtos
 */

require_once __DIR__ . '/Model.php';

class Categoria extends Model {
    protected $table = 'categorias';
    
    /**
     * Buscar categorias ativas
     */
    public function findAtivas() {
        $sql = "SELECT * FROM {$this->table} WHERE ativo = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([1]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar categoria por nome
     */
    public function findByNome($nome) {
        $sql = "SELECT * FROM {$this->table} WHERE nome = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nome]);
        return $stmt->fetch();
    }
    
    /**
     * Contar produtos por categoria
     */
    public function contarProdutos($categoria_id) {
        $sql = "SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ? AND ativo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoria_id]);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
