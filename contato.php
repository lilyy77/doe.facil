<?php
include_once("nova_conexao.php");

// Buscar os dados do contato
$sql = "SELECT * FROM site_contato WHERE id = 1";
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

  <style>
    body {
      font-family: 'Yrsa', serif;
      background-color: #ffdf9f;
      color: #333;
      line-height: 1.6;
    }

    /* ==================== Container de Contato ==================== */
    .container-contact {
      max-width: 700px;
      margin: 160px auto 40px auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
      animation: fadeIn 1s ease-in-out;
    }

    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
      font-size: 2rem;
    }

    .info h2 {
      font-size: 1.1rem;
      color: #f7c66c;
      margin-top: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .info p {
      font-size: 1rem;
      color: #444;
      margin: 5px 0 15px 0;
    }

    .info a {
      color: #000000;
      text-decoration: none;
      transition: color 0.3s;
    }

    .info a:hover {
      color: #ffdf9f;
    }

    .footer-note {
      font-size: 0.9rem;
      color: #777;
      text-align: center;
      margin-top: 20px;
    }

    i {
      color: #ffdf9f;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsivo */
    @media (max-width: 768px) {
      .container-contact {
        margin: 120px 20px;
        padding: 30px;
      }
    }
  </style>

</head>

<body>

  <!-- Cabeçalho / Menu Doe.Fácil -->
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
            <a href="home.php" class="nav-item nav-link" id="Home">Home</a>
            <a href="rede.php" class="nav-item nav-link" id="Ação-menu">Mídia</a>
            <a href="viva.php" class="nav-item nav-link" id="Viva-menu">Doe</a>
            <a href="conecte.php" class="nav-item nav-link" id="Conecte-menu">Conecte</a>
            <a href="contato.php" class="nav-item nav-link active" id="Contato-menu">Contato</a>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Conteúdo de Contato -->
  <section class="container-contact">
    <h1>Fale Conosco</h1>

    <div class="info">
      <h2><i class="fas fa-phone-alt"></i> Telefone:</h2>
      <p><?= htmlspecialchars($dados['telefone']) ?></p>

      <h2><i class="fas fa-envelope"></i> E-mail:</h2>
      <p><?= htmlspecialchars($dados['email']) ?></p>

      <h2><i class="fab fa-whatsapp"></i> WhatsApp:</h2>
      <p><?= htmlspecialchars($dados['whatsapp']) ?></p>

      <h2><i class="fas fa-map-marker-alt"></i> Endereço:</h2>
      <p><?= htmlspecialchars($dados['endereco']) ?></p>
    </div>

    <div class="info social">
      <h2><i class="fas fa-share-alt"></i> Redes Sociais:</h2>
      <p>
        <a href="<?= htmlspecialchars($dados['instagram']) ?>" target="_blank">
          <i class="fab fa-instagram"></i> Instagram
        </a><br>
        <a href="<?= htmlspecialchars($dados['youtube']) ?>" target="_blank">
          <i class="fab fa-youtube"></i> YouTube
        </a>
      </p>
    </div>

    <div class="info">
      <h2><i class="fas fa-clock"></i> Horário de Atendimento:</h2>
      <p><?= nl2br(htmlspecialchars($dados['horario'])) ?></p>
      <p class="footer-note">Obrigada</p>
    </div>
  </section>

</body>

</html>
<?php
/*
RESUMO DO CÓDIGO (contato.php)

- Inclui o arquivo de conexão com o banco de dados.
- Busca os dados da tabela site_contato (registro com id = 1).
- Armazena os dados de contato em um array associativo.

NO HTML:
- Define a estrutura da página de contato do site Doe.Fácil.
- Usa Bootstrap para layout e responsividade.
- Exibe telefone, e-mail, WhatsApp, endereço e horário de atendimento.
- Mostra links para redes sociais (Instagram e YouTube).
- Utiliza htmlspecialchars para evitar problemas de segurança.
- Possui menu fixo no topo e design responsivo.
*/
?>
