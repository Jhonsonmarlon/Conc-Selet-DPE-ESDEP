<?php
session_start();

// Verifica se está logado e se é gestor
if(!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'gestor') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Gestor</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <?php include '../partials/header.php'; ?>

  <main class="container">
    <h2>Painel do Gestor</h2>
    <p>Bem-vindo(a), <?php echo $_SESSION['user_nome']; ?>!</p>

    <ul>
      <li><a href="#">Cadastrar Escola</a></li>
      <li><a href="#">Adicionar Professores</a></li>
      <li><a href="#">Adicionar Alunos</a></li>
      <li><a href="#">Gerenciar Cadastros</a></li>
      <li><a href="#">Status das Inscrições</a></li>
    </ul>

    <a href="../control/logout.php">Sair</a>
  </main>

  <?php include '../partials/footer.php'; ?>
</body>
</html>
