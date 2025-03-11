<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciamento de Concursos e Seletivos</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <?php include 'partials/header.php'; ?>

  <main class="container">
    <h1>Bem-vindo ao Sistema de Gerenciamento de Concursos e Seletivos</h1>
    <p>Este sistema permite a administração completa de concursos e processos seletivos...</p>

    <div class="home-links">
      <a href="control/login.php">Login</a>
      <a href="control/register.php">Registro</a>
      <a href="pages/concursos.php">Concursos e Seletivos Ativos</a>
      <a href="#">Informações Gerais</a>
    </div>
  </main>

  <?php include 'partials/footer.php'; ?>
</body>
</html>
