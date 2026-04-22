<?php
session_start();

if (!isset($_SESSION['usuario_email']) || $_SESSION['isAdmin'] != 1) {
    header("Location: conecte.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "cadastro");

// ===============================
// SALVAR PUBLICAÇÃO
// ===============================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $nomeArquivo = "";

    if(isset($_FILES['midia']) && $_FILES['midia']['error'] == 0){

        $pasta = "uploads/";

        if(!is_dir($pasta)){
            mkdir($pasta, 0777, true);
        }

        $nomeArquivo = $pasta . time() . "_" . basename($_FILES["midia"]["name"]);
        move_uploaded_file($_FILES["midia"]["tmp_name"], $nomeArquivo);
    }

    $conn->query("INSERT INTO publicacoes (titulo, descricao, midia)
                  VALUES ('$titulo', '$descricao', '$nomeArquivo')");

    header("Location: editar_midia.php");
    exit;
}

// ===============================
// EXCLUIR
// ===============================
if(isset($_GET['excluir'])){
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM publicacoes WHERE id=$id");
    header("Location: editar_midia.php");
    exit;
}

$nomeAdmin = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Mídia</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">

<style>
body {
    padding-top: 70px;
    background-color: #121212;
    color: #fff;
    font-family: Arial, sans-serif;
}

.navbar {
    background-color: #2a0d3f !important;
}

.card {
    background: #1c1c1c;
    border: 2px solid #6a0dad;
    color: #fff;
    border-radius: 12px;
}

.btn-primary {
    background: #6a0dad;
    border: none;
}

.btn-primary:hover {
    background: #8b2ec7;
}

.btn-danger {
    background: #ae1302;
    border: none;
}

.post-media {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 10px;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand" href="admin.php">Doe.Fácil - Admin</a>
    <div class="ml-auto">
        <span class="navbar-text mr-3">
            Olá, <?php echo htmlspecialchars($nomeAdmin); ?>!
        </span>
        <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="text-center mb-4">Gerenciar Publicações</h2>
    <a href="admin.php" class="btn btn-secondary mb-2"><= Voltar</a>

    <!-- FORM PUBLICAR -->
    <div class="card p-4 mb-5">
        <h4>Nova Publicação</h4>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titulo" class="form-control mb-3"
            placeholder="Título" required>

            <textarea name="descricao" class="form-control mb-3"
            rows="4" placeholder="Descrição" required></textarea>

            <input type="file" name="midia" class="form-control mb-3">

            <button type="submit" class="btn btn-primary btn-block">
                Publicar
            </button>
        </form>
    </div>

    <!-- LISTAGEM -->
    <?php
    $result = $conn->query("SELECT * FROM publicacoes ORDER BY id DESC");

    while($row = $result->fetch_assoc()){
    ?>
        <div class="card p-4 mb-4">
            <h5 style="color:#e6b3ff;"><?php echo $row['titulo']; ?></h5>

            <p><?php echo $row['descricao']; ?></p>

            <?php if($row['midia']){ ?>
                <img src="<?php echo $row['midia']; ?>" class="post-media">
            <?php } ?>

            <div class="text-right mt-3">
                <a href="?excluir=<?php echo $row['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Deseja excluir esta publicação?')">
                   Excluir
                </a>
            </div>
        </div>
    <?php } ?>

</div>

</body>
</html>