<?php
include "conexaobanco.php"; // Conexão com o banco

// Buscar os dados atuais da página de contato
$sql = "SELECT * FROM site_contato WHERE id = 1"; // Crie a tabela site_contato com id=1
$result = mysqli_query($con, $sql);
$dados = mysqli_fetch_assoc($result);

// Atualizar conteúdo ao enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefone   = $_POST['telefone'] ?? '';
    $email      = $_POST['email'] ?? '';
    $whatsapp   = $_POST['whatsapp'] ?? '';
    $endereco   = $_POST['endereco'] ?? '';
    $instagram  = $_POST['instagram'] ?? '';
    $youtube    = $_POST['youtube'] ?? '';
    $horario    = $_POST['horario'] ?? '';

    $sqlUpdate = "UPDATE site_contato SET
        telefone='$telefone', email='$email', whatsapp='$whatsapp',
        endereco='$endereco', instagram='$instagram', youtube='$youtube', horario='$horario'
        WHERE id = 1";

    if (mysqli_query($con, $sqlUpdate)) {
        $mensagem = "Contato atualizado com sucesso!";
        $dados = $_POST;
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
<title>Editar Contato - Painel Admin</title>
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
.card h5 { color:  #cb83fbff}
.btn-primary { background: #6a0dad; border: none; }
.btn-primary:hover { background: #8b2ec7; }
.btn-voltar { 
    background: #444; 
    border: none; 
    color: #fff; 
    margin-bottom: 20px; 
}
.btn-voltar:hover { background: #6a0dad; color: #fff; }
label { font-weight: bold; }
.alert { background-color: #6a0dad; color: #fff; border: none; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <a class="navbar-brand" href="admin.php">Painel Admin</a>
</nav>

<div class="container">
    <a href="admin.php" class="btn btn-voltar"><= Voltar ao Painel</a>

    <?php if (!empty($mensagem)) echo "<div class='alert alert-info'>$mensagem</div>"; ?>

    <form method="POST">
        <div class="card">
            <h5>Editar Contato</h5>
            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($dados['telefone']) ?>">
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($dados['email']) ?>">
            </div>
            <div class="form-group">
                <label>WhatsApp</label>
                <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($dados['whatsapp']) ?>">
            </div>
            <div class="form-group">
                <label>Endereço</label>
                <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($dados['endereco']) ?>">
            </div>
            <div class="form-group">
                <label>Instagram (URL)</label>
                <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($dados['instagram']) ?>">
            </div>
            <div class="form-group">
                <label>YouTube (URL)</label>
                <input type="text" name="youtube" class="form-control" value="<?= htmlspecialchars($dados['youtube']) ?>">
            </div>
            <div class="form-group">
                <label>Horário de Atendimento</label>
                <textarea name="horario" class="form-control" rows="3"><?= htmlspecialchars($dados['horario']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        </div>
    </form>
</div>

</body>
</html>
<?php
/*
RESUMO DO CÓDIGO (editar_contato.php)

- Inclui o arquivo de conexão com o banco de dados.
- Busca os dados atuais da tabela site_contato (id = 1).
- Exibe um formulário no painel admin para edição das informações de contato.
- Ao enviar o formulário (POST):
  - Recebe telefone, email, WhatsApp, endereço, redes sociais e horário.
  - Atualiza esses dados no banco de dados.
  - Exibe mensagem de sucesso ou erro.
- Utiliza Bootstrap para layout e estilos.
- Possui botão para voltar ao painel administrativo.
*/
?>
