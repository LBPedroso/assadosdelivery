<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/PedidoController.php';

$authController = new AuthController();
$authController->requireAdmin();

$pedidoController = new PedidoController();
$stats = $pedidoController->estatisticas();

$produtoModel = new Produto();
$categoriaModel = new Categoria();

// Contar produtos e categorias
$totalProdutos = count($produtoModel->findAll('ativo = ?', [1]));
$totalCategorias = count($categoriaModel->findAtivas());
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background: var(--cor-escura);
            color: white;
            padding: 2rem 0;
        }
        .sidebar h2 {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            color: var(--cor-secundaria);
        }
        .sidebar nav a {
            display: block;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: var(--cor-primaria);
        }
        .main-content {
            padding: 2rem;
            background: #f5f5f5;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--cor-escura);
        }
        .stat-card.primary .value { color: var(--cor-primaria); }
        .stat-card.secondary .value { color: var(--cor-secundaria); }
        .stat-card.success .value { color: #28a745; }
        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .table-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: var(--cor-clara);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2>🔥 Admin Panel</h2>
            <nav>
                <a href="index.php" class="active">📊 Dashboard</a>
                <a href="produtos.php">📦 Produtos</a>
                <a href="categorias.php">🏷️ Categorias</a>
                <a href="pedidos.php">🛒 Pedidos</a>
                <a href="clientes.php">👥 Clientes</a>
                <a href="../index.php" target="_blank">🌐 Ver Site</a>
                <a href="../logout.php">🚪 Sair</a>
            </nav>
        </aside>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="main-content">
            <h1 style="margin-bottom: 2rem;">Dashboard</h1>

            <!-- ESTATÍSTICAS -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <h3>Vendas do Mês</h3>
                    <div class="value">R$ <?php echo number_format($stats['vendas_mes'], 2, ',', '.'); ?></div>
                </div>

                <div class="stat-card secondary">
                    <h3>Vendas de Hoje</h3>
                    <div class="value">R$ <?php echo number_format($stats['vendas_hoje'], 2, ',', '.'); ?></div>
                </div>

                <div class="stat-card success">
                    <h3>Total de Pedidos</h3>
                    <div class="value"><?php echo $stats['total_pedidos']; ?></div>
                </div>

                <div class="stat-card">
                    <h3>Pedidos Pendentes</h3>
                    <div class="value"><?php echo $stats['pedidos_pendentes']; ?></div>
                </div>

                <div class="stat-card">
                    <h3>Produtos Ativos</h3>
                    <div class="value"><?php echo $totalProdutos; ?></div>
                </div>

                <div class="stat-card">
                    <h3>Categorias</h3>
                    <div class="value"><?php echo $totalCategorias; ?></div>
                </div>
            </div>

            <!-- GRÁFICOS -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div class="chart-container">
                    <h2 style="margin-bottom: 1rem;">Pedidos por Status</h2>
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="chart-container">
                    <h2 style="margin-bottom: 1rem;">Produtos Mais Vendidos</h2>
                    <canvas id="produtosChart"></canvas>
                </div>
            </div>

            <!-- PRODUTOS MAIS VENDIDOS -->
            <div class="table-container">
                <h2 style="margin-bottom: 1rem;">🏆 Top 5 Produtos Mais Vendidos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade Vendida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['produtos_mais_vendidos'])): ?>
                            <tr>
                                <td colspan="2" style="text-align: center; color: #999;">
                                    Nenhuma venda registrada ainda
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stats['produtos_mais_vendidos'] as $produto): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($produto['nome']); ?></strong></td>
                                    <td><?php echo $produto['total_vendido']; ?> unidades</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Gráfico de Pedidos por Status
        const statusData = <?php echo json_encode($stats['pedidos_por_status']); ?>;
        const statusLabels = statusData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1));
        const statusValues = statusData.map(item => item.total);

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#E63946', '#F77F00', '#8B4513', '#28a745', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfico de Produtos Mais Vendidos
        const produtosData = <?php echo json_encode($stats['produtos_mais_vendidos']); ?>;
        const produtosLabels = produtosData.map(item => item.nome);
        const produtosValues = produtosData.map(item => item.total_vendido);

        new Chart(document.getElementById('produtosChart'), {
            type: 'bar',
            data: {
                labels: produtosLabels,
                datasets: [{
                    label: 'Quantidade Vendida',
                    data: produtosValues,
                    backgroundColor: '#F77F00'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
