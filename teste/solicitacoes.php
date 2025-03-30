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
    <title>Solicitações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-pendente {
            color: red;
            font-weight: bold;
        }

        .status-concluido {
            color: green;
            font-weight: bold;
        }

        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }

        .download-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Solicitações Pendentes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Motorista</th>
                    <th>Placa</th>
                    <th>Rota</th>
                    <th>Observação</th>
                    <th>Status</th>
                    <th>Anexo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultPendentes->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idSolicitacao'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['rotaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td class="status-pendente">Pendente</td>
                    <td>
                        <?php if (!empty($row['arquivoPDF'])) { ?>
                            <a class="download-btn" href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { echo "Nenhum"; } ?>
                    </td>
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
                    <th>Placa</th>
                    <th>Rota</th>
                    <th>Observação</th>
                    <th>Data de Conclusão</th>
                    <th>Status</th>
                    <th>Anexo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultConcluidas->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['idSolicitacao'] ?></td>
                    <td><?= htmlspecialchars($row['nomeMotorista']) ?></td>
                    <td><?= htmlspecialchars($row['placaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['rotaCaminhao']) ?></td>
                    <td><?= htmlspecialchars($row['observacaoSolicitacao']) ?></td>
                    <td><?= date("d/m/Y", strtotime($row['dataConclusao'] ?? '')) ?></td>
                    <td class="status-concluido">Concluído</td>
                    <td>
                        <?php if (!empty($row['arquivoPDF'])) { ?>
                            <a class="download-btn" href="uploads/<?= $row['arquivoPDF'] ?>" target="_blank">Baixar</a>
                        <?php } else { echo "Nenhum"; } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conexao->close(); ?>
