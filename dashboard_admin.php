<?php
session_start();

// Verifica se está logado e se é admin
if(!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include 'partials/header.php'; ?>

  <main class="container">
    <h2>Painel do Administrador</h2>
    <p>Bem-vindo(a), <?php echo $_SESSION['user_nome']; ?>!</p>

    <ul>
      <li><a href="#">Gerenciar Concursos/Seletivos</a></li>
      <li><a href="#">Definir Prazos e Documentos</a></li>
      <li><a href="#">Gerenciar Inscrições (Aprovar/Rejeitar)</a></li>
      <li><a href="#">Publicar Editais e Comunicados</a></li>
      <li><a href="#">Criar Novos Administradores</a></li>
    </ul>

    <a href="logout.php">Sair</a>
  </main>

  <?php include 'partials/footer.php'; ?>
</body>
</html>
