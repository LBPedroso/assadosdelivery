<?php
/**
 * API: Produto
 * Retorna informações de um produto em JSON
 */

header('Content-Type: application/json');
require_once '../config/config.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
    exit;
}

$produtoModel = new Produto();
$produto = $produtoModel->findById($_GET['id']);

if ($produto) {
    echo json_encode([
        'success' => true,
        'data' => $produto
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Produto não encontrado'
    ]);
}
