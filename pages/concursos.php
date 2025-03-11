<?php
session_start();

// Carrega concursos
$concursosFile = '../data/concursos.json';
$concursos = [];
if(file_exists($concursosFile)) {
  $jsonData = file_get_contents($concursosFile);
  $concursos = json_decode($jsonData, true);
  if(!is_array($concursos)) {
    $concursos = [];
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Concursos e Seletivos Ativos</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <?php include '../partials/header.php'; ?>

  <main class="container">
    <h2>Concursos e Seletivos Ativos</h2>
    <p>Aqui podem ser listados os concursos e seletivos disponíveis.</p>

    <?php if(!empty($concursos)): ?>
      <?php foreach($concursos as $c): ?>
      <section class="card-concurso">
        <h3><?php echo htmlspecialchars($c['titulo']); ?></h3>
        <p><?php echo htmlspecialchars($c['descricao']); ?></p>
        <p><strong>Público Alvo:</strong> <?php echo htmlspecialchars($c['publicoAlvo']); ?></p>
        <p><strong>Período:</strong> <?php echo $c['dataInicio'] . ' a ' . $c['dataFim']; ?></p>

        <?php if($c['publicoAlvo'] === 'Escolas'): ?>
          <a href="../control/register_gestor.php" class="btn-gestor">Cadastrar Escola</a>
        <?php endif; ?>
        
        <!-- Caso queira outro botão para “Inscrever-se” se for Estudante, etc. -->
      </section>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Nenhum concurso disponível no momento.</p>
    <?php endif; ?>

  </main>

  <?php include '../partials/footer.php'; ?>
</body>
</html>
