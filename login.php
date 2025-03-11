<?php
session_start();

// Se já estiver logado, redirecionar para painel (dependendo do tipo de usuário)
if(isset($_SESSION['user_tipo'])) {
  // Redireciona direto para o painel
  header("Location: dashboard_{$_SESSION['user_tipo']}.php");
  exit;
}

// Se houve envio do formulário
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';

  // Carrega arquivo JSON de usuários
  $jsonData = file_get_contents('data/users.json');
  $users = json_decode($jsonData, true);

  // Verifica usuário e senha
  foreach($users as $user) {
    if($user['email'] === $email && $user['senha'] === $senha) {
      // Encontrei o usuário
      $_SESSION['user_id']   = $user['id'];
      $_SESSION['user_nome'] = $user['nome'];
      $_SESSION['user_tipo'] = $user['tipo']; // 'gestor', 'estudante', 'admin', etc.

      // Redireciona para o dashboard específico
      switch($user['tipo']) {
        case 'gestor':
          header('Location: dashboard_gestor.php');
          break;
        case 'estudante':
          header('Location: dashboard_estudante.php');
          break;
        case 'admin':
          header('Location: dashboard_admin.php');
          break;
        default:
          header('Location: index.php');
      }
      exit;
    }
  }
  // Se chegou aqui, não encontrou nenhum usuário compatível
  $erro = "Email ou senha inválidos!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - Sistema de Concursos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <?php include 'partials/header.php'; ?>

  <main class="container">
    <h2>Login</h2>
    <?php if(!empty($erro)): ?>
      <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" required>

      <button type="submit">Entrar</button>
    </form>
  </main>

  <?php include 'partials/footer.php'; ?>
</body>
</html>
