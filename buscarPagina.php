<?php
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

if (!isset($_GET['busca']) || empty($_GET['busca'])) {
    die("Busca não informada.");
}

$busca = $_GET['busca'];

// Busca produtos pelo nome
$stmt = $conn->prepare("SELECT * FROM produtos WHERE nome LIKE ?");
$termo = "%$busca%";
$stmt->bind_param("s", $termo);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Resultados para <?= htmlspecialchars($busca) ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f5d7a0;
      font-family: 'Segoe UI';
    }

    .banner-categoria {
      background: linear-gradient(135deg, #f7c66c, #8c0808);
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      margin-bottom: 40px;
      border-radius: 0 0 30px 30px;
    }

    .banner-categoria h2 {
      font-weight: 700;
    }

    .produto-card {
      border-radius: 18px;
      overflow: hidden;
      transition: all 0.3s ease;
      border: none;
    }

    .produto-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .produto-card img {
      height: 190px;
      object-fit: cover;
    }

    .produto-card .card-body {
      background: #fff;
    }

    .btn-doar {
      background: #b40a0a;
      color: #fff;
      border-radius: 30px;
      padding: 6px 18px;
      font-size: 0.85rem;
      border: none;
    }

    .btn-doar:hover {
      background: #8c0808;
      color: #fff;
    }

    .btn-voltar {
      border-radius: 30px;
      padding: 6px 18px;
    }

    .msg-vazia {
      font-size: 1.1rem;
      color: #666;
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>

<body>

<!-- BANNER -->
<div class="banner-categoria">
  <h2>Resultados para: <?= htmlspecialchars($busca) ?></h2>
  <p>Produtos encontrados relacionados à sua busca</p>
</div>

<div class="container">

  <div class="text-center mb-4">
    <a href="viva.php" class="btn btn-outline-secondary btn-voltar">← Voltar</a>
  </div>

  <div class="row g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($p = $result->fetch_assoc()): ?>
        <div class="col-12 col-md-4 col-lg-3">
          <div class="card produto-card h-100">
            <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
            <div class="card-body text-center">
              <h6 class="fw-bold"><?= htmlspecialchars($p['nome']) ?></h6>
              <p class="small text-muted"><?= htmlspecialchars($p['categoria']) ?></p>
              <a href="#" class="btn btn-doar btn-sm">Doar</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="msg-vazia">Nenhum produto encontrado para esta busca.</p>
    <?php endif; ?>
  </div>

</div>

</body>
</html>
<?php
/*
RESUMO DO CÓDIGO

- Conecta ao banco de dados MySQL (cadastro).
- Verifica se o termo de busca foi informado pela URL.
- Busca produtos no banco pelo nome usando LIKE.
- Executa a consulta com prepared statement.
- Exibe um banner com o termo pesquisado.
- Lista os produtos encontrados em cards estilizados.
- Mostra mensagem caso não haja resultados.
- Possui botão para voltar à página viva.php.
*/
