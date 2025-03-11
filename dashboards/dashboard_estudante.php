<?php
session_start();

// Verifica se está logado e se é estudante
if(!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'estudante') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Estudante</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <?php include '../partials/header.php'; ?>

  <main class="container">
    <h2>Painel do Estudante</h2>
    <p>Bem-vindo(a), <?php echo $_SESSION['user_nome']; ?>!</p>

    <ul>
      <li><a href="#">Seletivos Disponíveis</a></li>
      <li><a href="#">Minhas Inscrições</a></li>
      <li><a href="#">Editar Documentos</a></li>
    </ul>

    <a href="../control/logout.php">Sair</a>
  </main>

  <?php include '../partials/footer.php'; ?>
</body>
</html>
