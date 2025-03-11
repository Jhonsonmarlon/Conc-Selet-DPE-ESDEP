<?php
session_start();

// Se já estiver logado, redirecionar para o painel (caso queira evitar re-cadastro)
if(isset($_SESSION['user_tipo'])) {
  header("Location: dashboard_{$_SESSION['user_tipo']}.php");
  exit;
}

// Verifica se houve envio do formulário
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'] ?? '';
  $email = $_POST['email'] ?? '';
  $senha = $_POST['senha'] ?? '';
  $conf_senha = $_POST['conf_senha'] ?? '';

  if($senha !== $conf_senha) {
    $erro = "As senhas não conferem!";
  } else {
    // Carrega e decodifica JSON
    $jsonData = file_get_contents('../data/users.json');
    $users = json_decode($jsonData, true);

    // Verifica se email já existe
    foreach($users as $u) {
      if($u['email'] === $email) {
        $erro = "Email já cadastrado!";
        break;
      }
    }

    if(empty($erro)) {
      // Cria novo ID (simplesmente o tamanho do array + 1)
      $novoId = count($users) + 1;

      // Forçar tipo "gestor"
      $novoUsuario = [
        "id" => $novoId,
        "nome" => $nome,
        "email" => $email,
        "senha" => $senha,
        "tipo" => "gestor" // Forçado!
      ];

      // Adiciona ao array principal e salva em JSON
      $users[] = $novoUsuario;
      file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));

      // Redireciona para login (ou direto para o dashboard, se preferir)
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
  <title>Registro de Gestor</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <?php include '../partials/header.php'; ?>

  <main class="container">
    <h2>Cadastrar Escola (Gestor)</h2>
    <?php if(!empty($erro)): ?>
      <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST" action="register_gestor.php">
      <label for="nome">Nome da Escola ou Responsável:</label>
      <input type="text" name="nome" id="nome" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" required>

      <label for="conf_senha">Confirmar Senha:</label>
      <input type="password" name="conf_senha" id="conf_senha" required>

      <button type="submit">Finalizar Cadastro</button>
    </form>
  </main>

  <?php include '../partials/footer.php'; ?>
</body>
</html>
