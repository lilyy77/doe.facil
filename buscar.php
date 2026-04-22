<?php
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro de conexão");
}

if (!isset($_GET['busca']) || empty($_GET['busca'])) {
    die("Nenhuma busca realizada");
}

$busca = $_GET['busca'];

$sql = "SELECT * FROM produtos
        WHERE nome LIKE ?
        OR categoria LIKE ?
        ORDER BY nome";

$stmt = $conn->prepare($sql);
$termo = "%$busca%";
$stmt->bind_param("ss", $termo, $termo);
$stmt->execute();
$result = $stmt->get_result();

echo "Chegou aqui!";
echo "<br>";
echo $_GET['busca'] ?? 'Nada buscado';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Resultados da busca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
  <h4>
    Resultados para:
    <strong><?= htmlspecialchars($busca) ?></strong>
  </h4>
  <hr>

  <div class="row g-4 mt-3">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($p = $result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card h-100">
            <img src="<?= htmlspecialchars($p['imagem']) ?>" class="card-img-top">
            <div class="card-body text-center">
              <h6><?= htmlspecialchars($p['nome']) ?></h6>
              <p class="small"><?= htmlspecialchars($p['categoria']) ?></p>
              <a href="#" class="btn btn-light btn-sm">Doar</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
<?php
/*
RESUMO DO CÓDIGO

- Conecta ao banco de dados MySQL (cadastro).
- Verifica se o termo de busca foi enviado pela URL (?busca=).
- Monta uma consulta para buscar produtos pelo nome ou categoria.
- Usa LIKE com prepared statement para segurança.
- Executa a busca e obtém os resultados.
- Exibe o termo pesquisado.
- Lista os produtos encontrados em cards com Bootstrap.
- Mostra mensagem caso nenhum produto seja encontrado.
*/
