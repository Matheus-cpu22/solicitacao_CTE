<?php
    include("../login_cadastro/includes/protect.php")
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solicitar Documentação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/painel.css">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#solicitacao">Solicitar Documentação</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Transportadora Cidade Imperial</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link" href="/solicitacao_CTE/solicitacao/solicitacoes.php">Solicitações</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://localhost:5173/" target="_blank">Chat</a>
                        </li>
                        <?php
                            if($_SESSION["nvl_acesso"] == "adm") {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login_cadastro/cadastro.php" target="_blank">Cadastrar Funcionário</a>
                        </li>
                        <?php
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login_cadastro/logout.php" target="_self" title="Desconectar Conta">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTEÚDO -->
    <div id="solicitacao" class="container-custom">

        <div class="box-titulo">Protocolo de Faturamento</div>

        <h4 class="mb-4">Solicitações</h4>

        <form action="envioSolicitacao.php" method="POST" enctype="multipart/form-data">

            <!-- DADOS -->
            <div class="section-title">Dados da Solicitação</div>
            <table class="table table-bordered">
                <tr>
                    <th>Nome do Motorista</th>
                    <td><input type="text" name="nomeMotorista" class="form-control" required></td>
                    <th>Placa do Cavalo</th>
                    <td><input type="text" name="placaCavalo" class="form-control" required></td>
                </tr>
                <tr>
                    <th>Placa das Carretas</th>
                    <td><input type="text" name="placaCarretas" class="form-control" required></td>
                    <th>Destino da Carga</th>
                    <td><input type="text" name="destino" class="form-control" required></td>
                </tr>
                <tr>
                    <th>Valor da Operação</th>
                    <td><input type="text" name="valorOperacao" class="form-control" required></td>
                    <th>Tipo de Operação</th>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoOperacao" value="CT-e" checked>
                            <label class="form-check-label">CT-e</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoOperacao" value="MDF-e">
                            <label class="form-check-label">MDF-e</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipoOperacao" value="CT-e E MDF-e">
                            <label class="form-check-label">CT-e e MDF-e</label>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- OBS -->
            <div class="mb-4">
                <label class="form-label fw-bold">Observações</label>
                <textarea name="observacaoSolicitacao" class="form-control" rows="4" required></textarea>
            </div>

            <!-- ANEXOS -->

            <div class="text-center">
                <button type="submit" class="btn btn-custom px-5">SOLICITAR</button>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>