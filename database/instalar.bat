@echo off
REM Script para executar os arquivos SQL automaticamente
REM Assados Delivery - Setup do Banco de Dados

echo ================================================
echo   ASSADOS DELIVERY - INSTALACAO DO BANCO
echo ================================================
echo.

REM Caminho do MySQL no XAMPP
set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe

REM Verificar se o MySQL existe
if not exist "%MYSQL_PATH%" (
    echo ERRO: MySQL nao encontrado em %MYSQL_PATH%
    echo Verifique se o XAMPP esta instalado corretamente.
    pause
    exit
)

echo [1/2] Criando banco de dados e tabelas...
"%MYSQL_PATH%" -u root < schema.sql

if %ERRORLEVEL% NEQ 0 (
    echo ERRO ao executar schema.sql
    pause
    exit
)

echo [2/2] Inserindo dados iniciais...
"%MYSQL_PATH%" -u root assados_delivery < seed.sql

if %ERRORLEVEL% NEQ 0 (
    echo ERRO ao executar seed.sql
    pause
    exit
)

echo.
echo ================================================
echo   INSTALACAO CONCLUIDA COM SUCESSO!
echo ================================================
echo.
echo Banco: assados_delivery
echo Usuario admin: admin@assados.com / admin123
echo Cliente teste: cliente@teste.com / 123456
echo.
pause
