<?php
$host = "localhost"; // Altere se necessário
$usuario = "root"; // Seu usuário do banco de dados
$senha = ""; // Sua senha do banco de dados
$banco = "transportadora"; // Nome do banco de dados
$porta = "3306";

$conexao = new mysqli($host, $usuario, $senha, $banco, $porta);

// Verifica se houve erro na conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
