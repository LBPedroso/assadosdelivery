<?php
require_once 'config/config.php';
require_once 'config/helpers.php';
require_once 'controllers/AuthController.php';

$authController = new AuthController();

// Se já estiver logado, redirecionar
if ($authController->isCliente()) {
    header('Location: minha-conta.php');
    exit;
}

$erro = '';
$sucesso = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    $resultado = $authController->loginCliente($email, $senha);
    
    if ($resultado['success']) {
        header('Location: minha-conta.php');
        exit;
    } else {
        $erro = $resultado['message'];
    }
}

// Processar cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'cadastro') {
    $dados = [
        'nome' => $_POST['nome'] ?? '',
        'email' => $_POST['email'] ?? '',
        'telefone' => $_POST['telefone'] ?? '',
        'cpf' => $_POST['cpf'] ?? '',
        'senha' => $_POST['senha'] ?? '',
        'endereco_rua' => $_POST['endereco'] ?? '',
        'endereco_numero' => $_POST['numero'] ?? '',
        'endereco_complemento' => $_POST['complemento'] ?? '',
        'endereco_bairro' => $_POST['bairro'] ?? '',
        'endereco_cidade' => $_POST['cidade'] ?? 'Campo Mourão',
        'endereco_estado' => $_POST['estado'] ?? 'PR',
        'endereco_cep' => $_POST['cep'] ?? ''
    ];
    
    // Validações
    $erros = [];
    
    // Validar que pelo menos email OU telefone foi informado
    if (empty($dados['email']) && empty($dados['telefone'])) {
        $erros[] = 'Informe pelo menos um e-mail ou telefone para contato';
    }
    
    // Validar CPF
    if (!empty($dados['cpf']) && !validarCPF($dados['cpf'])) {
        $erros[] = 'CPF inválido';
    }
    
    // Validar email (apenas se informado)
    if (!empty($dados['email']) && !validarEmail($dados['email'])) {
        $erros[] = 'Email inválido';
    }
    
    // Validar telefone (apenas se informado)
    if (!empty($dados['telefone']) && !validarTelefone($dados['telefone'])) {
        $erros[] = 'Telefone inválido';
    }
    
    // Validar CEP
    if (!empty($dados['endereco_cep']) && !validarCEP($dados['endereco_cep'])) {
        $erros[] = 'CEP inválido';
    }
    
    // Confirmar senha
    if ($dados['senha'] !== ($_POST['confirmar_senha'] ?? '')) {
        $erros[] = 'As senhas não conferem';
    }
    
    if (!empty($erros)) {
        $erro = implode('<br>', $erros);
    } else {
        $resultado = $authController->registrarCliente($dados);
        
        if ($resultado['success']) {
            header('Location: minha-conta.php');
            exit;
        } else {
            $erro = $resultado['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        .login-container {
            max-width: 1000px;
            margin: 3rem auto;
            padding: 0 20px;
        }
        .login-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--cor-escura);
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--cor-primaria);
        }
        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #c33;
        }
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #3c3;
        }
        @media (max-width: 768px) {
            .login-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'views/partials/header.php'; ?>

    <div class="login-container">
        <h1 class="section-title">Acesse sua Conta</h1>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <div class="login-grid">
            <!-- LOGIN -->
            <div class="login-box">
                <h2 style="margin-bottom: 1.5rem; color: var(--cor-primaria);">Já sou Cliente</h2>
                
                <form method="POST">
                    <input type="hidden" name="acao" value="login">
                    
                    <div class="form-group">
                        <label for="login-email">Email ou Telefone</label>
                        <input type="text" id="login-email" name="email" required placeholder="email@exemplo.com ou (44) 99999-9999">
                    </div>
                    
                    <div class="form-group">
                        <label for="login-senha">Senha</label>
                        <input type="password" id="login-senha" name="senha" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                        Entrar
                    </button>
                </form>
            </div>

            <!-- CADASTRO -->
            <div class="login-box">
                <h2 style="margin-bottom: 1.5rem; color: var(--cor-secundaria);">Primeiro Pedido</h2>
                
                <form method="POST">
                    <input type="hidden" name="acao" value="cadastro">
                    
                    <div class="form-group">
                        <label for="cad-nome">Nome Completo *</label>
                        <input type="text" id="cad-nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-email">Email</label>
                        <input type="email" id="cad-email" name="email">
                        <small style="color: #666;">* Informe pelo menos email ou telefone</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-telefone">Telefone</label>
                        <input type="tel" id="cad-telefone" name="telefone" placeholder="(44) 99999-9999">
                        <small style="color: #666;">* Informe pelo menos email ou telefone</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-cpf">CPF</label>
                        <input type="text" id="cad-cpf" name="cpf" placeholder="000.000.000-00" maxlength="14">
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-senha">Senha *</label>
                        <input type="password" id="cad-senha" name="senha" minlength="6" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-confirmar">Confirmar Senha *</label>
                        <input type="password" id="cad-confirmar" name="confirmar_senha" minlength="6" required>
                    </div>

                    <h3 style="margin: 1.5rem 0 1rem; font-size: 1.1rem;">Endereço de Entrega</h3>
                    
                    <div class="form-group">
                        <label for="cad-endereco">Rua *</label>
                        <input type="text" id="cad-endereco" name="endereco" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="cad-numero">Número *</label>
                            <input type="text" id="cad-numero" name="numero" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cad-complemento">Complemento</label>
                            <input type="text" id="cad-complemento" name="complemento">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-bairro">Bairro *</label>
                        <input type="text" id="cad-bairro" name="bairro" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="cad-cep">CEP</label>
                        <input type="text" id="cad-cep" name="cep" placeholder="87300-000">
                    </div>
                    
                    <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 1rem;">
                        Criar Conta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'views/partials/footer.php'; ?>

    <script>
        // Função para validar CPF
        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos
            
            // Verifica se tem 11 dígitos
            if (cpf.length !== 11) return false;
            
            // Verifica se todos os dígitos são iguais (CPF inválido)
            if (/^(\d)\1{10}$/.test(cpf)) return false;
            
            // Validação do primeiro dígito verificador
            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let resto = 11 - (soma % 11);
            let digito1 = resto >= 10 ? 0 : resto;
            
            if (digito1 !== parseInt(cpf.charAt(9))) return false;
            
            // Validação do segundo dígito verificador
            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = 11 - (soma % 11);
            let digito2 = resto >= 10 ? 0 : resto;
            
            if (digito2 !== parseInt(cpf.charAt(10))) return false;
            
            return true;
        }

        // Máscara para telefone (44) 99999-9999
        document.getElementById('cad-telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            }
            
            e.target.value = value;
        });

        // Máscara para CPF 000.000.000-00 com validação
        const cpfInput = document.getElementById('cad-cpf');
        
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            
            e.target.value = value;
        });
        
        // Validar CPF ao sair do campo
        cpfInput.addEventListener('blur', function(e) {
            const cpf = e.target.value.replace(/\D/g, '');
            
            if (cpf.length === 0) return; // Campo vazio, não valida
            
            if (!validarCPF(cpf)) {
                e.target.style.borderColor = '#dc3545';
                e.target.style.backgroundColor = '#ffe6e6';
                
                // Remove mensagem anterior se existir
                const msgAnterior = e.target.parentElement.querySelector('.cpf-erro');
                if (msgAnterior) msgAnterior.remove();
                
                // Adiciona mensagem de erro
                const msgErro = document.createElement('small');
                msgErro.className = 'cpf-erro';
                msgErro.style.color = '#dc3545';
                msgErro.style.display = 'block';
                msgErro.style.marginTop = '5px';
                msgErro.textContent = '❌ CPF inválido';
                e.target.parentElement.appendChild(msgErro);
            } else {
                e.target.style.borderColor = '#28a745';
                e.target.style.backgroundColor = '#e6ffe6';
                
                // Remove mensagem de erro se existir
                const msgErro = e.target.parentElement.querySelector('.cpf-erro');
                if (msgErro) msgErro.remove();
                
                // Adiciona mensagem de sucesso
                const msgSucesso = document.createElement('small');
                msgSucesso.className = 'cpf-erro';
                msgSucesso.style.color = '#28a745';
                msgSucesso.style.display = 'block';
                msgSucesso.style.marginTop = '5px';
                msgSucesso.textContent = '✅ CPF válido';
                e.target.parentElement.appendChild(msgSucesso);
            }
        });
        
        // Limpar validação ao focar
        cpfInput.addEventListener('focus', function(e) {
            e.target.style.borderColor = '';
            e.target.style.backgroundColor = '';
            const msg = e.target.parentElement.querySelector('.cpf-erro');
            if (msg) msg.remove();
        });

        // Máscara para CEP 00000-000
        document.getElementById('cad-cep').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
        
        // Validar formulário antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const cpf = document.getElementById('cad-cpf').value.replace(/\D/g, '');
            
            if (cpf.length > 0 && !validarCPF(cpf)) {
                e.preventDefault();
                alert('❌ Por favor, insira um CPF válido antes de continuar.');
                document.getElementById('cad-cpf').focus();
                return false;
            }
        });
    </script>
</body>
</html>
