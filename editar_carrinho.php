<?php
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro de conexão com o banco");
}

/* ===== SALVAR (NOVO REGISTRO) ===== */
if (isset($_POST['salvar'])) {
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $tempo = $_POST['tempo_estimado'];

    $stmt = $conn->prepare("
        INSERT INTO coletas (cidade, bairro, dia, horario, tempo_estimado)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $cidade, $bairro, $dia, $horario, $tempo);
    $stmt->execute();
}

/* ===== ATUALIZAR (EDIÇÃO) ===== */
if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $tempo = $_POST['tempo_estimado'];

    $stmt = $conn->prepare("
        UPDATE coletas 
        SET cidade=?, bairro=?, dia=?, horario=?, tempo_estimado=?
        WHERE id=?
    ");
    $stmt->bind_param("sssssi", $cidade, $bairro, $dia, $horario, $tempo, $id);
    $stmt->execute();
}

/* ===== EXCLUIR ===== */
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM coletas WHERE id = $id");
}

/* ===== LISTAR ===== */
$editar_id = $_GET['editar'] ?? null;
$coletas = $conn->query("SELECT * FROM coletas ORDER BY cidade, bairro");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Carrinho | Coletas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
html, body {
    background-color: #121212;
    color: #fff;
    font-family: Arial, sans-serif;
}

h2 { color: #e6b3ff; }

.card {
    background: #2a0d3f;
    border: 2px solid #6a0dad;
    border-radius: 14px;
}

label {
    color: #ddd;
    font-weight: 600;
}

.form-control {
    background: #000;
    color: #fff;
    border: 1px solid #555;
}

.form-control:focus {
    border-color: #9b4dff;
    box-shadow: none;
}

.btn-primary {
    background: #6a0dad;
    border: none;
}
.btn-primary:hover {
    background: #8b2ec7;
}

.btn-danger {
    background: #ae1302ff;
    border: none;
}

.btn-secondary {
    background: #333;
    border: none;
    color: #fff;
}
.btn-secondary:hover {
    background: #6a0dad;
}

.table {
    background: #2a0d3f !important;
    color: #fff;
}

.table thead {
    background: #1b082b !important;
}

.table th {
    color: #380154;
}

.table tbody tr:hover {
    background: #151414ff !important;
}
</style>
</head>

<body>
<div class="container mt-5">

<h2 class="mb-4">📦 Coletas em Domicílio</h2>

<a href="editar_viva.php" class="btn btn-secondary mb-4">⬅ Voltar</a>

<!-- FORMULÁRIO NOVO -->
<div class="card p-4 mb-4">
<form method="POST">
    <div class="row g-3">
        <div class="col-md-4">
            <label>Cidade</label>
            <input type="text" name="cidade" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Bairro</label>
            <input type="text" name="bairro" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Dia</label>
            <input type="text" name="dia" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Horário</label>
            <input type="text" name="horario" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Tempo estimado</label>
            <input type="text" name="tempo_estimado" class="form-control">
        </div>
    </div>

    <button type="submit" name="salvar" class="btn btn-primary mt-4">
        Salvar Coleta
    </button>
</form>
</div>

<!-- TABELA -->
<div class="card p-4">
<table class="table table-bordered table-hover">
<thead>
<tr>
    <th>Cidade</th>
    <th>Bairro</th>
    <th>Dia</th>
    <th>Horário</th>
    <th>Tempo</th>
    <th>Ação</th>
</tr>
</thead>

<tbody>
<?php while ($c = $coletas->fetch_assoc()): ?>
<tr>
<form method="POST">
<td>
<input type="text" name="cidade" class="form-control"
value="<?= htmlspecialchars($c['cidade']) ?>"
<?= $editar_id == $c['id'] ? '' : 'readonly' ?>>
</td>

<td>
<input type="text" name="bairro" class="form-control"
value="<?= htmlspecialchars($c['bairro']) ?>"
<?= $editar_id == $c['id'] ? '' : 'readonly' ?>>
</td>

<td>
<input type="text" name="dia" class="form-control"
value="<?= htmlspecialchars($c['dia']) ?>"
<?= $editar_id == $c['id'] ? '' : 'readonly' ?>>
</td>

<td>
<input type="text" name="horario" class="form-control"
value="<?= htmlspecialchars($c['horario']) ?>"
<?= $editar_id == $c['id'] ? '' : 'readonly' ?>>
</td>

<td>
<input type="text" name="tempo_estimado" class="form-control"
value="<?= htmlspecialchars($c['tempo_estimado']) ?>"
<?= $editar_id == $c['id'] ? '' : 'readonly' ?>>
</td>

<td>
<input type="hidden" name="id" value="<?= $c['id'] ?>">

<?php if ($editar_id == $c['id']): ?>
    <button type="submit" name="atualizar" class="btn btn-primary btn-sm">
        Salvar
    </button>
<?php else: ?>
    <a href="?editar=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">
        Editar
    </a>
<?php endif; ?>

<a href="?excluir=<?= $c['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Excluir coleta?')">
Excluir
</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>
</body>
</html>
