<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID do pedido não fornecido']);
    exit;
}

try {
    $db = Database::getInstance();
    
    // Buscar dados do pedido
    $sqlPedido = "SELECT * FROM pedidos WHERE id = :id";
    $stmtPedido = $db->prepare($sqlPedido);
    $stmtPedido->bindParam(':id', $id);
    $stmtPedido->execute();
    $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);
    
    if (!$pedido) {
        echo json_encode(['success' => false, 'message' => 'Pedido não encontrado']);
        exit;
    }
    
    // Buscar itens do pedido
    $sqlItens = "SELECT pi.*, p.nome as produto_nome 
                 FROM pedidos_itens pi
                 JOIN produtos p ON pi.produto_id = p.id
                 WHERE pi.pedido_id = :pedido_id";
    $stmtItens = $db->prepare($sqlItens);
    $stmtItens->bindParam(':pedido_id', $id);
    $stmtItens->execute();
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'pedido' => $pedido,
        'itens' => $itens
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
