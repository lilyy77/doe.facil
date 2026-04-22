<?php
session_start();

// Verifica se o usuário é admin
if (!isset($_SESSION['usuario_email']) || $_SESSION['isAdmin'] != 1) {
    header("Location: conecte.html");
    exit;
}

$nomeAdmin = $_SESSION['usuario_nome'];

include "conexaobanco.php"; // Conexão com o banco

// Buscar os dados atuais
$sql = "SELECT * FROM site_conteudo WHERE id = 1";
$result = mysqli_query($con, $sql);
$dados = mysqli_fetch_assoc($result);

// Atualizar conteúdo ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo1 = $_POST['titulo1'] ?? '';
    $texto1  = $_POST['texto1'] ?? '';
    $titulo2 = $_POST['titulo2'] ?? '';
    $texto2  = $_POST['texto2'] ?? '';
    $titulo3 = $_POST['titulo3'] ?? '';
    $texto3  = $_POST['texto3'] ?? '';

    // Upload de imagens
    $imagens = [];
    for ($i = 1; $i <= 3; $i++) {
        if (!empty($_FILES["imagem$i"]['name'])) {
            $nomeArquivo = time() . "_" . $_FILES["imagem$i"]['name'];
            move_uploaded_file($_FILES["imagem$i"]['tmp_name'], "img/$nomeArquivo");
            $imagens[$i] = $nomeArquivo;
        } else {
            $imagens[$i] = $dados["imagem$i"]; // mantém imagem antiga se não enviar nova
        }
    }

    // Atualizar no banco
    $sqlUpdate = "UPDATE site_conteudo SET 
        titulo1='$titulo1', texto1='$texto1',
        titulo2='$titulo2', texto2='$texto2',
        titulo3='$titulo3', texto3='$texto3',
        imagem1='{$imagens[1]}', imagem2='{$imagens[2]}', imagem3='{$imagens[3]}'
        WHERE id = 1";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = "Home atualizada com sucesso!";
        $dados = $_POST; // atualiza dados exibidos
    } else {
        $mensagem = "Erro ao atualizar: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Home - Painel Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
<style>
body {
    background: #121212;
    color: #fff;
    font-family: Arial, sans-serif;
    padding-top: 70px;
}
.navbar { 
    background: #2a0d3f;
}
.container { 
    max-width: 700px;
    margin-top: 50px;
}
.card { 
    background: #1c1c1c;
    border: 2px solid #6a0dad;
    padding: 20px;
    margin-bottom: 20px;
}
.card h5 { 
    color: #cb83fbff;
}
.btn-primary { 
    background: #6a0dad; 
    border: none; 
}
.btn-primary:hover { 
    background: #8b2ec7; 
}
.btn-voltar { 
    background: #444; 
    border: none; 
    color: #fff; 
    margin-bottom: 20px; 
}
.btn-voltar:hover { 
    background: #6a0dad; 
    color: #fff; 
}
label { 
    font-weight: bold; 
}
.alert { 
    background-color: #6a0dad; 
    color: #fff; 
    border: none; 
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <a class="navbar-brand" href="admin.php">Painel Admin</a>
</nav>

<div class="container mt-5">
    <!-- Botão de voltar -->
    <a href="admin.php" class="btn btn-voltar"><= Voltar ao Painel</a>

    <?php if (!empty($mensagem)) echo "<div class='alert alert-info'>$mensagem</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <?php for ($i=1; $i<=3; $i++): ?>
        <div class="card p-4">
            <h5>Slide <?= $i ?></h5>
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="titulo<?= $i ?>" class="form-control" value="<?= htmlspecialchars($dados["titulo$i"]) ?>">
            </div>
            <div class="form-group">
                <label>Texto</label>
                <textarea name="texto<?= $i ?>" class="form-control" rows="3"><?= htmlspecialchars($dados["texto$i"]) ?></textarea>
            </div>
            <div class="form-group">
                <label>Imagem atual:</label><br>
                <img src="img/<?= $dados["imagem$i"] ?>" alt="Slide <?= $i ?>" width="200"><br><br>
                <input type="file" name="imagem<?= $i ?>" class="form-control-file">
            </div>
        </div>
        <?php endfor; ?>

        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
<?php
/*
RESUMO DO CÓDIGO (editar_home.php)

- Inicia a sessão.
- Verifica se o usuário está logado e se é administrador.
- Se não for admin, redireciona para a página de login.
- Conecta ao banco de dados via conexaobanco.php.
- Busca os dados atuais da home (títulos, textos e imagens) na tabela site_conteudo.
- Ao enviar o formulário (POST):
    - Recebe títulos e textos dos 3 slides.
    - Processa upload de até 3 imagens.
    - Mantém a imagem antiga caso nenhuma nova seja enviada.
    - Atualiza os dados no banco (id = 1).
- Exibe mensagem de sucesso ou erro.
- Mostra um formulário para edição dos 3 slides:
    - Título
    - Texto
    - Imagem atual + upload de nova imagem
- Possui botão para voltar ao painel admin.
*/
?>
