<?php
session_start();

// Se já estiver logado, redirecionar para o painel
if (isset($_SESSION['user_tipo'])) {
  header("Location: dashboard_{$_SESSION['user_tipo']}.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'] ?? '';
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';
  $conf_senha = $_POST['conf_senha'] ?? '';
  $tipo = $_POST['tipo'] ?? 'estudante'; // valor padrão

  if ($senha !== $conf_senha) {
    $erro = "As senhas não conferem!";
  } else {
    // Carrega e decodifica JSON
    $jsonData = file_get_contents('../data/users.json');
    $users = json_decode($jsonData, true);

    // Verifica se email já existe
    foreach ($users as $u) {
      if ($u['email'] === $email) {
        $erro = "Email já cadastrado!";
        break;
      }
    }

    if (empty($erro)) {
      // Cria novo ID
      $novoId = count($users) + 1;

      // Cria array do novo usuário
      $novoUsuario = [
        "id" => $novoId,
        "nome" => $nome,
        "email" => $email,
        "senha" => $senha,
        "tipo" => $tipo
      ];

      // Adiciona ao array principal e salva em JSON
      $users[] = $novoUsuario;
      file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

      // Redireciona para login
      header("Location: login.php");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Registro - Sistema de Concursos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <?php include '../partials/header.php'; ?>

  <main class="container">
    <h2>Registro</h2>
    <?php if (!empty($erro)): ?>
      <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <label for="nome">Nome:</label>
      <input type="text" name="nome" id="nome" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" required>

      <label for="conf_senha">Confirmar Senha:</label>
      <input type="password" name="conf_senha" id="conf_senha" required>

      <!-- Seleção do tipo de usuário (somente gestor ou estudante) -->
      <label for="tipo">Tipo de Usuário:</label>
      <select name="tipo" id="tipo">
        <option value="estudante">Estudante</option>
      </select>

      <button type="submit">Cadastrar</button>
    </form>
  </main>

  <?php include '../partials/footer.php'; ?>
</body>

</html>

<script src="../js/script.js"></script>