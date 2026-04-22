<?php
session_start();

/* ======================
   CONEXÃO COM O BANCO
====================== */
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados.");
}

/* ======================
   RECEBER CATEGORIA (GET)
====================== */

$categoria = $_GET['cat'] ?? '';

if ($categoria === '') {
    die("Categoria não informada.");
}


/* ======================
   BUSCAR PRODUTOS DA CATEGORIA
====================== */
$stmt = $conn->prepare(
    "SELECT * FROM produtos
     WHERE categoria = ?
     ORDER BY FIELD(status,'urgente','ativo','inativo'), nome"
);
$stmt->bind_param("s", $categoria);
$stmt->execute();
$result = $stmt->get_result();
$produtos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($categoria) ?></title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #121212;
    color: #fff;
    font-family: Arial, sans-serif;
}

h3 {
    color: #e6b3ff;
}

.card {
    background: #2a0d3f;
    border: 2px solid #6a0dad;
    border-radius: 14px;
    color: #fff;
    transition: transform .2s;
}

.card:hover {
    transform: scale(1.02);
}

img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #151414ff;
}

.status-urgente { color: #ff4d4d; font-weight: bold; }
.status-ativo   { color: #7CFC00; font-weight: bold; }
.status-inativo { color: #aaa; }

.btn-secondary {
    background: #333;
    border: none;
}
.btn-secondary:hover {
    background: #6a0dad;
}
</style>
</head>

<body class="p-4">

<!-- BOTÃO VOLTAR -->
<a href="editar_viva.php" class="btn btn-secondary mb-4">← Voltar</a>

<h3 class="mb-4"><?= htmlspecialchars($categoria) ?></h3>

<div class="row g-3">

<?php if (empty($produtos)): ?>
    <p>Nenhum produto cadastrado nesta categoria.</p>
<?php endif; ?>

<?php foreach ($produtos as $p): ?>

<?php
$status = in_array($p['status'], ['urgente','ativo','inativo'])
          ? $p['status']
          : 'inativo';
?>

<div class="col-md-3 col-sm-6">
    <div class="card p-3 h-100">
        <img src="../<?= htmlspecialchars($p['imagem']) ?>"
             onerror="this.src='../uploads/sem-imagem.png'"
             class="mb-2">

        <h6><?= htmlspecialchars($p['nome']) ?></h6>

        <small class="status-<?= $status ?>">
            Status: <?= ucfirst($status) ?>
        </small>
    </div>
</div>

<?php endforeach; ?>

</div>

</body>
</html>
<?php
/*
RESUMO DO CÓDIGO (produtos_por_categoria.php)

- Inicia a sessão do usuário.
- Realiza a conexão com o banco de dados "cadastro".
- Recebe a categoria pela URL (GET).
- Valida se a categoria foi informada.
- Busca todos os produtos da categoria selecionada.
- Ordena os produtos pelo status (urgente, ativo, inativo) e pelo nome.
- Exibe os produtos em cards usando Bootstrap.
- Mostra imagem, nome e status do produto.
- Aplica estilos visuais diferentes conforme o status.
- Possui botão para voltar à página anterior.
*/
?>
