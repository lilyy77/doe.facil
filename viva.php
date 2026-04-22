<?php
session_start();

include_once("nova_conexao.php");
//$conn = new mysqli("localhost:3307", "root", "", "cadastro");
//if ($conn->connect_error) {
//    die("Erro: " . $conn->connect_error);
//}

// SOMENTE URGENTES
$sqlUrgentes = "SELECT * FROM produtos WHERE status = 'urgente'";
$urgentes = $conn->query($sqlUrgentes);

// SOMENTE ATIVOS
$sqlAtivos = "SELECT * FROM produtos WHERE status = 'ativo'";
$ativos = $conn->query($sqlAtivos);

// Categorias
$sqlCategorias = "SELECT DISTINCT categoria FROM produtos ORDER BY categoria";
$categorias = $conn->query($sqlCategorias);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Doe.Fácil</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="barra.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

  <style>
    /* Seus estilos existentes */
    #nav-container nav { padding: 0.5rem 2rem; }
    .navbar-left { display: flex; align-items: center; gap: 0.5rem; }
    .navbar-center { flex-grow: 1; display: flex; align-items: center; max-width: 700px; width: 100%; }
    .navbar-center .dropdown { margin-right: 0.5rem; }
    #search { flex-grow: 1; padding: 0.375rem 0.75rem; border: 1px solid #000000; border-radius: 0.25rem 0 0 0.25rem; outline: none; }
    .cart-icon { font-size: 1.5rem; cursor: pointer; padding: 0.375rem 0.75rem; }
    .navbar-right { display: flex; align-items: center; gap: 1rem; }
    .dropdown-toggle::after { display: none; }
    .sub-bar { background-color: #b40a0a; padding: 1.5rem 0; }
    .sub-bar h5 { color: #fff; font-weight: bold; text-align: center; margin-bottom: 1rem; }
    #carouselWrapper { display: flex; gap: 1rem; overflow-x: auto; padding: 0 1rem; scroll-behavior: smooth; }
    #carouselWrapper::-webkit-scrollbar { display: none; }
    .produto-card { flex: 0 0 auto; width: 14rem; border-radius: 12px; overflow: hidden; background-color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .produto-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); }
    .produto-card img { height: 160px; object-fit: cover; }
    .grid-card { border-radius: 12px; overflow: hidden; background-color: #fff; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #ddd; }
    .grid-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); }
    .grid-card img { height: 160px; object-fit: cover; }

    /* Nova mensagem discreta acima do carrinho */
    #mensagem-carrinho {
      position: absolute;
      top: 60px;
      right: 20px;
      background-color: #ffc107;
      color: black;
      padding: 8px 15px;
      border-radius: 12px;
      display: none;
      font-weight: bold;
      z-index: 1000;
      opacity: 0;
      transition: opacity 0.4s ease;
    }
  </style>
</head>

<body>

<header>
  <div class="container-fluid" id="nav-container">
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <div class="navbar-left">
        <a href="#" class="navbar-brand p-0">
          <img id="logo" src="img/logo.jpg" alt="Doe.Fácil" width="40" height="40" />
        </a>
      </div>

      <div class="navbar-center">
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownCategorias" data-bs-toggle="dropdown" aria-expanded="false">
            &#8942;
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownCategorias">
            <?php while ($cat = $categorias->fetch_assoc()): ?>
              <li>
                <a class="dropdown-item" href="3.php?cat=<?= urlencode($cat['categoria']) ?>">
                  <?= htmlspecialchars($cat['categoria']) ?>
                </a>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>

        <form action="buscarPagina.php" method="GET" style="flex-grow:1;">
            <input type="search" name="busca" id="search" placeholder="Pesquisar produtos..." autocomplete="off" style="width:100%;" required />
        </form>

        <!-- Botão do carrinho -->
        <a href="carrinho.php" style="text-decoration: none; font-size: 20px;">🛒 Carrinho</a>
      </div>

      <div class="navbar-right collapse navbar-collapse justify-content-end">
        <div class="navbar-nav">
          <a href="home.php" class="nav-link">Home</a>
          <a href="rede.php" class="nav-link">Mídia</a>
          <a href="viva.php" class="nav-link active">Doe</a>
          <a href="conecte.php" class="nav-link">Conecte</a>
          <a href="contato.php" class="nav-link">Contato</a>
        </div>
      </div>
    </nav>
  </div>
</header>

<div style="height:60px;"></div>

<!-- Mensagem do carrinho -->
<div id="mensagem-carrinho">🛒 Produto adicionado!</div>

<!-- CAMPANHAS EMERGENCIAIS -->
<div class="sub-bar">
  <h5>Campanhas Emergenciais</h5>
  <div id="carouselWrapper">
    <?php while ($p = $urgentes->fetch_assoc()): ?>
      <div class="card produto-card">
        <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
        <div class="card-body text-center">
          <h6><?= htmlspecialchars($p['nome']) ?></h6>
          <p class="small"><?= htmlspecialchars($p['categoria']) ?></p>
          <p class="small">Status: <?= htmlspecialchars($p['status']) ?></p>
          <button class="btn btn-dark btn-sm" onclick="adicionarAoCarrinho(<?= $p['id'] ?>)">🛒 Doar</button>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- PRODUTOS ATIVOS -->
<section class="container my-5">
  <h5 class="text-center mb-4">Outros Produtos para Doação</h5>
  <div class="row g-4">
    <?php while ($p = $ativos->fetch_assoc()): ?>
      <div class="col-10 col-md-4 col-lg-2">
        <div class="card h-100 grid-card">
          <img src="<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
          <div class="card-body text-center">
            <h6><?= htmlspecialchars($p['nome']) ?></h6>
            <p class="small"><?= htmlspecialchars($p['categoria']) ?></p>
            <button class="btn btn-light btn-sm" onclick="adicionarAoCarrinho(<?= $p['id'] ?>)">Doar</button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<script>
function adicionarAoCarrinho(produtoId) {
    fetch('adicionar_carrinho.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'produto_id=' + produtoId
    })
    .then(res => res.text())
    .then(data => {
        const msg = document.getElementById('mensagem-carrinho');
        msg.style.display = 'block';
        msg.style.opacity = '1';
        setTimeout(() => { msg.style.opacity = '0'; }, 2000);
        setTimeout(() => { msg.style.display = 'none'; }, 2400);
    });
}
</script>

<script>
  const carousel = document.getElementById('carouselWrapper');
  const firstCard = carousel.querySelector('.produto-card');
  if (firstCard) {
    const cardWidth = firstCard.offsetWidth + 16;
    let scrollAmount = 0;
    let scrollCount = 0;
    const maxScrolls = 4;
    function autoScroll() {
      if (scrollCount >= maxScrolls) { scrollAmount = 0; scrollCount = 0; carousel.scrollTo({ left: 0, behavior: 'smooth' }); return; }
      scrollAmount += cardWidth;
      carousel.scrollTo({ left: scrollAmount, behavior: 'smooth' });
      scrollCount++;
    }
    setInterval(autoScroll, 3000);
  }
</script>

</body>
</html>

<?php
/*
RESUMO DA PÁGINA – DOE (VIVA)

- Inicia sessão e conecta ao banco de dados (cadastro).
- Busca produtos com status:
  • "urgente" para campanhas emergenciais.
  • "ativo" para produtos comuns.
- Busca categorias distintas para o menu dropdown.
- Exibe navbar fixa com:
  • Logo
  • Menu de categorias
  • Campo de busca
  • Link do carrinho
  • Links de navegação (Home, Mídia, Doe, Conecte, Contato).
- Mostra campanhas emergenciais em um carrossel horizontal automático.
- Lista outros produtos ativos em grid responsivo.
- Cada produto possui botão “Doar” que:
  • Envia o produto ao carrinho via fetch (AJAX).
  • Exibe mensagem temporária “Produto adicionado”.
- Usa Bootstrap 5 para layout e responsividade.
- Aplica efeitos visuais (hover, sombra, transições).
- Código organizado para separação de urgentes, ativos e categorias.
*/
?>