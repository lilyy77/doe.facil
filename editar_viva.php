<?php
session_start();

/* ======================
   CONEXÃO
====================== */
$conn = new mysqli("localhost", "root", "", "cadastro");
if ($conn->connect_error) {
    die("Erro de conexão");
}

/* ======================
   MENSAGEM (FLASH)
====================== */
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

/* ======================
   DADOS ANTIGOS (ERRO)
====================== */
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

/* ======================
   BUSCAR CATEGORIAS
====================== */
$catResult = $conn->query("SELECT DISTINCT categoria FROM produtos ORDER BY categoria");
$categorias = $catResult ? $catResult->fetch_all(MYSQLI_ASSOC) : [];

/* ======================
   ADICIONAR CARD
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {

    $nome = trim($_POST['nome'] ?? '');
    $categoria = $_POST['categoria'] ?? '';
    $nova_categoria = trim($_POST['nova_categoria'] ?? '');
    $status = $_POST['status'] ?? '';

    $_SESSION['old'] = $_POST;

    if ($nome === '') {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Digite o nome do produto.</div>";
    } elseif ($categoria === '') {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Selecione uma categoria.</div>";
    } elseif ($categoria === 'Outra' && $nova_categoria === '') {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Digite o nome da nova categoria.</div>";
    } elseif ($status === '') {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Selecione o status.</div>";
    } elseif (empty($_FILES['imagem']['name'])) {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Selecione uma imagem.</div>";
    } else {

        if ($categoria === 'Outra') {
            $categoria = $nova_categoria;
        }

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755);
        }

        $img = time().'_'.basename($_FILES['imagem']['name']);
        $path = "uploads/$img";

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $path)) {
            $stmt = $conn->prepare(
                "INSERT INTO produtos (nome, categoria, imagem, status) VALUES (?,?,?,?)"
            );
            $stmt->bind_param("ssss", $nome, $categoria, $path, $status);
            $stmt->execute();
            $stmt->close();

            $_SESSION['msg'] = "<div class='alert alert-success'>Novo card adicionado com sucesso.</div>";
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao enviar imagem.</div>";
        }
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ======================
   SALVAR
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {

    foreach ($_POST['produtos'] as $id => $p) {

        if ($_POST['salvar'] != $id) continue;
        if (trim($p['nome']) === '') continue;

        $nome = trim($p['nome']);
        $status = $p['status'];

        $categoria = $p['categoria'];
        if ($categoria === 'Outra' && !empty($p['nova_categoria'])) {
            $categoria = trim($p['nova_categoria']);
        }

        $imagem = $p['imagem_antiga'];

        if (!empty($_FILES['produtos']['name'][$id]['imagem'])) {
            $img = time().'_'.basename($_FILES['produtos']['name'][$id]['imagem']);
            $path = "uploads/$img";
            move_uploaded_file($_FILES['produtos']['tmp_name'][$id]['imagem'], $path);
            $imagem = $path;
        }

        $stmt = $conn->prepare(
            "UPDATE produtos SET nome=?, categoria=?, imagem=?, status=? WHERE id=?"
        );
        $stmt->bind_param("ssssi", $nome, $categoria, $imagem, $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['msg'] = "<div class='alert alert-success'>Alterações salvas com sucesso.</div>";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ======================
   EXCLUIR
====================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {

    $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $_POST['id']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "<div class='alert alert-success'>Card excluído com sucesso.</div>";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* ======================
   LISTAR PRODUTOS
====================== */
$result = $conn->query("SELECT * FROM produtos ORDER BY status, categoria, nome");
$produtos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Administrador</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
img { max-width:80px; border-radius:6px; }
</style>
</head>

<body class="p-4">

<h2>Painel do Administrador</h2>
<a href="admin.php" class="btn btn-secondary mb-2"><= Voltar</a>
<a href="categorias.php" class="btn btn-secondary mb-2">Categorias</a>
<a href="editar_carrinho.php" class="btn btn-secondary mb-2">Carrinho</a>

<?= $msg ?>

<!-- ADICIONAR CARD (INALTERADO) -->
<div class="card p-3 mb-4">
<h5>Adicionar Card</h5>

<form method="post" enctype="multipart/form-data" class="row g-2 align-items-end">
    <div class="col-md-3">
        <label>Nome</label>
      <input type="text" name="nome" class="form-control">
    </div>

    <div class="col-md-3">
        <label>Categoria</label>
        <select name="categoria" id="categoriaSelect" class="form-select">
            <option value="">Selecione</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['categoria'] ?>"><?= $c['categoria'] ?></option>
            <?php endforeach; ?>
            <option value="Outra">Adicionar categoria...</option>
        </select>

        <input type="text" name="nova_categoria" id="novaCategoria"
               class="form-control mt-2" placeholder="Nova categoria"
               style="display:none;">
    </div>

    <div class="col-md-2">
        <label>Status</label>
        <select name="status" class="form-select">
            <option value="">Selecione</option>
            <option value="urgente">Urgente</option>
            <option value="ativo">Ativo</option>
            <option value="inativo">Inativo</option>
        </select>
    </div>

    <div class="col-md-3">
        <label>Imagem</label>
        <input type="file" name="imagem" class="form-control">
    </div>

    <div class="col-md-1">
        <button type="submit" name="adicionar" class="btn btn-primary w-100">Adicionar</button>
    </div>
</form>
</div>

<!-- TABELA -->
<form method="post" enctype="multipart/form-data">

<table class="table table-bordered align-middle">
<thead class="table-dark">
<tr>
<th>ID</th><th>Nome</th><th>Categoria</th><th>Imagem</th><th>Status</th><th>Ações</th>
</tr>
</thead>

<tbody>
<?php foreach ($produtos as $p): ?>
<tr>
<td><?= $p['id'] ?></td>

<td>
<input type="text" name="produtos[<?= $p['id'] ?>][nome]"
       value="<?= htmlspecialchars($p['nome']) ?>" class="form-control">
</td>

<td>
<select name="produtos[<?= $p['id'] ?>][categoria]"
        class="form-select categoria-select"
        data-id="<?= $p['id'] ?>">
<?php foreach ($categorias as $c): ?>
<option value="<?= $c['categoria'] ?>" <?= $p['categoria']==$c['categoria']?'selected':'' ?>>
<?= $c['categoria'] ?>
</option>
<?php endforeach; ?>
<option value="Outra">Adicionar categoria...</option>
</select>

<input type="text"
name="produtos[<?= $p['id'] ?>][nova_categoria]"
id="nova<?= $p['id'] ?>"
class="form-control mt-1"
placeholder="Nova categoria"
style="display:none;">
</td>

<td>
<input type="hidden" name="produtos[<?= $p['id'] ?>][imagem_antiga]" value="<?= $p['imagem'] ?>">
<input type="file" name="produtos[<?= $p['id'] ?>][imagem]" class="form-control mb-1">
<img src="<?= $p['imagem'] ?>">
</td>

<td>
<select name="produtos[<?= $p['id'] ?>][status]" class="form-select">
<option value="urgente" <?= $p['status']=='urgente'?'selected':'' ?>>Urgente</option>
<option value="ativo" <?= $p['status']=='ativo'?'selected':'' ?>>Ativo</option>
<option value="inativo" <?= $p['status']=='inativo'?'selected':'' ?>>Inativo</option>
</select>
</td>

<td>
<button type="submit" name="salvar" value="<?= $p['id'] ?>" class="btn btn-success btn-sm">Salvar</button>

<button type="submit"
        name="excluir"
        class="btn btn-danger btn-sm"
        onclick="document.getElementById('id<?= $p['id'] ?>').value=<?= $p['id'] ?>; return confirm('Tem certeza?');">
Excluir
</button>

<input type="hidden" id="id<?= $p['id'] ?>" name="id">
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</form>

<script>
document.getElementById('categoriaSelect')?.addEventListener('change', e => {
    document.getElementById('novaCategoria').style.display =
        e.target.value === 'Outra' ? 'block' : 'none';
});

document.querySelectorAll('.categoria-select').forEach(sel => {
    sel.addEventListener('change', function () {
        const id = this.dataset.id;
        document.getElementById('nova'+id).style.display =
            this.value === 'Outra' ? 'block' : 'none';
    });
});
</script>

</body>
</html>

<style>
    /* ===== FUNDO GLOBAL ===== */
html, body {
    background-color: #121212;
    color: #fff;
    min-height: 100%;
    font-family: Arial, sans-serif;
}

/* ===== TÍTULO ===== */
h2 {
    color: #e6b3ff;
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

/* ===== CARD ROXO ESCURO ===== */
.card {
    background: #2a0d3f;
    border: 2px solid #6a0dad;
    border-radius: 14px;
    color: #fff;
}

/* ===== LABEL ===== */
label {
    color: #ddd;
    font-weight: 600;
}

/* ===== INPUTS ===== */
.form-control,
.form-select {
    background: #000;
    color: #fff;
    border: 1px solid #555;
}

.form-control::placeholder {
    color: #aaa;
}

.form-control:focus,
.form-select:focus {
    border-color: #9b4dff;
    box-shadow: none;
}

/* ===== FILE ===== */
input[type="file"] {
    background: #000;
    color: #fff;
}

/* ===== BOTÕES ===== */
.btn-primary {
    background: #6a0dad;
    border: none;
    color: #fff;
}
.btn-primary:hover {
    background: #8b2ec7;
}

.btn-success {
    background: #e6b3ff;
    border: none;
    color: #6a0dad;
}

.btn-danger {
    background: #ae1302ff;
    border: none;
    color: #fff;
}

.btn-warning {
    background: #e6b3ff;
    border: none;
    color: 
}

/* ===== ALERTAS ===== */
.alert {
    background: #2a0d3f;
    color: #fff;
    border-left: 4px solid #9b4dff;
}

/* ===== TABELA ===== */
.table {
    background: #2a0d3f !important; /* força fundo escuro */
    color: #fff;
    border-color: #6a0dad;
}

.table thead {
    background: #1b082b !important;
}

.table th {
    color: #e6b3ff;
    border-color: #6a0dad;
}

.table td,
.table tbody,
.table tbody tr {
    background: #2a0d3f !important; /* força fundo escuro nas linhas */
    color: #fff;
    border-color: #444;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #151414ff !important;
}

/* ===== CAMPOS NA TABELA ===== */
.table input,
.table select {
    background:  #151414ff !important;
    color: #fff;
    border: 1px solid #151414ff;
}

/* ===== IMAGENS ===== */
img {
    max-width: 70px;
    border-radius: 8px;
    border: 1px solid #151414ff;
}
</style>

<?php
/*
RESUMO DO CÓDIGO (painel_produtos.php)

- Inicia a sessão.
- Conecta ao banco de dados "cadastro".
- Usa mensagens flash ($_SESSION['msg']) para sucesso/erro.
- Mantém dados antigos do formulário em caso de erro.
- Busca categorias distintas da tabela produtos.

FUNCIONALIDADES:
1) ADICIONAR CARD
   - Valida nome, categoria, status e imagem.
   - Permite criar nova categoria.
   - Faz upload da imagem (pasta uploads).
   - Insere o produto no banco.

2) EDITAR CARD
   - Permite editar nome, categoria, status e imagem.
   - Mantém imagem antiga se não enviar nova.
   - Atualiza apenas o produto clicado em "Salvar".

3) EXCLUIR CARD
   - Remove o produto pelo ID.
   - Confirma antes de excluir.

4) LISTAR PRODUTOS
   - Exibe todos os produtos em tabela.
   - Ordena por status, categoria e nome.
   - Permite edição direta na tabela.

INTERFACE:
- Painel administrativo com Bootstrap.
- Tema escuro personalizado.
- Botões para voltar ao admin e gerenciar categorias.
- JS para exibir campo de nova categoria quando selecionado "Outra".

OBJETIVO:
Gerenciar totalmente os cards/produtos do site (CRUD completo).
*/
?>
