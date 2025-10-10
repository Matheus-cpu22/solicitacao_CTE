<?php
session_start();
require_once 'config.php';

// Verificar se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $lembrar = isset($_POST['lembrar']);
    
    // Validação básica
    if (empty($email) || empty($senha)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Por favor, insira um e-mail válido.';
    } else {
        try {
            // Buscar usuário no banco de dados
            $stmt = $pdo->prepare("SELECT id, nome, email, senha, ativo FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario) {
                // Verificar se a conta está ativa
                if (!$usuario['ativo']) {
                    $error_message = 'Sua conta está desativada. Entre em contato com o suporte.';
                } elseif (password_verify($senha, $usuario['senha'])) {
                    // Login bem-sucedido
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_name'] = $usuario['nome'];
                    $_SESSION['user_email'] = $usuario['email'];
                    
                    // Configurar cookie "Lembrar de mim" se solicitado
                    if ($lembrar) {
                        $token = bin2hex(random_bytes(32));
                        $expires = time() + (30 * 24 * 60 * 60); // 30 dias
                        
                        // Salvar token no banco de dados
                        $stmt = $pdo->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                        $stmt->execute([$usuario['id'], hash('sha256', $token), date('Y-m-d H:i:s', $expires)]);
                        
                        // Definir cookie
                        setcookie('remember_token', $token, $expires, '/', '', false, true);
                    }
                    
                    // Registrar login no log
                    $stmt = $pdo->prepare("INSERT INTO login_logs (user_id, ip_address, user_agent, login_time) VALUES (?, ?, ?, NOW())");
                    $stmt->execute([$usuario['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
                    
                    // Redirecionar para dashboard
                    header('Location: dashboard.php');
                    exit();
                } else {
                    $error_message = 'E-mail ou senha incorretos.';
                }
            } else {
                $error_message = 'E-mail ou senha incorretos.';
            }
        } catch (PDOException $e) {
            error_log("Erro no login: " . $e->getMessage());
            $error_message = 'Ocorreu um erro interno. Tente novamente mais tarde.';
        }
    }
}

// Verificar token "Lembrar de mim"
if (isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
    try {
        $token_hash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $pdo->prepare("SELECT u.id, u.nome, u.email FROM usuarios u 
                              JOIN remember_tokens rt ON u.id = rt.user_id 
                              WHERE rt.token = ? AND rt.expires_at > NOW() AND u.ativo = 1");
        $stmt->execute([$token_hash]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_email'] = $usuario['email'];
            header('Location: dashboard.php');
            exit();
        } else {
            // Token inválido, remover cookie
            setcookie('remember_token', '', time() - 3600, '/');
        }
    } catch (PDOException $e) {
        error_log("Erro ao verificar token: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>Bem-vindo de volta!</h2>
                <p>Faça login em sua conta</p>
            </div>
            
            <?php if ($error_message): ?>
                <div class="error-message" style="background: #fee; color: #c33; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="success-message" style="background: #efe; color: #3c3; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form class="login-form" action="login.php" method="POST">
                <div class="form-group">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Seu e-mail" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="senha" placeholder="Sua senha" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="lembrar" <?php echo isset($_POST['lembrar']) ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                        Lembrar de mim
                    </label>
                    <a href="forgot-password.php" class="forgot-password">Esqueceu a senha?</a>
                </div>
                
                <button type="submit" class="login-btn">
                    <span>Entrar</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
                
                <div class="divider">
                    <span>ou</span>
                </div>
                
                <div class="social-login">
                    <button type="button" class="social-btn google" onclick="loginWithGoogle()">
                        <i class="fab fa-google"></i>
                        Continuar com Google
                    </button>
                    <button type="button" class="social-btn facebook" onclick="loginWithFacebook()">
                        <i class="fab fa-facebook-f"></i>
                        Continuar com Facebook
                    </button>
                </div>
                
                <div class="signup-link">
                    <p>Não tem uma conta? <a href="register.php">Cadastre-se aqui</a></p>
                </div>
            </form>
        </div>
        
        <div class="background-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="senha"]');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        function loginWithGoogle() {
            // Implementar integração com Google OAuth
            alert('Funcionalidade de login com Google será implementada em breve!');
        }
        
        function loginWithFacebook() {
            // Implementar integração com Facebook OAuth
            alert('Funcionalidade de login com Facebook será implementada em breve!');
        }
        
        // Animação de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const loginBox = document.querySelector('.login-box');
            loginBox.style.opacity = '0';
            loginBox.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                loginBox.style.transition = 'all 0.6s ease';
                loginBox.style.opacity = '1';
                loginBox.style.transform = 'translateY(0)';
            }, 100);
        });
        
        // Auto-hide messages
        setTimeout(function() {
            const messages = document.querySelectorAll('.error-message, .success-message');
            messages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s ease';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>