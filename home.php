
<?php
include_once("nova_conexao.php");

// Buscar os textos e imagens da tabela
$sql = "SELECT * FROM site_conteudo WHERE id = 1";
$result = mysqli_query($conn, $sql);
$dados = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doe.Fácil</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
    crossorigin="anonymous">
  <link rel="stylesheet" href="barra.css">

  <!-- jQuery e Bootstrap scripts -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/e631c8149a.js" crossorigin="anonymous"></script>

</head>

<body>

  <!-- Cabeçalho -->
  <header>
    <div class="container-fluid" id="nav-container">
      <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <a href="#" class="navbar-brand">
          <img id="logo" src="img/logo.jpg" alt="Doe.Fácil" width="40" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-link">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar-link">
          <div class="navbar-nav">
            <a href="home.php" class="nav-item nav-link active" id="Home">Home</a>
            <a href="rede.php" class="nav-item nav-link" id="Ação-menu">Mídia</a>
            <a href="viva.php" class="nav-item nav-link" id="viva-menu">Doe</a>
            <a href="conecte.php" class="nav-item nav-link" id="Conecte-menu">Conecte</a>
            <a href="contato.php" class="nav-item nav-link" id="Contato-menu">Contato</a>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Conteúdo principal -->
  <main>
    <div class="container-fluid p-0">
      <div id="mainSlider" class="carousel slide" data-ride="carousel">

        <div class="carousel-inner">

          <!-- SLIDE 1 -->
          <div class="carousel-item active">
            <img src="img/<?= $dados['imagem1'] ?>" class="d-block w-100" alt="Slide 1">
            <div class="carousel-caption d-md-block">
              <h2><?= $dados['titulo1'] ?></h2>
              <p><?= nl2br($dados['texto1']) ?></p>
            </div>
          </div>

          <!-- SLIDE 2 -->
          <div class="carousel-item">
            <img src="img/<?= $dados['imagem2'] ?>" class="d-block w-100" alt="Slide 2">
            <div class="carousel-caption d-md-block">
              <h2><?= $dados['titulo2'] ?></h2>
              <p><?= nl2br($dados['texto2']) ?></p>
            </div>
          </div>

          <!-- SLIDE 3 -->
          <div class="carousel-item">
            <img src="img/<?= $dados['imagem3'] ?>" class="d-block w-100" alt="Slide 3">
            <div class="carousel-caption d-md-block">
              <h2><?= $dados['titulo3'] ?></h2>
              <p><?= nl2br($dados['texto3']) ?></p>
            </div>
          </div>

        </div>

        <a class="carousel-control-prev" href="#mainSlider" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Anterior</span>
        </a>

        <a class="carousel-control-next" href="#mainSlider" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Próximo</span>
        </a>

      </div>
    </div>
  </main>

</body>

</html>

<?php
/*
RESUMO DO CÓDIGO (home.php)

- Inclui a conexão com o banco de dados.
- Busca os textos e imagens da tabela site_conteudo (registro id = 1).
- Armazena os dados em um array associativo ($dados).

INTERFACE:
- Usa Bootstrap 4 e Font Awesome.
- Barra de navegação fixa no topo com links do site.
- Layout responsivo.

FUNCIONALIDADE PRINCIPAL:
- Carousel (slider) com 3 slides.
- Cada slide exibe:
  • Imagem vinda do banco (imagem1, imagem2, imagem3)
  • Título dinâmico (titulo1, titulo2, titulo3)
  • Texto dinâmico com quebra de linha (texto1, texto2, texto3)

OBJETIVO:
- Exibir o conteúdo principal do site de forma dinâmica,
  permitindo alteração de textos e imagens diretamente pelo banco.
*/
?>
