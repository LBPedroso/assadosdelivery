<?php
/**
 * Controller: Pedido
 * Gerencia operações de pedidos
 */

require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Produto.php';

class PedidoController {
    private $pedidoModel;
    private $produtoModel;
    
    public function __construct() {
        $this->pedidoModel = new Pedido();
        $this->produtoModel = new Produto();
    }
    
    /**
     * Criar novo pedido
     */
    public function criar($cliente_id, $itens, $dados_entrega) {
        try {
            // Validar estoque de cada item
            foreach ($itens as $item) {
                $produto = $this->produtoModel->findById($item['produto_id']);
                
                if (!$produto || !$produto['ativo']) {
                    throw new Exception("Produto {$item['produto_id']} não disponível");
                }
                
                if ($produto['estoque'] < $item['quantidade']) {
                    throw new Exception("Estoque insuficiente para {$produto['nome']}");
                }
            }
            
            // Calcular total
            $subtotal = 0;
            foreach ($itens as $item) {
                $produto = $this->produtoModel->findById($item['produto_id']);
                $subtotal += $produto['preco'] * $item['quantidade'];
            }
            
            $taxa_entrega = $subtotal >= 50 ? 0 : 8.00;
            $valor_total = $subtotal + $taxa_entrega;
            
            // Criar pedido
            $pedido_id = $this->pedidoModel->criarPedido(
                $cliente_id,
                $itens,
                $valor_total,
                $taxa_entrega,
                $dados_entrega
            );
            
            return [
                'success' => true,
                'pedido_id' => $pedido_id,
                'message' => 'Pedido criado com sucesso!'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Listar pedidos do cliente
     */
    public function listarPorCliente($cliente_id) {
        return $this->pedidoModel->findAll('cliente_id = ?', [$cliente_id]);
    }
    
    /**
     * Buscar pedido por ID
     */
    public function buscar($id) {
        return $this->pedidoModel->findById($id);
    }
    
    /**
     * Atualizar status do pedido
     */
    public function atualizarStatus($pedido_id, $novo_status) {
        return $this->pedidoModel->update($pedido_id, ['status' => $novo_status]);
    }
    
    /**
     * Estatísticas para dashboard
     */
    public function estatisticas() {
        $db = Database::getInstance()->getConnection();
        
        // Total de pedidos
        $stmt = $db->query("SELECT COUNT(*) as total FROM pedidos");
        $totalPedidos = $stmt->fetch()['total'];
        
        // Pedidos pendentes
        $stmt = $db->query("SELECT COUNT(*) as total FROM pedidos WHERE status = 'pendente'");
        $pedidosPendentes = $stmt->fetch()['total'];
        
        // Vendas do mês
        $stmt = $db->query("SELECT SUM(valor_total) as total FROM pedidos 
                           WHERE MONTH(criado_em) = MONTH(CURRENT_DATE()) 
                           AND YEAR(criado_em) = YEAR(CURRENT_DATE())");
        $vendasMes = $stmt->fetch()['total'] ?? 0;
        
        // Vendas de hoje
        $stmt = $db->query("SELECT SUM(valor_total) as total FROM pedidos 
                           WHERE DATE(criado_em) = CURDATE()");
        $vendasHoje = $stmt->fetch()['total'] ?? 0;
        
        // Produtos mais vendidos
        $stmt = $db->query("SELECT p.nome, SUM(pi.quantidade) as total_vendido
                           FROM pedidos_itens pi
                           INNER JOIN produtos p ON pi.produto_id = p.id
                           GROUP BY p.id, p.nome
                           ORDER BY total_vendido DESC
                           LIMIT 5");
        $produtosMaisVendidos = $stmt->fetchAll();
        
        // Pedidos por status
        $stmt = $db->query("SELECT status, COUNT(*) as total FROM pedidos GROUP BY status");
        $pedidosPorStatus = $stmt->fetchAll();
        
        return [
            'total_pedidos' => $totalPedidos,
            'pedidos_pendentes' => $pedidosPendentes,
            'vendas_mes' => $vendasMes,
            'vendas_hoje' => $vendasHoje,
            'produtos_mais_vendidos' => $produtosMaisVendidos,
            'pedidos_por_status' => $pedidosPorStatus
        ];
    }
}
