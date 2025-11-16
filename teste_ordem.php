<?php
require_once 'config/config.php';

$categoriaModel = new Categoria();
$categorias = $categoriaModel->findAtivas();

echo "<h2>Teste de Ordem das Categorias</h2>";
echo "<pre>";
foreach ($categorias as $categoria) {
    echo "ID: {$categoria['id']} - Nome: {$categoria['nome']}\n";
}
echo "</pre>";
?>
