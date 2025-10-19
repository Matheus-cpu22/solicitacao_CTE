<?php
$host = "localhost"; // Altere se necessário
$usuario = "root"; // Seu usuário do banco de dados
$senha = "aluno"; // Sua senha do banco de dados
$banco = "chat_transportadora"; // Nome do banco de dados
$porta = "3307";

$conexao = new mysqli($host, $usuario, $senha, $banco, $porta);

// Verifica se houve erro na conexão
if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
