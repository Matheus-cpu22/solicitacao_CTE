<?php
include("conexao.php");

// Obtém as solicitações agrupadas por status
$sqlPendentes = "SELECT * FROM tblsolicitacao WHERE status = 'Pendente'";
$sqlConcluidas = "SELECT * FROM tblsolicitacao WHERE status = 'Concluído'";

$resultPendentes = $conexao->query($sqlPendentes);
$resultConcluidas = $conexao->query($sqlConcluidas);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/solicitacoes.css">
    <title>Solicitações</title>
</head>

<body>
    <div class="container">
        <h2>Solicitações Pendentes</h2>
        <div class="table-container"></div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Motorista</th>
                    <th>Placa Cavalo</th>
                    <th>Placa Carreta</th>
                    <th>Destino</th>
                    <th>Valor da Operação</th>
                    <th>Tipo de Operação</th>
                    <th>Observação</th>
                    <th>Observação Extra</th>
                    <th>Status</th>
                    <th>Anexo</th>
                    <th>Data da Solicitação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPendentes->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idSolicitacao'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCavalo']) ?></td>
                    <td><?= htmlspecialchars($row['placaCarretas']) ?></td>
                    <td><?= htmlspecialchars($row['destino']) ?></td>
                    <td>R$ <?= htmlspecialchars($row['valorOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['tipoOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoExtra1']) ?></td>
                    <td class="status-pendente">Pendente</td>
                    <td>
                        <?php if (!empty($row['arquivoPDF'])) { ?>
                            <a class="download-btn" href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { echo "Nenhum"; } ?>
                    </td>
                    <td><?= date("d/m/Y H:i:s", strtotime($row['dataSolicitacao'] ?? '')) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Solicitações Concluídas</h2>
        <table>
            <thead>
                <tr>
                <th>ID</th>
                    <th>Motorista</th>
                    <th>Placa Cavalo</th>
                    <th>Placa Carreta</th>
                    <th>Destino</th>
                    <th>Valor da Operação</th>
                    <th>Tipo de Operação</th>
                    <th>Observação</th>
                    <th>Observação Extra</th>
                    <th>Status</th>
                    <th>Anexo</th>
                    <th>Data da Solicitação</th>
                    <th>Data da Conclusão</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultConcluidas->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idSolicitacao'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCavalo']) ?></td>
                    <td><?= htmlspecialchars($row['placaCarretas']) ?></td>
                    <td><?= htmlspecialchars($row['destino']) ?></td>
                    <td>R$ <?= htmlspecialchars($row['valorOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['tipoOperacao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoExtra1']) ?></td>
                    <td class="status-concluido">Concluído</td>
                    <td>
                        <?php if (!empty($row['arquivoPDF'])) { ?>
                            <a class="download-btn" href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { echo "Nenhum"; } ?>
                    </td>
                    <td><?= date("d/m/Y H:i:s", strtotime($row['dataSolicitacao'] ?? '')) ?></td>
                    <td><?= date("d/m/Y H:i:s", strtotime($row['dataConclusao'] ?? '')) ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>

<?php $conexao->close(); ?>
