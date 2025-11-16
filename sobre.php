<?php
session_start();
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Assados Delivery</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/public/assets/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/views/partials/header.php'; ?>
    
    <main class="container" style="padding: 40px 20px;">
        <section class="sobre-hero" style="text-align: center; margin-bottom: 50px;">
            <h1 style="font-size: 48px; color: #E63946; margin-bottom: 20px;">🔥 Assados Delivery</h1>
            <p style="font-size: 20px; color: #666; max-width: 800px; margin: 0 auto;">
                Carnes assadas de qualidade, preparadas com carinho e entregues fresquinhas no final de semana!
            </p>
        </section>
        
        <section class="sobre-conteudo" style="max-width: 900px; margin: 0 auto;">
            <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="color: #E63946; margin-bottom: 20px; font-size: 32px;">Nossa História</h2>
                <p style="color: #666; line-height: 1.8; font-size: 16px; margin-bottom: 15px;">
                    O <strong>Assados Delivery</strong> nasceu da paixão pela boa comida e pela tradição dos churrascos de fim de semana em família. 
                    Percebendo a necessidade de facilitar o acesso a carnes assadas de qualidade, especialmente preparadas, 
                    decidimos criar um serviço que leva até você os melhores cortes, temperados com receitas especiais e assados no ponto perfeito.
                </p>
                <p style="color: #666; line-height: 1.8; font-size: 16px; margin-bottom: 15px;">
                    Localizado em <strong>Campo Mourão - PR</strong>, atendemos toda a região com entregas aos sábados e domingos, 
                    garantindo que seu final de semana seja ainda mais especial, sem precisar se preocupar com o preparo das carnes.
                </p>
                <p style="color: #666; line-height: 1.8; font-size: 16px;">
                    Trabalhamos apenas com fornecedores certificados e carnes de primeira qualidade. Cada produto é cuidadosamente 
                    selecionado, temperado com ingredientes frescos e assado com todo o cuidado que sua família merece.
                </p>
            </div>
            
            <div class="diferenciais" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">🥩</div>
                    <h3 style="color: #E63946; margin-bottom: 10px;">Qualidade Premium</h3>
                    <p style="color: #666; font-size: 14px;">Carnes selecionadas dos melhores fornecedores da região</p>
                </div>
                
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">🔥</div>
                    <h3 style="color: #E63946; margin-bottom: 10px;">Tempero Especial</h3>
                    <p style="color: #666; font-size: 14px;">Receitas exclusivas que realçam o sabor natural da carne</p>
                </div>
                
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">🚚</div>
                    <h3 style="color: #E63946; margin-bottom: 10px;">Entrega Rápida</h3>
                    <p style="color: #666; font-size: 14px;">Entregamos fresquinho no horário que você escolher</p>
                </div>
            </div>
            
            <div style="background: linear-gradient(135deg, #E63946 0%, #C5303A 100%); color: white; padding: 40px; border-radius: 10px; text-align: center;">
                <h2 style="margin-bottom: 20px; font-size: 28px;">Nosso Compromisso</h2>
                <p style="font-size: 16px; line-height: 1.8; max-width: 700px; margin: 0 auto;">
                    Garantir que cada refeição seja uma experiência memorável. Trabalhamos com dedicação para 
                    oferecer produtos de excelência, atendimento diferenciado e a praticidade que você precisa 
                    para aproveitar seus momentos em família.
                </p>
            </div>
        </section>
    </main>
    
    <?php include __DIR__ . '/views/partials/footer.php'; ?>
</body>
</html>
