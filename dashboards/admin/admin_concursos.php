<?php
session_start();

// Verifica se está logado e se é admin
if(!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'admin') {
  header("Location: ../control/login.php");
  exit;
}

$concursosFile = '../data/concursos.json';
$concursos = [];

// Carrega o JSON de concursos
if(file_exists($concursosFile)) {
  $jsonData = file_get_contents($concursosFile);
  $concursos = json_decode($jsonData, true);
  if(!is_array($concursos)) {
    $concursos = [];
  }
}

// Se houve solicitação de criar ou editar
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;
  $titulo = $_POST['titulo'] ?? '';
  $descricao = $_POST['descricao'] ?? '';
  $publicoAlvo = $_POST['publicoAlvo'] ?? '';
  $dataInicio = $_POST['dataInicio'] ?? '';
  $dataFim = $_POST['dataFim'] ?? '';

  if(empty($id)) {
    // Criar novo concurso
    $novoId = count($concursos) > 0 ? max(array_column($concursos, 'id')) + 1 : 1;

    $novoConcurso = [
      'id' => $novoId,
      'titulo' => $titulo,
      'descricao' => $descricao,
      'publicoAlvo' => $publicoAlvo,
      'dataInicio' => $dataInicio,
      'dataFim' => $dataFim
    ];

    $concursos[] = $novoConcurso;
  } else {
    // Editar concurso existente
    foreach($concursos as &$c) {
      if($c['id'] == $id) {
        $c['titulo'] = $titulo;
        $c['descricao'] = $descricao;
        $c['publicoAlvo'] = $publicoAlvo;
        $c['dataInicio'] = $dataInicio;
        $c['dataFim'] = $dataFim;
        break;
      }
    }
  }

  // Salva no JSON
  file_put_contents($concursosFile, json_encode($concursos, JSON_PRETTY_PRINT));

  // Redirecionar para evitar re-envio de formulário
  header("Location: admin_concursos.php");
  exit;
}

// Se houver solicitação de excluir (GET com ?excluir=ID)
if(isset($_GET['excluir'])) {
  $excluirId = $_GET['excluir'];
  $concursos = array_filter($concursos, function($c) use ($excluirId) {
    return $c['id'] != $excluirId;
  });
  // Reorganiza o array
  $concursos = array_values($concursos);

  file_put_contents($concursosFile, json_encode($concursos, JSON_PRETTY_PRINT));

  header("Location: admin_concursos.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciar Concursos - Admin</title>
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<?php include '../../partials/header.php'; ?>

<main class="container">
  <h2>Gerenciar Concursos/Seletivos</h2>

  <!-- Lista de Concursos -->
  <h3>Concursos Cadastrados</h3>
  <?php if(!empty($concursos)): ?>
    <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%;">
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Público</th>
          <th>Período</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($concursos as $c): ?>
        <tr>
          <td><?php echo $c['id']; ?></td>
          <td><?php echo htmlspecialchars($c['titulo']); ?></td>
          <td><?php echo htmlspecialchars($c['publicoAlvo']); ?></td>
          <td><?php echo $c['dataInicio'] . ' a ' . $c['dataFim']; ?></td>
          <td>
            <!-- Link para editar, passando ID via GET -->
            <a href="admin_concursos.php?edit=<?php echo $c['id']; ?>">Editar</a> |
            <a href="admin_concursos.php?excluir=<?php echo $c['id']; ?>" 
               onclick="return confirm('Tem certeza que deseja excluir este concurso?');">
               Excluir
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>Nenhum concurso cadastrado.</p>
  <?php endif; ?>

  <hr />

  <?php
  // Se estivermos editando, carrega dados do concurso
  $editConcurso = null;
  if(isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    foreach($concursos as $c) {
      if($c['id'] == $editId) {
        $editConcurso = $c;
        break;
      }
    }
  }
  ?>

  <!-- Formulário de Criação/Edição -->
  <h3><?php echo $editConcurso ? 'Editar Concurso' : 'Criar Novo Concurso'; ?></h3>
  <form method="POST" action="admin_concursos.php">
    <!-- Se estivermos editando, enviamos o ID no campo hidden -->
    <?php if($editConcurso): ?>
      <input type="hidden" name="id" value="<?php echo $editConcurso['id']; ?>">
    <?php endif; ?>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required
           value="<?php echo $editConcurso ? htmlspecialchars($editConcurso['titulo']) : ''; ?>">

    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao" rows="3" required><?php 
      echo $editConcurso ? htmlspecialchars($editConcurso['descricao']) : ''; 
    ?></textarea>

    <label for="publicoAlvo">Público Alvo:</label>
    <select id="publicoAlvo" name="publicoAlvo" required>
      <option value="Escolas" 
        <?php echo ($editConcurso && $editConcurso['publicoAlvo'] === 'Escolas') ? 'selected' : ''; ?>>
        Escolas
      </option>
      <option value="Estudantes" 
        <?php echo ($editConcurso && $editConcurso['publicoAlvo'] === 'Estudantes') ? 'selected' : ''; ?>>
        Estudantes
      </option>
      <!-- Você pode adicionar outras categorias... -->
    </select>

    <label for="dataInicio">Data Início:</label>
    <input type="date" id="dataInicio" name="dataInicio" required
           value="<?php echo $editConcurso ? $editConcurso['dataInicio'] : ''; ?>">

    <label for="dataFim">Data Fim:</label>
    <input type="date" id="dataFim" name="dataFim" required
           value="<?php echo $editConcurso ? $editConcurso['dataFim'] : ''; ?>">

    <br>
    <button type="submit"><?php echo $editConcurso ? 'Atualizar' : 'Criar'; ?></button>
  </form>

</main>

<?php include '../../partials/footer.php'; ?>
</body>
</html>
