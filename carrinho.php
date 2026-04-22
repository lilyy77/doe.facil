<?php
session_start();
if (!isset($_SESSION['usuario_id'])) die("Faça login.");
$usuario_id = $_SESSION['usuario_id'];

$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) die("Erro: " . $conn->connect_error);

// ================= PRODUTOS DO CARRINHO =================
$sql = "SELECT c.id AS carrinho_id, p.id AS produto_id, p.nome, p.categoria, p.imagem, p.status 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id
        WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

// ================= ENDEREÇO DO USUÁRIO =================
$sql_end = "SELECT rua, numero, bairro, cidade, cep FROM usuarios WHERE id = ?";
$stmt2 = $conn->prepare($sql_end);
$stmt2->bind_param("i", $usuario_id);
$stmt2->execute();
$endereco = $stmt2->get_result()->fetch_assoc();

// ================= COLETAS (APENAS ADIÇÃO) =================
$sql_coleta = "SELECT cidade, bairro, dia, horario, tempo_estimado
               FROM coletas
               WHERE cidade = ? AND bairro = ?";
$stmt3 = $conn->prepare($sql_coleta);
$stmt3->bind_param("ss", $endereco['cidade'], $endereco['bairro']);
$stmt3->execute();
$coletas = $stmt3->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Meu Carrinho</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f0d6a4;
    color: #222;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
h2, h3, h4 { color: #8c0808; }

.btn-secondary {
    background-color: #8c0808;
    border: none;
    color: #fff;
}
.btn-secondary:hover {
    background-color: #f7c66c;
    color: #000;
}

#tabela-coletas { display: none; }
#endereco-doe { display: none; }
</style>
</head>

<body class="p-4">
<div class="container">

<h2 class="text-center">Meu Carrinho</h2>
<a href="javascript:history.back()" class="btn btn-secondary mb-3">← Voltar</a>

<!-- ================= PRODUTOS ================= -->
<div class="row">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="col-md-3 mb-3">
        <div class="card">
            <img src="<?= htmlspecialchars($row['imagem']) ?>" class="card-img-top">
            <div class="card-body text-center">
                <h6><?= htmlspecialchars($row['nome']) ?></h6>
                <button class="btn btn-danger btn-sm"
                        onclick="removerDoCarrinho(<?= $row['carrinho_id'] ?>)">
                    Remover
                </button>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<hr>

<!-- ================= TIPO DE ENTREGA ================= -->
<h3>Escolha o tipo de entrega</h3>

<div class="form-check">
    <input class="form-check-input" type="radio" name="tipo_entrega"
           id="domicilio" checked onclick="mostrarDomicilio()">
    <label class="form-check-label">Buscar em domicílio</label>
</div>

<div class="form-check mt-2">
    <input class="form-check-input" type="radio" name="tipo_entrega"
           id="doe" onclick="mostrarDoe()">
    <label class="form-check-label">Levar até o Doe Fácil</label>
</div>

<!-- ================= ENDEREÇO DOE FÁCIL (ORIGINAL, MANTIDO) ================= -->
<div id="endereco-doe" class="alert alert-info mt-3">
    <strong>📍 Endereço do Doe Fácil</strong><br>
    Rua da Solidariedade, nº 777<br>
    Bairro Esperança<br>
    Jacarezinho – PR<br>
    CEP: 86400-000
</div>

<!-- ================= TABELA DE COLETAS (ADICIONADA) ================= -->
<div id="tabela-coletas" class="mt-4">
<h4>📍 Coletas no seu bairro</h4>

<?php if($coletas->num_rows > 0): ?>
<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>Cidade</th>
<th>Bairro</th>
<th>Dia</th>
<th>Horário</th>
<th>Tempo estimado</th>
</tr>
</thead>
<tbody>
<?php while($c = $coletas->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($c['cidade']) ?></td>
<td><?= htmlspecialchars($c['bairro']) ?></td>
<td><?= htmlspecialchars($c['dia']) ?></td>
<td><?= htmlspecialchars($c['horario']) ?></td>
<td><?= htmlspecialchars($c['tempo_estimado']) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<div class="alert alert-warning">
⚠️ Ainda não há coleta cadastrada para o seu bairro.
</div>
<?php endif; ?>
</div>

<script>
function mostrarDomicilio(){
    document.getElementById('tabela-coletas').style.display = 'block';
    document.getElementById('endereco-doe').style.display = 'none';
}

function mostrarDoe(){
    document.getElementById('tabela-coletas').style.display = 'none';
    document.getElementById('endereco-doe').style.display = 'block';
}

// padrão: buscar em domicílio
document.addEventListener("DOMContentLoaded", mostrarDomicilio);

function removerDoCarrinho(id){
    fetch('remover_carrinho.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'carrinho_id='+id
    }).then(()=>location.reload());
}
</script>

</div>
</body>
</html>
