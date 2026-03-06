<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

$authController = new AuthController();
$authController->requireAdmin();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Lucro - Admin <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../public/assets/css/style.css">
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
        .card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .card h2 {
            margin-bottom: 1.5rem;
            color: var(--cor-escura);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #555;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.7rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
            width: 100%;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
        .btn-primary {
            background: var(--cor-primaria);
            color: white;
        }
        .btn-copy {
            background: var(--cor-secundaria);
            color: white;
            font-size: 0.85rem;
            padding: 0.5rem 1.2rem;
        }
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }
        .result-card {
            border-radius: 8px;
            padding: 1.2rem;
            text-align: center;
        }
        .result-card h4 {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.4rem;
        }
        .result-card .val {
            font-size: 1.6rem;
            font-weight: bold;
        }
        .result-card.green  { background: #e8f5e9; }
        .result-card.green  .val { color: #2e7d32; }
        .result-card.blue   { background: #e3f2fd; }
        .result-card.blue   .val { color: #1565c0; }
        .result-card.orange { background: #fff3e0; }
        .result-card.orange .val { color: #e65100; }
        .result-card.grey   { background: #f5f5f5; }
        .result-card.grey   .val { color: #424242; }
        .scenario-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: #f9f9f9;
            border-left: 4px solid var(--cor-secundaria);
            padding: 0.8rem 1rem;
            border-radius: 0 6px 6px 0;
            margin-bottom: 0.8rem;
        }
        .scenario-row strong { min-width: 160px; }
        .caption-box {
            position: relative;
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            white-space: pre-wrap;
            font-family: inherit;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .caption-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.8rem;
        }
        .caption-header h3 { color: var(--cor-escura); }
        .social-icon { font-size: 1.3rem; }
        .hidden { display: none; }
        .divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 1.5rem 0;
        }
        .tip-list {
            list-style: none;
            padding: 0;
        }
        .tip-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .tip-list li::before {
            content: '💡 ';
        }
        .copied-msg {
            display: none;
            color: #2e7d32;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body>
<div class="admin-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2>🔥 Admin Panel</h2>
        <nav>
            <a href="index.php">📊 Dashboard</a>
            <a href="produtos.php">🥩 Produtos</a>
            <a href="categorias.php">📁 Categorias</a>
            <a href="pedidos.php">📦 Pedidos</a>
            <a href="clientes.php">👥 Clientes</a>
            <a href="calculadora.php" class="active">💰 Calculadora</a>
            <a href="../logout.php">🚪 Sair</a>
        </nav>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-content">
        <h1 style="margin-bottom: 2rem;">💰 Calculadora de Lucro & Conteúdo para Redes Sociais</h1>

        <!-- FORMULÁRIO -->
        <div class="card">
            <h2>📋 Dados do Produto</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label for="nomeProduto">Nome do Produto *</label>
                    <input type="text" id="nomeProduto" placeholder="Ex: TV 55&quot; MOX 4K HDR" />
                </div>
                <div class="form-group">
                    <label for="marca">Marca / Modelo</label>
                    <input type="text" id="marca" placeholder="Ex: Samsung, LG, MOX..." />
                </div>
                <div class="form-group">
                    <label for="custoProduto">Custo do Produto (R$) *</label>
                    <input type="number" id="custoProduto" min="0" step="0.01" placeholder="1250.00" />
                </div>
                <div class="form-group">
                    <label for="margemDesejada">Margem de Lucro Desejada (%)</label>
                    <input type="number" id="margemDesejada" min="0" max="500" step="1" placeholder="56" value="56" />
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade de Venda</label>
                    <input type="text" id="cidade" placeholder="Ex: Campo Mourão" value="Campo Mourão" />
                </div>
                <div class="form-group">
                    <label for="taxaCartao">Taxa do Cartão / Parcelamento (%)</label>
                    <input type="number" id="taxaCartao" min="0" max="30" step="0.5" placeholder="8" value="8" />
                </div>
                <div class="form-group">
                    <label for="telefone">Seu Número de Contato</label>
                    <input type="text" id="telefone" placeholder="Ex: (44) 99999-9999" />
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="descricaoProduto">Destaques do Produto (um por linha)</label>
                    <textarea id="descricaoProduto" placeholder="Ex:&#10;Smart TV com Netflix e YouTube&#10;Resolução 4K Real&#10;Lacrada na caixa&#10;Entrega disponível"></textarea>
                </div>
            </div>
            <br/>
            <button class="btn btn-primary" onclick="calcular()">📊 Calcular & Gerar Conteúdo</button>
        </div>

        <!-- RESULTADOS (ocultos até calcular) -->
        <div id="resultados" class="hidden">

            <!-- ANÁLISE DE LUCRO -->
            <div class="card">
                <h2>📊 Análise de Lucro</h2>

                <div class="results-grid">
                    <div class="result-card grey">
                        <h4>Custo</h4>
                        <div class="val" id="res-custo">—</div>
                    </div>
                    <div class="result-card orange">
                        <h4>Preço de Venda Sugerido</h4>
                        <div class="val" id="res-venda">—</div>
                    </div>
                    <div class="result-card green">
                        <h4>Lucro Líquido</h4>
                        <div class="val" id="res-lucro">—</div>
                    </div>
                    <div class="result-card blue">
                        <h4>Margem de Lucro</h4>
                        <div class="val" id="res-margem">—</div>
                    </div>
                </div>

                <hr class="divider" />
                <h3 style="margin-bottom:1rem;">🎯 Cenários de Negociação</h3>
                <div id="cenarios"></div>

                <hr class="divider" />
                <h3 style="margin-bottom:1rem;">💡 Dicas de Venda</h3>
                <ul class="tip-list" id="dicas"></ul>
            </div>

            <!-- LEGENDAS PARA REDES SOCIAIS -->
            <div class="card">
                <h2>📱 Conteúdo para Redes Sociais</h2>
                <p style="color:#666; margin-bottom:1.5rem;">Clique em "Copiar" para copiar a legenda e colar diretamente na rede social.</p>

                <!-- FACEBOOK / MARKETPLACE -->
                <div class="caption-header">
                    <h3><span class="social-icon">👍</span> Facebook / Marketplace</h3>
                    <div>
                        <button class="btn btn-copy" onclick="copiar('facebook')">📋 Copiar</button>
                        <span class="copied-msg" id="copied-facebook">✅ Copiado!</span>
                    </div>
                </div>
                <div class="caption-box" id="caption-facebook"></div>

                <!-- INSTAGRAM -->
                <div class="caption-header">
                    <h3><span class="social-icon">📸</span> Instagram</h3>
                    <div>
                        <button class="btn btn-copy" onclick="copiar('instagram')">📋 Copiar</button>
                        <span class="copied-msg" id="copied-instagram">✅ Copiado!</span>
                    </div>
                </div>
                <div class="caption-box" id="caption-instagram"></div>

                <!-- TIKTOK -->
                <div class="caption-header">
                    <h3><span class="social-icon">🎵</span> TikTok</h3>
                    <div>
                        <button class="btn btn-copy" onclick="copiar('tiktok')">📋 Copiar</button>
                        <span class="copied-msg" id="copied-tiktok">✅ Copiado!</span>
                    </div>
                </div>
                <div class="caption-box" id="caption-tiktok"></div>

                <!-- WHATSAPP STATUS -->
                <div class="caption-header">
                    <h3><span class="social-icon">💬</span> WhatsApp (Status / Grupos)</h3>
                    <div>
                        <button class="btn btn-copy" onclick="copiar('whatsapp')">📋 Copiar</button>
                        <span class="copied-msg" id="copied-whatsapp">✅ Copiado!</span>
                    </div>
                </div>
                <div class="caption-box" id="caption-whatsapp"></div>
            </div>
        </div>
    </main>
</div>

<script>
function fmt(valor) {
    return 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function calcular() {
    const nome     = document.getElementById('nomeProduto').value.trim();
    const marca    = document.getElementById('marca').value.trim();
    const custo    = parseFloat(document.getElementById('custoProduto').value) || 0;
    const margem   = parseFloat(document.getElementById('margemDesejada').value) || 56;
    const taxaCartao = parseFloat(document.getElementById('taxaCartao').value) || 8;
    const cidade   = document.getElementById('cidade').value.trim() || 'Campo Mourão';
    const telefone = document.getElementById('telefone').value.trim() || '[SEU NÚMERO AQUI]';
    const descRaw  = document.getElementById('descricaoProduto').value.trim();

    if (!nome || custo <= 0) {
        alert('Preencha o nome e o custo do produto.');
        return;
    }

    const precoVenda  = custo * (1 + margem / 100);
    // Preço de negociação: aplica 85% da margem original (redução de 15% na margem)
    const precoNegoc  = custo * (1 + (margem * 0.85) / 100);
    // Preço no cartão: acrescenta a taxa configurada pelo usuário
    const taxaFator   = 1 + taxaCartao / 100;
    const precoCartao = precoVenda * taxaFator;
    // Lucro no cartão descontando a taxa de processamento do custo
    const lucroCartao = precoCartao - custo * taxaFator;
    const lucro       = precoVenda - custo;
    const margemReal  = (lucro / custo) * 100;

    // Preencher cards de resultado
    document.getElementById('res-custo').textContent   = fmt(custo);
    document.getElementById('res-venda').textContent   = fmt(precoVenda);
    document.getElementById('res-lucro').textContent   = fmt(lucro);
    document.getElementById('res-margem').textContent  = margemReal.toFixed(1) + '%';

    // Cenários
    const cenariosEl = document.getElementById('cenarios');
    cenariosEl.innerHTML = `
        <div class="scenario-row">
            <strong>💰 Venda à Vista:</strong>
            <span>${fmt(precoVenda)} &rarr; Lucro: ${fmt(lucro)}</span>
        </div>
        <div class="scenario-row">
            <strong>🤝 Com Desconto (negociação):</strong>
            <span>${fmt(precoNegoc)} &rarr; Lucro: ${fmt(precoNegoc - custo)}</span>
        </div>
        <div class="scenario-row">
            <strong>💳 Parcelado no Cartão (estimativa 10x):</strong>
            <span>${fmt(precoCartao)} &rarr; Lucro ap. taxa: ${fmt(lucroCartao)}</span>
        </div>
    `;

    // Dicas
    document.getElementById('dicas').innerHTML = `
        <li>Poste no <strong>Marketplace do Facebook</strong> de ${cidade} — maior alcance local.</li>
        <li>Faça um vídeo curto do produto ligado e poste no <strong>Status do WhatsApp</strong> e no <strong>Instagram Stories</strong>.</li>
        <li>Anuncie nos grupos de classificados e brique de ${cidade} no Facebook.</li>
        <li>Coloque o preço de ${fmt(precoCartao)} para ter margem de negociação e fechar em ${fmt(precoVenda)} à vista.</li>
        <li>Imagens "estilo de vida" (produto em uso) vendem mais que fotos de caixa.</li>
    `;

    // Montar destaques do produto
    const destaques = descRaw
        ? descRaw.split('\n').filter(l => l.trim()).map(l => '✅ ' + l.trim()).join('\n')
        : '✅ Produto em ótimo estado\n✅ Pronta entrega\n✅ Entrega disponível em ' + cidade;

    const nomeMarca = marca ? nome + ' ' + marca : nome;

    // LEGENDAS
    const captFacebook = `📺 ${nomeMarca.toUpperCase()} — LACRADO/NOVO!\n\n` +
        `Oportunidade para quem quer o melhor custo-benefício em ${cidade}!\n\n` +
        destaques + '\n\n' +
        `💰 Preço: ${fmt(precoVenda)} (ou ${fmt(precoCartao)} em até 10x no cartão — consulte taxas)\n` +
        `🚚 Entrega em ${cidade}!\n\n` +
        `📲 Interessados, chame no WhatsApp ou Direct: ${telefone}\n\n` +
        `#venda #${cidade.replace(/\s/g, '').toLowerCase()} #oportunidade #classificados`;

    const captInstagram = `📺 ${nomeMarca} — NOVA OPORTUNIDADE! ✨\n\n` +
        `${destaques}\n\n` +
        `💰 ${fmt(precoVenda)} à vista | Parcela no cartão!\n` +
        `📍 ${cidade}\n` +
        `📲 Chama no Direct ou WhatsApp: ${telefone}\n\n` +
        `#venda #${cidade.replace(/\s/g, '').toLowerCase()} #oportunidade #compra #oferta #marketplace`;

    const captTiktok = `🔥 ${nomeMarca} por apenas ${fmt(precoVenda)}! 🔥\n\n` +
        `📍 ${cidade} | 📲 ${telefone}\n\n` +
        destaques + '\n\n' +
        `Comenta ou manda mensagem pra fechar negócio! 👇\n\n` +
        `#venda #oferta #${cidade.replace(/\s/g, '').toLowerCase()} #oportunidade #comprar`;

    const captWhatsapp = `📺 *${nomeMarca}* — À VENDA!\n\n` +
        destaques + '\n\n' +
        `💰 *${fmt(precoVenda)}* à vista\n` +
        `📍 ${cidade}\n\n` +
        `Interessado? Manda mensagem! 👇\n${telefone}`;

    document.getElementById('caption-facebook').textContent  = captFacebook;
    document.getElementById('caption-instagram').textContent = captInstagram;
    document.getElementById('caption-tiktok').textContent    = captTiktok;
    document.getElementById('caption-whatsapp').textContent  = captWhatsapp;

    document.getElementById('resultados').classList.remove('hidden');
    document.getElementById('resultados').scrollIntoView({ behavior: 'smooth' });
}

function copiar(rede) {
    const el = document.getElementById('caption-' + rede);
    navigator.clipboard.writeText(el.textContent).then(() => {
        const msg = document.getElementById('copied-' + rede);
        msg.style.display = 'inline';
        setTimeout(() => { msg.style.display = 'none'; }, 2500);
    });
}
</script>
</body>
</html>
