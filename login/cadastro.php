<?php
include("conexao.php");

$usuario = "Vinicius";
$senha = "0396"; // Senha em texto puro    

// Criptografar a senha antes de salvar no banco
$hash_senha = password_hash($senha, PASSWORD_DEFAULT);

// Usar Prepared Statement para segurança contra SQL Injection
$sql = "INSERT INTO tblLogin (LoginUsuario, senhaUsuario) VALUES (?, ?)";
$stmt = $conexao->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ss", $usuario, $hash_senha);
    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Erro na preparação da query: " . $conexao->error;
}

$conexao->close();
?>
