<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body class="d-flex justify-content-center align-items-center vh-100">

<form action="login.php" method="POST" class="border p-4 rounded shadow bg-light" style="width: 300px;">
  <div class="mb-3 text-center">
    <label for="Loginusuario" class="form-label">Usuário</label>
    <input type="text" name="Loginusuario" class="form-control" id="Loginusuario" required>
  </div>
  <div class="mb-3 text-center">
    <label for="senhaUsuario" class="form-label">Senha</label>
    <input type="password" name="senhaUsuario" class="form-control" id="senhaUsuario" required>
  </div>
  <div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary">Entrar</button>
  </div>
</form>

  </body>
</html>
