<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Limpar usuários existentes
    $db->exec("DELETE FROM usuarios");
    
    // Gerar hash da senha
    $senha = 'admin123';
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir novo admin
    $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Administrador', 'admin@assados.com', $hash, 'admin']);
    
    echo "<h2>✅ Usuário administrador criado com sucesso!</h2>";
    echo "<p><strong>Email:</strong> admin@assados.com</p>";
    echo "<p><strong>Senha:</strong> admin123</p>";
    echo "<p><strong>Hash gerado:</strong> " . $hash . "</p>";
    echo "<br><a href='admin/login.php'>→ Ir para Login</a>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro: " . $e->getMessage() . "</h2>";
}
