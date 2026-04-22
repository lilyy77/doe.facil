<?php
session_start();

// Verifica se está logado e se é admin
if (!isset($_SESSION['usuario_email']) || $_SESSION['isAdmin'] != 1) {
    header("Location: conecte.php");
    exit;
}

$nomeAdmin = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel Admin - Doe.Fácil</title>

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
    background-color: #1c1c1c;
    border: 2px solid #6a0dad;
    color: #fff;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(106, 13, 173, 0.8);
}

.btn-edit {
    background-color: #6a0dad;
    border: none;
    color: #fff;
}

.btn-edit:hover {
    background-color: #8b2ec7;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand" href="#">Doe.Fácil - Admin</a>
    <div class="ml-auto">
        <span class="navbar-text mr-3">
            Olá, <?php echo htmlspecialchars($nomeAdmin); ?>!
        </span>
        <a href="logout.php" class="btn btn-danger btn-sm">Sair</a>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4 text-center">Painel Administrativo</h1>

    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h3>Home</h3>
                <p>Editar textos e imagens da página inicial.</p>
                <a href="editar_home.php" class="btn btn-edit">Editar</a>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h3>Mídia</h3>
                <p>Gerenciar posts, vídeos e imagens.</p>
                <a href="editar_midia.php" class="btn btn-edit">Editar</a>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h3>Doe / Viva</h3>
                <p>Atualizar campanhas de doação.</p>
                <a href="editar_viva.php" class="btn btn-edit">Editar</a>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h3>Contato</h3>
                <p>Editar dados de contato.</p>
                <a href="editar_contato.php" class="btn btn-edit">Editar</a>
            </div>
        </div>

         <div class="col-md-6 mb-4">
            <div class="card p-3">
                <h3>Notificações</h3>
                <p>Editar dados de notificações.</p>
                <a href="notificacoes.php" class="btn btn-edit">Editar</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
             