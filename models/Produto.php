<?php
/**
 * Model: Produto
 * Gerencia os produtos do cardápio
 */

require_once __DIR__ . '/Model.php';

class Produto extends Model {
    protected $table = 'produtos';
    
    /**
     * Buscar produtos ativos
     */
    public function findAtivos() {
        return $this->findAll('ativo = ?', [1]);
    }
    
    /**
     * Buscar produtos por categoria
     */
    public function findByCategoria($categoria_id) {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM {$this->table} p
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.categoria_id = ? AND p.ativo = 1
                ORDER BY p.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoria_id]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar todos com categoria
     */
    public function findAllWithCategoria() {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM {$this->table} p
                INNER JOIN categorias c ON p.categoria_id = c.id
                ORDER BY c.nome, p.nome";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar por código
     */
    public function findByCodigo($codigo) {
        $sql = "SELECT * FROM {$this->table} WHERE codigo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }
    
    /**
     * Buscar produtos com estoque baixo
     */
    public function findEstoqueBaixo($limite = 10) {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM {$this->table} p
                INNER JOIN categorias c ON p.categoria_id = c.id
                WHERE p.estoque <= ? AND p.ativo = 1
                ORDER BY p.estoque ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
    
    /**
     * Atualizar estoque
     */
    public function atualizarEstoque($id, $quantidade) {
        $sql = "UPDATE {$this->table} SET estoque = estoque + ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$quantidade, $id]);
    }
    
    /**
     * Verificar disponibilidade (usa a FUNCTION do MySQL)
     */
    public function verificarDisponibilidade($id, $quantidade) {
        $sql = "SELECT fn_verificar_estoque(?, ?) as disponivel";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $quantidade]);
        $result = $stmt->fetch();
        return (bool) $result['disponivel'];
    }
    
    /**
     * Buscar produtos mais vendidos
     */
    public function findMaisVendidos($limite = 10) {
        $sql = "SELECT p.*, c.nome as categoria_nome, 
                       SUM(pi.quantidade) as total_vendido,
                       SUM(pi.subtotal) as receita_total
                FROM {$this->table} p
                INNER JOIN categorias c ON p.categoria_id = c.id
                INNER JOIN pedidos_itens pi ON p.id = pi.produto_id
                INNER JOIN pedidos ped ON pi.pedido_id = ped.id
                WHERE ped.status != 'cancelado'
                GROUP BY p.id
                ORDER BY total_vendido DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
