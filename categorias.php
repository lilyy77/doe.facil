<?php
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Erro de conexão</div>");
}

$msg = "";

/* ======================
   BUSCAR CATEGORIAS
====================== */
$result = $conn->query("SELECT DISTINCT categoria FROM produtos ORDER BY categoria");
$categorias = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

/* ======================
   SALVAR CATEGORIA (EDITAR)
====================== */
if (isset($_POST['salvar_categoria'])) {

    $categoria_antiga = trim($_POST['categoria_antiga']);
    $categoria_nova = trim($_POST['categoria_nova']);

    if ($categoria_nova === '') {
        $msg = "<div class='alert alert-danger'>Erro: o nome da categoria não pode ficar vazio.</div>";
    } else {

        $stmt = $conn->prepare(
            "UPDATE produtos SET categoria=? WHERE categoria=?"
        );
        $stmt->bind_param("ss", $categoria_nova, $categoria_antiga);
        $stmt->execute();
        $stmt->close();

        $msg = "<div class='alert alert-success'>
                    Categoria atualizada com sucesso! 
                    Todos os produtos foram atualizados.
                </div>";
    }
}

/* ======================
   EXCLUIR CATEGORIA
====================== */
if (isset($_POST['excluir_categoria'])) {

    $categoria = $_POST['categoria'];

    $stmt = $conn->prepare("DELETE FROM produtos WHERE categoria=?");
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $stmt->close();

    $msg = "<div class='alert alert-success'>
                Categoria e todos os produtos relacionados foram excluídos.
            </div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerenciar Categorias</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<h2>Gerenciamento de Categorias</h2>

<a href="editar_viva.php" class="btn btn-secondary mb-3"><= Voltar</a>

<!-- MENSAGENS -->
<?= $msg ?>

<div class="card p-3">
<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
<th>Categoria</th>
<th>Ações</th>
</tr>
</thead>
<tbody>

<?php if (count($categorias) == 0): ?>
<tr>
<td colspan="2" class="text-center">Nenhuma categoria encontrada.</td>
</tr>
<?php endif; ?>

<?php foreach ($categorias as $c): ?>
<tr>
<td>
<form method="post" class="d-flex gap-2">
<input type="hidden" name="categoria_antiga"
value="<?= htmlspecialchars($c['categoria']) ?>">

<input type="text"
name="categoria_nova"
class="form-control"
value="<?= htmlspecialchars($c['categoria']) ?>">
</td>

<td class="d-flex gap-2">

<button type="submit"
name="salvar_categoria"
class="btn btn-success btn-sm">
Salvar
</button>
</form>

<form method="post"
onsubmit="return confirm(
    'ATENÇÃO: Ao excluir esta categoria, TODOS os produtos relacionados também serão excluídos. Deseja continuar?'
);">

<input type="hidden" name="categoria"
value="<?= htmlspecialchars($c['categoria']) ?>">

<button type="submit"
name="excluir_categoria"
class="btn btn-danger btn-sm">
Excluir
</button>

</form>
</td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
</div>

</body>
</html>
<style>
      /* ===== FUNDO GLOBAL ===== */
html, body {
    background-color: #121212 !important;
    color: #fff;
    min-height: 100%;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
}

/* ===== TÍTULO ===== */
h2 {
    color: #e6b3ff;
    margin-bottom: 20px;
}

/* ===== BOTÃO VOLTAR ===== */
.btn-secondary {
    background: #333;
    border: none;
    color: #fff;
}
.btn-secondary:hover {
    background: #6a0dad;
}

/* ===== CARD ===== */
.card {
    background: #2a0d3f !important;
    border: 2px solid #6a0dad;
    border-radius: 14px;
    color: #fff;
    padding: 20px;
}

/* ===== TABLE ===== */
.table {
    background: #2a0d3f !important;
    color: #fff !important;
    border-color: #6a0dad !important;
    border-radius: 12px;
    overflow: hidden;
}

.table thead {
    background: #1b082b !important;
    color: #e6b3ff !important;
}

.table th,
.table td {
    border-color: #6a0dad !important;
    color: #fff !important;
}

.table tbody tr {
    background: #2a0d3f !important;
}

.table tbody tr:hover {
    background: #1b082b !important;
}

/* ===== LINHA DE "NENHUMA CATEGORIA ENCONTRADA" ===== */
.table tbody td.text-center {
    background: #2a0d3f !important;
    color: #e6b3ff !important;
}

/* ===== FORMULÁRIOS DENTRO DA TABELA ===== */
.table input,
.table select {
    background: #121212 !important;
    color: #fff !important;
    border: 1px solid #555 !important;
}

/* ===== ALERTAS ===== */
.alert {
    background: #2a0d3f !important;
    color: #fff !important;
    border-left: 4px solid #9b4dff;
}

/* ===== LABEL ===== */
label {
    color: #ddd;
    font-weight: 600;
}

/* ===== BOTÕES ===== */
.btn-primary {
    background: #6a0dad !important;
    border: none;
    color: #fff;
}
.btn-primary:hover {
    background: #8b2ec7 !important;
}

.btn-success {
    background: #e6b3ff !important;
    border: none;
    color: #6a0dad !important;
}

.btn-danger {
    background: #ae1302ff !important;
    border: none;
    color: #fff !important;
}

.btn-warning {
    background: #e6b3ff !important;
    border: none;
    color: #6a0dad !important;
}

/* ===== IMAGENS ===== */
img {
    max-width: 70px;
    border-radius: 8px;
    border: 1px solid #151414ff;
}

/* ===== LINKS ===== */
a {
    color: #cb83fbff;
    text-decoration: none;
}
a:hover {
    color: #e6b3ff;
}
>/style>
/*
RESUMO DO CÓDIGO

- Conecta ao banco de dados "cadastro".
- Busca todas as categorias existentes na tabela de produtos.
- Exibe as categorias em uma tabela administrativa.
- Permite editar o nome de uma categoria.
- Atualiza a categoria em todos os produtos relacionados.
- Permite excluir uma categoria.
- Ao excluir, remove também todos os produtos dessa categoria.
- Exibe mensagens de sucesso ou erro para cada ação.
- Interface estilizada com Bootstrap e tema escuro.
*/
